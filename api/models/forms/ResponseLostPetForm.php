<?php

namespace api\models\forms;

use common\models\LostPet;
use common\models\ResponseLostPet;
use Yii;
use yii\base\Model;

class ResponseLostPetForm extends Model
{
    public $lost_pet_id;

    public function formName(): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [
                ['lost_pet_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => LostPet::class,
                'targetAttribute' => ['lost_pet_id' => 'id']
            ],
            [['lost_pet_id'], 'required'],
            [['lost_pet_id'], 'notForSelf'],
            [['lost_pet_id'], 'checkDuplicate'],
        ];
    }

    public function notForSelf(): void
    {
        $lostPet = LostPet::find()->where(['id' => $this->lost_pet_id, 'user_id' => Yii::$app->user->identity->id])
            ->one();
        if ($lostPet) {
            $this->addError('lost_pet_id', 'You can\'t create a request for your lost pet');
        }
    }

    public function checkDuplicate(): void
    {
        $response = ResponseLostPet::find()->where(
            [
                'lost_pet_id'      => $this->lost_pet_id,
                'request_owner_id' => Yii::$app->user->identity->id,
                'status'           => ResponseLostPet::STATUS_NEW,
            ]
        )->one();
        if ($response) {
            $this->addError('lost_pet_id', 'You already has created a request for this lost pet');
        }
        $response = ResponseLostPet::find()->where(
            [
                'lost_pet_id'      => $this->lost_pet_id,
                'request_owner_id' => Yii::$app->user->identity->id,
                'status'           => ResponseLostPet::STATUS_APPROVED,
            ]
        )->one();
        if ($response) {
            $this->addError('lost_pet_id', 'This request has already approved');
        }
    }

    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        /** @var ResponseLostPet $pet */
        $response = new ResponseLostPet();
        $response->lost_pet_id = $this->lost_pet_id;
        $response->request_owner_id = Yii::$app->user->identity->id;
        $response->status = ResponseLostPet::STATUS_NEW;
        if ($response->validate() && $response->save()) {
            $this->sendEmail($response);
            return true;
        }
        $this->addErrors($response->getErrors());
        return false;
    }

    private function sendEmail(ResponseLostPet $response): bool
    {
        return Yii::$app
            ->mailer
            ->compose(
                'response/response-new',
                ['response' => $response]
            )
            ->setFrom(Yii::$app->params['emailFrom'])
            ->setTo($response->lostPet->user->email)
            ->setBcc(Yii::$app->params['emailBcc'])
            ->setSubject('New response')
            ->send();
    }
}
