<?php

namespace api\controllers;

use common\models\SiteUser;
use common\models\StripeLog;
use Yii;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;

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

    /**
     * @param $event
     * @param $user_id
     * @param $data
     *
     * @return string[]
     * @throws BadRequestHttpException
     */
    private function progressLog($event, $user_id, $data): array
    {
        $stripeLog = new StripeLog(
            [
                'data'         => $data,
                'site_user_id' => $user_id,
                'event'        => $event,
            ]
        );
        if ($stripeLog->save()) {
            return ['result' => 'ok'];
        }
        if ($errors = $stripeLog->getErrorSummary(true)) {
            throw new BadRequestHttpException(array_shift($errors));
        }

        throw new BadRequestHttpException('Undefined error');
    }

    /**
     * @return string[]
     * @throws BadRequestHttpException
     */
    public function actionCustomer(): array
    {
        $request = Yii::$app->request;

        if (empty($request->bodyParams['data']['object']['id'])) {
            throw new BadRequestHttpException('Stripe user Id is not found');
        }
        /** @var SiteUser $user */
        $user = SiteUser::find()->where(['stripe_customer_id' => $request->bodyParams['data']['object']['id']])->one();
        if (!$user) {
            throw new BadRequestHttpException('User is not found');
        }

        return $this->progressLog(
            $request->bodyParams['id'],
            $user->id,
            $request->bodyParams
        );
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
