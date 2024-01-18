<?php

namespace console\controllers;

use common\models\SiteUserToken;
use DateInterval;
use DateTime;
use Throwable;
use yii\console\Controller;
use yii\db\StaleObjectException;

class UserController extends Controller
{
    /**
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDeleteExpiredToken(): void
    {
        $tokens = SiteUserToken::find()
            ->where(['<', 'expired_at', (new DateTime())->sub(new DateInterval('P1D'))->format('Y-m-d H:i:s')])
            ->all();
        foreach ($tokens as $token) {
            $token->delete();
        }
    }
}
