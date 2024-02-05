<?php

namespace api\models\forms;

use common\models\LostPet;
use common\models\ResponseLostPet;
use Yii;
use yii\base\Model;

class ResponseLostPetStatusForm extends Model
{
    public $id;
    public $status;

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
                ['id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => ResponseLostPet::class,
                'targetAttribute' => ['id' => 'id']
            ],
            [['status', 'id'], 'required'],
            [
                ['status'],
                'in',
                'range' => [
                    ResponseLostPet::STATUS_APPROVED,
                    ResponseLostPet::STATUS_REJECTED,
                    ResponseLostPet::STATUS_CANCELED,
                ]
            ],
            [['id'], 'checkAccess'],
        ];
    }

    public function checkAccess(): void
    {
        /** @var ResponseLostPet $response */
        $response = ResponseLostPet::find()->where([ResponseLostPet::tableName() . '.id' => $this->id])
            ->andWhere(
                [
                    'or',
                    ['request_owner_id' => Yii::$app->user->identity->id],
                    ['user_id' => Yii::$app->user->identity->id],
                ]
            )
            ->joinWith(['lostPet'])
            ->one();
        if (!$response) {
            $this->addError('id', 'You haven\'t an access to modify this response');
        }
        if (($response->request_owner_id === Yii::$app->user->identity->id) && $this->status !== ResponseLostPet::STATUS_CANCELED) {
            $this->addError('status', 'You haven\'t an access to set this status');
        }
        if ($response->lostPet->user_id === Yii::$app->user->identity->id && $this->status ===
            ResponseLostPet::STATUS_CANCELED) {
            $this->addError('status', 'You haven\'t an access to set this status');
        }
    }

    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        /** @var ResponseLostPet $responseLostPet */
        /** @var ResponseLostPet $responseLostPetOld */
        $responseLostPet = ResponseLostPet::find()->where(['id' => $this->id])->one();
        $responseLostPetOld = clone $responseLostPet;
        $responseLostPet->status = $this->status;
        if ($responseLostPet->validate() && $responseLostPet->save()) {
            if ($responseLostPetOld->status === ResponseLostPet::STATUS_NEW &&
                $responseLostPet->status === ResponseLostPet::STATUS_APPROVED) {
                $responseLostPet->lostPet->status = LostPet::STATUS_FINISHED;
                $responseLostPet->lostPet->save(false);
            }
            $this->sendEmail($responseLostPetOld, $responseLostPet);
            return true;
        }
        $this->addErrors($responseLostPet->getErrors());
        return false;
    }

    private function sendEmail(ResponseLostPet $responseOld, ResponseLostPet $response): bool
    {
        if ($responseOld->status === ResponseLostPet::STATUS_NEW && $response->status === ResponseLostPet::STATUS_APPROVED) {
            return Yii::$app
                ->mailer
                ->compose(
                    'response/response-approved',
                    ['response' => $response]
                )
                ->setFrom(Yii::$app->params['emailFrom'])
                ->setTo($response->requestOwner->email)
                ->setBcc(Yii::$app->params['emailBcc'])
                ->setSubject('Response is approved')
                ->send();
        }
        if ($responseOld->status === ResponseLostPet::STATUS_NEW && $response->status === ResponseLostPet::STATUS_REJECTED) {
            return Yii::$app
                ->mailer
                ->compose(
                    'response/response-rejected',
                    ['response' => $response]
                )
                ->setFrom(Yii::$app->params['emailFrom'])
                ->setTo($response->requestOwner->email)
                ->setBcc(Yii::$app->params['emailBcc'])
                ->setSubject('Response is rejected')
                ->send();
        }
        return false;
    }
}
