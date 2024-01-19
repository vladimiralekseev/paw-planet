<?php

namespace api\models\forms;

use common\models\UserRequestPet;
use Yii;
use yii\base\Model;

class UserRequestPetStatusForm extends Model
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
                'targetClass'     => UserRequestPet::class,
                'targetAttribute' => ['id' => 'id']
            ],
            [['status', 'id'], 'required'],
            [
                ['status'],
                'in',
                'range' => [
                    UserRequestPet::STATUS_APPROVED,
                    UserRequestPet::STATUS_REJECTED,
                    UserRequestPet::STATUS_CANCELED,
                ]
            ],
            [['id'], 'checkAccess'],
        ];
    }

    public function checkAccess(): void
    {
        /** @var UserRequestPet $request */
        $request = UserRequestPet::find()->where([UserRequestPet::tableName() . '.id' => $this->id])
            ->andWhere(
                [
                    'or',
                    ['request_owner_id' => Yii::$app->user->identity->id],
                    ['user_id' => Yii::$app->user->identity->id],
                ]
            )
            ->joinWith(['pet'])
            ->one();
        if (!$request) {
            $this->addError('id', 'You haven\'t an access to modify this request');
        }
        if (($request->request_owner_id === Yii::$app->user->identity->id) && $this->status !== UserRequestPet::STATUS_CANCELED) {
            $this->addError('status', 'You haven\'t an access to set this status');
        }
        if ($request->pet->user_id === Yii::$app->user->identity->id && $this->status === UserRequestPet::STATUS_CANCELED) {
            $this->addError('status', 'You haven\'t an access to set this status');
        }
    }

    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        /** @var UserRequestPet $userRequestPet */
        /** @var UserRequestPet $userRequestPetOld */
        $userRequestPet = UserRequestPet::find()->where(['id' => $this->id])->one();
        $userRequestPetOld = clone $userRequestPet;
        $userRequestPet->status = $this->status;
        if ($userRequestPet->validate() && $userRequestPet->save()) {
            $this->sendEmail($userRequestPetOld, $userRequestPet);
            return true;
        }
        $this->addErrors($userRequestPet->getErrors());
        return false;
    }

    private function sendEmail(UserRequestPet $requestOld, UserRequestPet $request): bool
    {
        if ($requestOld->status === UserRequestPet::STATUS_NEW && $request->status === UserRequestPet::STATUS_APPROVED) {
            return Yii::$app
                ->mailer
                ->compose(
                    'request/request-approved',
                    ['request' => $request]
                )
                ->setFrom(Yii::$app->params['emailFrom'])
                ->setTo($request->requestOwner->email)
                ->setBcc(Yii::$app->params['emailBcc'])
                ->setSubject('Request is approved')
                ->send();
        }
        if ($requestOld->status === UserRequestPet::STATUS_NEW && $request->status === UserRequestPet::STATUS_REJECTED) {
            return Yii::$app
                ->mailer
                ->compose(
                    'request/request-rejected',
                    ['request' => $request]
                )
                ->setFrom(Yii::$app->params['emailFrom'])
                ->setTo($request->requestOwner->email)
                ->setBcc(Yii::$app->params['emailBcc'])
                ->setSubject('Request is rejected')
                ->send();
        }
        return false;
    }
}
