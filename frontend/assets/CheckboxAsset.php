<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class CheckboxAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
		'css/checkbox.css'
    ];
    public $js = [		
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
