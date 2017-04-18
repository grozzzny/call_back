<?php
namespace grozzzny\call_back\assets;

class ModuleAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@grozzzny/call_back/media';

    public $css = [];

    public $js = [
        'js/admin_module.js'
    ];

    public $jsOptions = array(
        'position' => \yii\web\View::POS_HEAD
    );
}
