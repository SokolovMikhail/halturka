<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class AccountAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap.css',
        'css/non-responsive.css',
		'css/bootstrap-slider.css',
		'css/font-awesome.min.css',
		'css/treeview.css', // use?
		'css/daterangepicker.css',
		'css/fontello.css',
		'css/account.css',
		'css/bootstrap-select.css',
		'css/lightbox.css',
		'css/help.css',
		'css/table.css',
    ];
    public $js = [
		'js/bootstrap.js',
		'js/bootstrap-slider.js',
		'js/treeview.js', // use?
		'js/moment.min.js',
		'js/daterangepicker.js',
		'js/account.js',
		'js/jquery-cookie.js',
		'js/bootstrap-formhelpers-phone.js',
		'js/bootstrap-select.js',
		'js/lightbox.js',
		'js/help.js',
		'js/Chart.bundle.js',
		'js/table.js',
		'js/scripts.js',		
    ];
    public $depends = [
        'yii\web\YiiAsset',
		'frontend\assets\BootboxAsset',
    ];
}
