<?php

namespace backend\controllers;

use common\models\Breed;
use common\models\Color;
use common\models\Files;
use common\models\LostPet;
use common\models\Pet;
use common\models\ResponseLostPet;
use common\models\Review;
use common\models\SiteUser;
use common\models\upload\LostPetImageSmallUploadForm;
use common\models\upload\PetImageSmallUploadForm;
use common\models\upload\ProfileSmallPreviewUploadForm;
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

        $siteUserCount = SiteUser::find()->select(['status', 'count(*) as count'])
            ->groupBy('status')->asArray()->indexBy('status')->all();
        $petsCount = Pet::find()->select(['status', 'count(*) as count'])
            ->groupBy('status')->asArray()->indexBy('status')->all();
        $petsRequests = UserRequestPet::find()->select(['status', 'count(*) as count'])
            ->groupBy('status')->asArray()->indexBy('status')->all();
        $lostPetsCount = LostPet::find()->select(['status', 'count(*) as count'])
            ->groupBy('status')->asArray()->indexBy('status')->all();
        $responsesCount = ResponseLostPet::find()->select(['status', 'count(*) as count'])
            ->groupBy('status')->asArray()->indexBy('status')->all();
        $breedsCount = Breed::find()->select(['count(*) as count'])->asArray()->one();
        $colorsCount = Color::find()->select(['count(*) as count'])->asArray()->one();
        $reviewsCount = Review::find()->select(['count(*) as count'])->asArray()->one();
        $lostPetsImages = Files::find()
            ->innerJoinWith(['lostPets1'])
            ->where(['dir' => [LostPetImageSmallUploadForm::DIR]])
            ->orderBy('id desc')->limit(8)->all();
        $petsImages = Files::find()
            ->innerJoinWith(['petSmallImages'])
            ->where(['dir' => [PetImageSmallUploadForm::DIR]])
            ->orderBy('id desc')->limit(8)->all();
        $profileImages = Files::find()
            ->innerJoinWith(['siteUsers0'])
            ->where(['dir' => [ProfileSmallPreviewUploadForm::DIR]])
            ->orderBy('id desc')->limit(8)->all();

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
                'reviewsCount',
                'lostPetsImages',
                'petsImages',
                'profileImages',
            )
        );
    }
}
