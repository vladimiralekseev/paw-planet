<?php

namespace api\controllers;

use yii\filters\VerbFilter;

class StripeController extends BaseController
{
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class'   => VerbFilter::class,
                    'actions' => [
                        'customer'              => ['post'],
                        'customer-subscription' => ['post'],
                        'billing-portal'        => ['post'],
                    ],
                ],
            ]
        );
    }

    public function actionCustomer(): array
    {
        return ['result' => 'ok'];
    }

    public function actionCustomerSubscription(): array
    {
        return ['result' => 'ok'];
    }

    public function actionBillingPortal(): array
    {
        return ['result' => 'ok'];
    }

    public function actionSubscriptionSchedule(): array
    {
        return ['result' => 'ok'];
    }
}
