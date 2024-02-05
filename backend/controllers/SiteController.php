<?php

namespace backend\controllers;

use common\models\Breed;
use common\models\Color;
use common\models\LostPet;
use common\models\Pet;
use common\models\ResponseLostPet;
use common\models\Review;
use common\models\SiteUser;
use common\models\UserRequestPet;
use Yii;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends BaseController
{
    public $freeAccessActions = ['login', 'logout', 'confirm-registration-email', 'index'];

    /**
     * @return string|Response
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/user-management/auth/login');
        }

        $siteUserCount = SiteUser::find()->select(['status','count(*) as count'])
            ->groupBy('status')->asArray()->indexBy('status')->all();
        $petsCount = Pet::find()->select(['status','count(*) as count'])
            ->groupBy('status')->asArray()->indexBy('status')->all();
        $petsRequests = UserRequestPet::find()->select(['status','count(*) as count'])
            ->groupBy('status')->asArray()->indexBy('status')->all();
        $lostPetsCount = LostPet::find()->select(['status','count(*) as count'])
            ->groupBy('status')->asArray()->indexBy('status')->all();
        $responsesCount = ResponseLostPet::find()->select(['status','count(*) as count'])
            ->groupBy('status')->asArray()->indexBy('status')->all();
        $breedsCount = Breed::find()->select(['count(*) as count'])->asArray()->one();
        $colorsCount = Color::find()->select(['count(*) as count'])->asArray()->one();
        $reviewsCount = Review::find()->select(['count(*) as count'])->asArray()->one();

        return $this->render(
            'index',
            compact(
                'petsCount',
                'lostPetsCount',
                'siteUserCount',
                'petsRequests',
                'responsesCount',
                'breedsCount',
                'colorsCount',
                'reviewsCount'
            )
        );
    }
}
