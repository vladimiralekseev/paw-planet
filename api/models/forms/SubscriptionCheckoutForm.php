<?php

namespace api\models\forms;

use common\models\Product;
use common\models\SiteUser;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use yii\base\Model;

/**
 * @property int $product_id
 * @property int $user_id
 */
class SubscriptionCheckoutForm extends Model
{
    public $product_id;
    public $user_id;

    private $response;

    public function formName(): string
    {
        return '';
    }

    public function rules(): array
    {
        return [
            [['product_id'], 'required'],
            [
                ['product_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => Product::class,
                'targetAttribute' => ['product_id' => 'id']
            ],
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
    public function generate(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        /** @var SiteUser $user */
        $user = SiteUser::find()->where(['id' => $this->user_id])->one();
        /** @var Product $product */
        $product = Product::find()->where(['id' => $this->product_id])->one();

        if ($user->product_id) {
            $this->addError('stripe', 'You should cancel the current subscription');
            return false;
        }

        try {
            $user->stripeUpdate();
            $user->refresh();
        } catch (ApiErrorException $e) {
            $this->addError('stripe', $e->getMessage());
            return false;
        }

        try {
            $this->response = $user->generateCheckoutSessions($product);
            return true;
        } catch (ApiErrorException $e) {
            $this->addError('stripe', $e->getMessage());
            return false;
        }
    }

    /**
     * @return Session|null
     */
    public function getResponse(): ?Session
    {
        return $this->response;
    }
}
