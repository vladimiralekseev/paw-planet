<?php

namespace api\controllers;

use common\models\Product;
use common\models\SiteUser;
use common\models\StripeLog;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Json;
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
     * @param $user
     * @param $data
     *
     * @return string[]
     * @throws BadRequestHttpException
     */
    private function processLog($user, $data): array
    {
        $stripeLog = new StripeLog(
            [
                'data'         => Json::encode($data),
                'site_user_id' => $user->id,
                'event'        => $data['type'],
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
            throw new BadRequestHttpException('Stripe customer Id is not found');
        }
        /** @var SiteUser $user */
        $user = SiteUser::find()->where(['stripe_customer_id' => $request->bodyParams['data']['object']['id']])->one();
        if (!$user) {
            throw new BadRequestHttpException('User is not found');
        }

        return $this->processLog(
            $user,
            $request->bodyParams
        );
    }

    /**
     * @return string[]
     * @throws BadRequestHttpException
     */
    public function actionCustomerSubscription(): array
    {
        $request = Yii::$app->request;
        $object = null;

        if (empty($request->bodyParams['data']['object'])) {
            throw new BadRequestHttpException('Stripe Object is not found');
        }

        $object = $request->bodyParams['data']['object'];

        if (empty($object['customer'])) {
            throw new BadRequestHttpException('Stripe customer Id is not found');
        }

        /** @var SiteUser $user */
        $user = SiteUser::find()->where(['stripe_customer_id' => $object['customer']])->one();
        if (!$user) {
            throw new BadRequestHttpException('User is not found');
        }

        /** @var Product $prod */
        $prod = Product::find()->where(['stripe_product_id' => $object['plan']['product']])->one();
        if (!$prod) {
            throw new BadRequestHttpException('Product is not found. Id: ' . $object['plan']['product']);
        }

        if ($request->bodyParams['type'] === StripeLog::TYPE_CUSTOMER_SUBSCRIPTION_CREATED) {
            $user->product_id = $prod->id;
            $user->subscription_status = SiteUser::SUBSCRIBE_STATUS_ACTIVE;
            if ($prod->trial_days) {
                $user->stripe_trial_is_used = 1;
            }
            $user->save(false);
        } elseif ($request->bodyParams['type'] === StripeLog::TYPE_CUSTOMER_SUBSCRIPTION_DELETED) {
            $user->product_id = null;
            $user->subscription_status = null;
            $user->save(false);
        } elseif ($request->bodyParams['type'] === StripeLog::TYPE_CUSTOMER_SUBSCRIPTION_UPDATED) {
            if (in_array(
                $object['status'],
                [StripeLog::SUBSCRIPTION_STATUS_ACTIVE, StripeLog::SUBSCRIPTION_STATUS_TRIALING],
                true
            )) {
                $user->product_id = $prod->id;
                $user->subscription_status = SiteUser::SUBSCRIBE_STATUS_ACTIVE;
                $user->save(false);
            } else if ($object['status'] === StripeLog::SUBSCRIPTION_STATUS_CANCELED) {
                $user->product_id = null;
                $user->subscription_status = null;
                $user->save(false);
            } else {
                $user->product_id = $prod->id;
                $user->subscription_status = SiteUser::SUBSCRIBE_STATUS_INACTIVE;
                $user->save(false);
            }
        }

        return $this->processLog(
            $user,
            $request->bodyParams
        );
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
