<?php

namespace common\models;

use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;
use Stripe\Price;
use Stripe\StripeClient;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;

class Stripe extends Model
{
    private StripeClient $stripeClient;

    /**
     * @throws InvalidConfigException
     */
    public function init(): void
    {
        parent::init();

        if (!empty(Yii::$app->params['stripe']['key'])) {
            $this->stripeClient = new StripeClient(Yii::$app->params['stripe']['key']);
        } else {
            throw new InvalidConfigException('Stripe key is not set');
        }
    }

    private static function customerData(SiteUser $user): array
    {
        return [
            'name'    => $user->getFullName(),
            'email'   => $user->email,
            'phone'   => $user->phone_number,
            'address' => [
                'city'        => $user->city,
                'state'       => $user->state,
                'line1'       => $user->address,
            ],
        ];
    }

    /**
     * @param SiteUser $user
     *
     * @return Customer
     * @throws ApiErrorException
     */
    public function saveCustomer(SiteUser $user): Customer
    {
        $customer = null;
        if ($user->stripe_customer_id) {
            $customer = $this->stripeClient->customers->update(
                $user->stripe_customer_id,
                self::customerData($user)
            );
        } else {
            $customer = $this->stripeClient->customers->create(
                self::customerData($user)
            );
            if ($customer) {
                $user->stripe_customer_id = $customer['id'];
                $user->save(false);
            }
        }
        return $customer;
    }

    /**
     * @param SiteUser $user
     * @param Product  $product
     *
     * @return Session
     * @throws ApiErrorException
     */
    public function generateCheckoutSessions(SiteUser $user, Product $product): Session
    {
        $stripeProduct = $this->getProduct($product->stripe_product_id);

        return $this->stripeClient->checkout->sessions->create([
                'customer'   => $user->stripe_customer_id,
                'success_url' => 'https://example.com/success.html?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'  => 'https://example.com/canceled.html',
                'mode'        => 'subscription',
                'line_items'  => [
                    [
                        'price'    => $stripeProduct['default_price'],
                        // For metered billing, do not pass quantity
                        'quantity' => 1,
                    ]
                ],
            ]
        );
    }

    /**
     * @param $id
     *
     * @return \Stripe\Product
     * @throws ApiErrorException
     */
    public function getProduct($id): \Stripe\Product
    {
        return $this->stripeClient->products->retrieve($id, []);
    }

    /**
     * @param $id
     *
     * @return Price
     * @throws ApiErrorException
     */
    public function getPrice($id): Price
    {
        return $this->stripeClient->prices->retrieve($id, []);
    }
}
