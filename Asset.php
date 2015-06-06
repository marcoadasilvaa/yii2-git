<?php

namespace markmarco16\git;

use yii\web\AssetBundle;

class Asset extends AssetBundle
{
    public $sourcePath = '@vendor/markmarco16/yii2-git/assets';

    public $css = [
        'css/git.css',
    ];

    public $js = [];
    
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

}