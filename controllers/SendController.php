<?php


namespace grozzzny\call_back\controllers;


use grozzzny\call_back\models\CallBack;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;
use yii\web\Response;

class SendController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['index'],
                        'roles' => ['@','?']
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;

        $model = new CallBack();

        if ($model->load($request->get()) && $model->save()) {
            return ['success' => true];
        }else{
            return ['error' => 'no save'];
        }

    }

}