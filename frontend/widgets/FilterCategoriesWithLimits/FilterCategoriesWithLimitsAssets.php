<?
namespace frontend\widgets\FilterCategoriesWithLimits;

use yii\web\AssetBundle;

class FilterCategoriesWithLimitsAssets extends AssetBundle
{
	public $sourcePath = '@frontend/widgets/FilterCategoriesWithLimits/assets';	
	public $js = [
    	'js/scripts.js',
	];
	public $depends = [
		'yii\web\YiiAsset',
	];
}