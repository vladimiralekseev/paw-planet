<?php

namespace api\models\forms;

use common\models\Pet;
use common\models\PetAvailable;
use common\models\UserRequestPet;
use Yii;
use yii\base\Model;

class UserRequestPetForm extends Model
{
    public $pet_id;
    public $type;

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
            [['pet_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pet::class, 'targetAttribute' => ['pet_id' => 'id']],
            [['type', 'pet_id'], 'required'],
            [['pet_id'], 'notForSelf'],
            [['pet_id'], 'checkDuplicate'],
            [['type'], 'in', 'range' => [UserRequestPet::TYPE_WALK, UserRequestPet::TYPE_BORROW]],
        ];
    }

    public function notForSelf(): void
    {
        $pet = Pet::find()->where(['id' => $this->pet_id, 'user_id' => Yii::$app->user->identity->id])->one();
        if ($pet) {
            $this->addError('pet_id', 'You can\'t create a request for your pet');
        }
    }

    public function checkDuplicate(): void
    {
        $pet = UserRequestPet::find()->where(
            [
                'pet_id'           => $this->pet_id,
                'request_owner_id' => Yii::$app->user->identity->id,
                'type'             => $this->type,
                'status'           => UserRequestPet::STATUS_NEW,
            ]
        )->one();
        if ($pet) {
            $this->addError('pet_id', 'You already has created a request for this pet');
        }
        $pet = UserRequestPet::find()->where(
            [
                'pet_id'           => $this->pet_id,
                'request_owner_id' => Yii::$app->user->identity->id,
                'type'             => $this->type,
                'status'           => UserRequestPet::STATUS_APPROVED,
            ]
        )->one();
        if ($pet) {
            $this->addError('pet_id', 'This request has already approved');
        }
    }

    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        /** @var UserRequestPet $pet */
        $userRequestPet = new UserRequestPet();
        $userRequestPet->pet_id = $this->pet_id;
        $userRequestPet->request_owner_id = Yii::$app->user->identity->id;
        $userRequestPet->type = $this->type;
        $userRequestPet->status = UserRequestPet::STATUS_NEW;
        if ($userRequestPet->validate() && $userRequestPet->save()) {
            $this->sendEmail($userRequestPet);
            return true;
        }
        $this->addErrors($userRequestPet->getErrors());
        return false;
    }

    private function sendEmail(UserRequestPet $request): bool
    {
        return Yii::$app
            ->mailer
            ->compose(
                'request/request-new',
                ['request' => $request]
            )
            ->setFrom(Yii::$app->params['emailFrom'])
            ->setTo($request->pet->user->email)
            ->setBcc(Yii::$app->params['emailBcc'])
            ->setSubject('New request')
            ->send();
    }
}
