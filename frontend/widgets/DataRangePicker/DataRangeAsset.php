<?
namespace frontend\widgets\DataRangePicker;

use yii\web\AssetBundle;

class DataRangeAsset extends AssetBundle
{
	public $sourcePath = '@frontend/widgets/DataRangePicker/assets';	
	public $css = [
    	'css/date-picker.css',
	];
	public $js =[
		// 'js/date-picker.js'
	];
	public $depends = ['yii\web\JqueryAsset'];

}