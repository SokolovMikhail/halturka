<?
namespace frontend\widgets\NavMenu;
use yii\web\AssetBundle;
class NavMenuAsset extends AssetBundle
{
	public $sourcePath = '@frontend/widgets/NavMenu/assets';	
	public $js = [
    	'js/scripts.js',
	];
	public $css = [
    	'css/styles.css',
	];
	public $depends = [
		'yii\web\YiiAsset',
	];
}