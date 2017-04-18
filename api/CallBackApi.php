<?php
namespace grozzzny\call_back\api;

use Yii;
use grozzzny\call_back\models\CallBack;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class CallBackApi
{
    public static function form($view = '@grozzzny/call_back/views/_form', $params = [])
    {
        $model = new CallBack(['scenario' => CallBack::SCENARIO_PHONE]);

        $params = ArrayHelper::merge($params, ['model' => $model]);

        return Yii::$app->getView()->render($view, $params);
    }

}