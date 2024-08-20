<?php

namespace api\controllers;

use common\models\SiteUser;
use Yii;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

class AccessPremiumController extends AccessController
{
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow'         => true,
                    'matchCallback' => function ($rule, $action) {
                        /** @var SiteUser $user */
                        $user = Yii::$app->user->identity;
                        $user->refresh();
                        if ($user->product && $user->subscription_status === SiteUser::SUBSCRIBE_STATUS_ACTIVE) {
                            return true;
                        }
                        throw new ForbiddenHttpException(
                            'You have not access to this section. You should have Premium or Premium Plus subscription.',
                            self::ACCESS_CODE_SUBSCRIPTION_IS_ABSENT
                        );
                    }
                ],
            ],
        ];
        return $behaviors;
    }
}
