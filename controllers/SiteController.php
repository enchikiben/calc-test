<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;

/**
 * @SWG\Swagger(
 *     basePath="/",
 *     produces={"application/json"},
 *     @SWG\Info(version="1.0", title="Calculator API"),
 * )
 */
class SiteController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'docs' => [
                'class' => 'yii2mod\swagger\SwaggerUIRenderer',
                'restUrl' => Url::to(['site/json-schema']),
            ],
            'json-schema' => [
                'class' => 'yii2mod\swagger\OpenAPIRenderer',
                // Тhe list of directories that contains the swagger annotations.
                'scanDir' => [
                    Yii::getAlias('@app/controllers'),
                ],
            ],
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

}
