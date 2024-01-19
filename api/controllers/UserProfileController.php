<?php

namespace api\controllers;

use api\models\forms\UserProfileForm;
use common\models\SiteUser;
use common\models\SiteUserPublic;
use common\models\upload\ProfilePreviewUploadForm;
use common\models\upload\ProfileSmallPreviewUploadForm;
use common\models\UserRequestPet;
use Throwable;
use Yii;
use yii\db\StaleObjectException;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;

class UserProfileController extends AccessController
{
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class'   => VerbFilter::class,
                    'actions' => [
                        'index'         => ['get'],
                        'detail'        => ['get'],
                        'update'        => ['post'],
                        'avatar'        => ['post'],
                        'avatar-delete' => ['delete'],
                    ],
                ],
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/user-profile/",
     *     security={{"bearerAuth":{}}},
     *     tags={"User"},
     *     @OA\SecurityScheme(
     *          securityScheme="bearerAuth",
     *          in="header",
     *          name="bearerAuth",
     *          type="http",
     *          scheme="bearer",
     *          bearerFormat="JWT",
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="The user data",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                     "id": 11,
     *                     "last_name": "lastname",
     *                     "first_name": "firstname",
     *                     "phone_number": null,
     *                     "about": null,
     *                     "email": "emaidddddl@email.com",
     *                     "my_location": null,
     *                     "latitude": "50.4450105000000",
     *                     "longitude": "30.4188569000000",
     *                     "country": null,
     *                     "state": null,
     *                     "city": null,
     *                     "address": null,
     *                     "whats_app": null,
     *                     "facebook": null,
     *                     "status": 10,
     *                     "status_name": "Active",
     *                     "updated_at": "2023-12-20 14:42:06",
     *                     "created_at": "2023-12-20 14:42:06"
     *                     }
     *                 )
     *             )
     *         }
     *     )
     * )
     */
    public function actionIndex(): IdentityInterface
    {
        return Yii::$app->user->identity;
    }

    /**
     * @OA\Post(
     *     path="/user-profile/update/",
     *     security={{"bearerAuth":{}}},
     *     tags={"User"},
     *     @OA\SecurityScheme(
     *          securityScheme="bearerAuth",
     *          in="header",
     *          name="bearerAuth",
     *          type="http",
     *          scheme="bearer",
     *          bearerFormat="JWT",
     *      ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"first_name", "email"},
     *                 @OA\Property(
     *                     property="last_name",
     *                     type="string",
     *                     maxLength=128,
     *                 ),
     *                 @OA\Property(
     *                     property="first_name",
     *                     type="string",
     *                     maxLength=128,
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     maxLength=255,
     *                 ),
     *                 @OA\Property(
     *                     property="phone_number",
     *                     type="string",
     *                     maxLength=64,
     *                 ),
     *                 @OA\Property(
     *                     property="whats_app",
     *                     type="string",
     *                     maxLength=256,
     *                 ),
     *                 @OA\Property(
     *                     property="facebook",
     *                     type="string",
     *                     maxLength=256,
     *                 ),
     *                 @OA\Property(
     *                     property="latitude",
     *                     type="number",
     *                 ),
     *                 @OA\Property(
     *                     property="longitude",
     *                     type="number",
     *                 ),
     *                 @OA\Property(
     *                     property="country",
     *                     type="string",
     *                     maxLength=64,
     *                 ),
     *                 @OA\Property(
     *                     property="state",
     *                     type="string",
     *                     maxLength=64,
     *                 ),
     *                 @OA\Property(
     *                     property="city",
     *                     type="string",
     *                     maxLength=64,
     *                 ),
     *                 @OA\Property(
     *                     property="address",
     *                     type="string",
     *                     maxLength=128,
     *                 ),
     *                 example={
     *                      "last_name": "lastname",
     *                      "first_name": "firstname",
     *                      "email": "email_x@gmail.com",
     *                      "phone_number": "55555555",
     *                      "about": "text about",
     *                      "my_location": "text location",
     *                      "latitude": "50.4450105000000",
     *                      "longitude": "30.4188569000000",
     *                      "country": null,
     *                      "state": null,
     *                      "city": null,
     *                      "address": null,
     *                      "whats_app": "http://",
     *                      "facebook": "http://"
     *                  }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="The data",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="name",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="status",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="code",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string"
     *                     ),
     *                     example={
     *                         "name": "Success",
     *                         "status": 200,
     *                         "code": 0,
     *                         "message": "Your profile is updated!"
     *                     }
     *                 )
     *             )
     *         }
     *     )
     * )
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionUpdate(): array
    {
        $userForm = new UserProfileForm();
        if ($userForm->load(Yii::$app->request->post()) && $userForm->save()) {
            return $this->successResponse(
                'Your profile is updated!'
            );
        }
        if ($errors = $userForm->getErrorSummary(true)) {
            throw new BadRequestHttpException(array_shift($errors));
        }

        throw new BadRequestHttpException('Undefined error');
    }

    /**
     * @OA\Post(
     *     path="/user-profile/avatar/",
     *     security={{"bearerAuth":{}}},
     *     tags={"User"},
     *     @OA\SecurityScheme(
     *          securityScheme="bearerAuth",
     *          in="header",
     *          name="bearerAuth",
     *          type="http",
     *          scheme="bearer",
     *          bearerFormat="JWT",
     *      ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     description="file to upload",
     *                     property="ProfilePreviewUploadForm[file]",
     *                     type="string",
     *                     format="binary",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="The data",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="name",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="status",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="code",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string"
     *                     ),
     *                     example={
     *                         "name": "Success",
     *                         "status": 200,
     *                         "code": 0,
     *                         "message": "Image is updated!"
     *                     }
     *                 )
     *             )
     *         }
     *     )
     * )
     * @return array
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionAvatar(): array
    {
        /** @var SiteUser $user */
        $user = Yii::$app->user->identity;
        $profilePreviewUploadForm = new ProfilePreviewUploadForm();
        $profileSmallPreviewUploadForm = new ProfileSmallPreviewUploadForm();

        $profilePreviewUploadForm->loadInstance();
        if ($profilePreviewUploadForm->validate() && $profilePreviewUploadForm->upload()) {
            $profileSmallPreviewUploadForm->file = $profilePreviewUploadForm->file;
            $profileSmallPreviewUploadForm->upload();
            if ($user->img_id) {
                $user->img->delete();
            }
            if ($user->small_img_id) {
                $user->smallImg->delete();
            }
            $user->img_id = $profilePreviewUploadForm->id;
            $user->small_img_id = $profileSmallPreviewUploadForm->id;
            $user->save();
            return $this->successResponse(
                'Image is updated!'
            );
        }
        if ($errors = $profilePreviewUploadForm->getErrorSummary(true)) {
            throw new BadRequestHttpException(array_shift($errors));
        }

        throw new BadRequestHttpException('Undefined error');
    }

    /**
     * @OA\Delete(
     *     path="/user-profile/avatar-delete/",
     *     security={{"bearerAuth":{}}},
     *     tags={"User"},
     *     @OA\SecurityScheme(
     *          securityScheme="bearerAuth",
     *          in="header",
     *          name="bearerAuth",
     *          type="http",
     *          scheme="bearer",
     *          bearerFormat="JWT",
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="The data",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="name",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="status",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="code",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string"
     *                     ),
     *                     example={
     *                         "name": "Success",
     *                         "status": 200,
     *                         "code": 0,
     *                         "message": "Image is updated!"
     *                     }
     *                 )
     *             )
     *         }
     *     )
     * )
     * @return array
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionAvatarDelete(): array
    {
        /** @var SiteUser $user */
        $user = Yii::$app->user->identity;
        if ($user->img_id) {
            $user->img->delete();
        }
        if ($user->small_img_id) {
            $user->smallImg->delete();
        }
        return $this->successResponse(
            'Image is deleted!'
        );
    }

    /**
     * User profile detail by id
     *
     * @OA\Get(
     *     path="/user-profile/{id}/",
     *     security={{"bearerAuth":{}}},
     *     tags={"User"},
     *     @OA\SecurityScheme(
     *          securityScheme="bearerAuth",
     *          in="header",
     *          name="bearerAuth",
     *          type="http",
     *          scheme="bearer",
     *          bearerFormat="JWT",
     *     ),
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *          ),
     *          style="form"
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="The user data",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                     "id": 11,
     *                     "last_name": "lastname",
     *                     "first_name": "firstname",
     *                     "about": null,
     *                     "country": null,
     *                     "state": null,
     *                     "city": null,
     *                     "status": 10,
     *                     "status_name": "Active",
     *                     "updated_at": "2023-12-20 14:42:06",
     *                     "created_at": "2023-12-20 14:42:06",
     *                     "security_fields": {
     *                         "phone_number": "0970000000",
     *                         "email": "email@email.com",
     *                         "my_location": "text",
     *                         "latitude": "50.4450105000000",
     *                         "longitude": "30.4188569000000",
     *                         "address": "text",
     *                         "whats_app": "text",
     *                         "facebook": "text",
     *                     }
     *                     }
     *                 )
     *             )
     *         }
     *     )
     * )
     * @throws NotFoundHttpException
     */
    public function actionDetail($id)
    {
        /** @var SiteUserPublic $user */
        $user = SiteUserPublic::find()->where(['id' => $id])->one();
        if (!$user) {
            throw new NotFoundHttpException('User not found.');
        }

        return $user;
    }
}
