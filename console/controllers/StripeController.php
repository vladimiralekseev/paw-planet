<?php

namespace console\controllers;

use Stripe\StripeClient;
use yii\console\Controller;

class StripeController extends Controller
{

    public function actionTest(): void
    {
        $stripe = new StripeClient(
            'sk_test_51PIVraLCG0UE78V4nmVYAwlNAfXr0brQSMmchsd8Rd4yhVHr5xIXGUCkxf3xd3tlnSDwZAHahnonoav3Xz8V27ZI007nhdDNlj'
        );
//        $customer = $stripe->customers->create(
//            [
//                'description' => 'example customer 2',
//                'email' => 'email2@example.com',
//                'payment_method' => 'pm_card_visa',
//            ]
//        );
//        var_dump($customer);

        $session = $stripe->billingPortal->sessions->create(
            [
                'customer'   => 'cus_QBQDcBuhkQoJDX',
                'return_url' => 'https://paw-planet.gointeractive.com.ua/stripe/payment-result',
            ]
        );
        var_dump($session);


//        $res = $stripe->subscriptions->search(
//            [
//                'query' => 'status:\'succeeded\'',
//            ]
//        );
//        var_dump($res);
    }
}
