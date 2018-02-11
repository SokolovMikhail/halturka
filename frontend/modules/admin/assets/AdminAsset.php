<?
namespace frontend\modules\admin\assets;

use yii\web\AssetBundle;

class AdminAsset extends AssetBundle
{
	public $sourcePath = '@frontend/modules/admin/assets';	
	public $css = [
    	'css/styles.css',
		'css/popover.css',
		// 'css/lightbox.css',
	];
	public $js =[
		'js/scripts.js',
		// 'js/lightbox.js',
		'js/jquery-cookie.js',
		'https://api-maps.yandex.ru/2.1/?lang=ru_RU',
		'js/snowfall.jquery.js',
	];
	public $depends = ['yii\web\JqueryAsset'];

}