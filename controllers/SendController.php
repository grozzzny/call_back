<?php


namespace grozzzny\call_back\controllers;


use yii\filters\AccessControl;
use yii\web\Controller;

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
        echo 'ok';



    }

}