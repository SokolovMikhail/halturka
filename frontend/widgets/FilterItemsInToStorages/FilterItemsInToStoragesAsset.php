<?
namespace frontend\widgets\FilterItemsInToStorages;

use yii\web\AssetBundle;

class FilterItemsInToStoragesAsset extends AssetBundle
{
	public $sourcePath = '@frontend/widgets/FilterItemsInToStorages/assets';	
	public $css = [
    	'css/styles.css',
	];
	public $js =[
		'js/scripts.js'
	];
	public $depends = ['yii\web\JqueryAsset'];

}