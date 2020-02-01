<?php

namespace app\controllers;

use Yii;
use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use app\components\Calculator;

class CalculatorController extends Controller
{
    /**
     * @SWG\Post(path="/calculator",
     *      tags={"Calculator"},
     *      summary="Carries out operations of addition of multiplication and subtraction.",
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="JSON Payload",
     *          required=true,
     *          type="json",
     *          format="application/json",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="example", type="string", example="1+2"),
     *          )
     *
     *      ),
     *      @SWG\Response(
     *          response = 200,
     *          description = "Calculation result",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="result", type="string", example="4"),
     *          )
     *      ),
     *      @SWG\Response(
     *          response = 400,
     *          description = "Bad Request",
     *          @SWG\Schema(
     *              type="object",
     *              format="application/json",
     *              @SWG\Property(property="name", type="string", example="Bad Request"),
     *              @SWG\Property(property="message", type="string", example="Bad Request"),
     *              @SWG\Property(property="code", type="number", example="0"),
     *              @SWG\Property(property="status", type="number", example="400"),
     *              @SWG\Property(property="type", type="string", example=""),
     *          )
     *      ),
     * )
     */
    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                    'application/xml' => Response::FORMAT_XML,
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $data = Yii::$app->request->bodyParams;

        if (isset($data['example'])) {
            $calculator = new Calculator();
            $calculator->example = $data['example'];

            if ($calculator->validate()) {
                return [
                    'result' => $calculator->calculate()
                ];
            }
        }

        throw new BadRequestHttpException("bad request");
    }

}
