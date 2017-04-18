<?php
namespace grozzzny\call_back\api;

use Yii;
use grozzzny\call_back\models\CallBack;
use yii\helpers\ArrayHelper;

class CallBackApi
{
    public static function form($view = '@grozzzny/call_back/views/_form', $params = [])
    {
        $model = new CallBack(['scenario' => CallBack::SCENARIO_PHONE]);
        if($post = Yii::$app->request->post()){
            $model->load($post);

            if($model->validate()){
                $model->save();
            }
        }

        $params = ArrayHelper::merge($params, ['model' => $model]);

        return Yii::$app->getView()->render($view, $params);
    }

}