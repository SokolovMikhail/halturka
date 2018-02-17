<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class BootstrapJsAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [	
		'js/bootstrap.js',		
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
