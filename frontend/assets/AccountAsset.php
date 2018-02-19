<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class AccountAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap.css',
		'css/font-awesome.min.css',

		'css/fontello.css',
		'css/account.css',
		'css/bootstrap-select.css',
		'css/table.css',
    ];
    public $js = [	
		'js/moment.min.js',
		'js/account.js',
		'js/jquery-cookie.js',
		'js/bootstrap-select.js',
		'js/table.js',
		'js/scripts.js',		
    ];
    public $depends = [
        'yii\web\YiiAsset',
		'frontend\assets\BootboxAsset',
    ];
}
