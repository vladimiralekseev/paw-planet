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
    private function progressLog($user, $data): array
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

        return $this->progressLog(
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

        if (empty($request->bodyParams['data']['object']['customer'])) {
            throw new BadRequestHttpException('Stripe customer Id is not found');
        }

        /** @var SiteUser $user */
        $user = SiteUser::find()->where(['stripe_customer_id' => $request->bodyParams['data']['object']['id']])->one();
        if (!$user) {
            throw new BadRequestHttpException('User is not found');
        }

        if ($request->bodyParams['type'] === StripeLog::TYPE_CUSTOMER_SUBSCRIPTION_CREATED) {
            /** @var Product $prod */
            $prod = Product::find()->where(['stripe_product_id' => $request->bodyParams['plan']['product']]);

            if ($prod) {
                if ($user->product_id && $user->product_id !== $prod->id) {
                    // add cancel subscription
                }
                $user->product_id = $prod->id;
                $user->save(false);
            }
        }

        return $this->progressLog(
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
