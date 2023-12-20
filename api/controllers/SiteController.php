<?php

namespace api\controllers;

use OpenApi\Annotations\OpenApi;
use OpenApi\Generator;
use Yii;
use yii\web\Response;

/**
 * @OA\Info(
 *     title="Paw Palanet API",
 *     version="1.0"
 * )
 * @OA\Schemes(format="http")
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      in="header",
 *      name="Authorization",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 * ),
 */
class SiteController extends BaseController
{
    public function actionIndex(): string
    {
        Yii::$app->response->format = Response::FORMAT_HTML;
        return $this->render('index');
    }

    public function actionSwaggerJson(): ?OpenApi
    {
        return Generator::scan(['./../']);
    }
}
