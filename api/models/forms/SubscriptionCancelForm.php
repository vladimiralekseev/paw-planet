<?php

namespace api\models\forms;

use common\models\SiteUser;
use common\models\Stripe;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use yii\base\Model;

/**
 * @property int $user_id
 */
class SubscriptionCancelForm extends Model
{
    public $user_id;

    private $response;

    public function formName(): string
    {
        return '';
    }

    public function rules(): array
    {
        return [
            [
                ['user_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => SiteUser::class,
                'targetAttribute' => ['user_id' => 'id']
            ],
        ];
    }

    /**
     * @return bool
     */
    public function cancelSubscription(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        /** @var SiteUser $user */
        $user = SiteUser::find()->where(['id' => $this->user_id])->one();

        if (!$user->product_id) {
            $this->addError('stripe', 'You have not subscription');
            return false;
        }

        try {
            $stripe = new Stripe();
            if ($this->response = $stripe->cancelSubscription($user)) {
                return true;
            }
            $this->addError('stripe', 'Subscription is not found');

            return false;
        } catch (ApiErrorException $e) {
            $this->addError('stripe', $e->getMessage());
            return false;
        }
    }

    public function getResponse(): Session
    {
        return $this->response;
    }
}
