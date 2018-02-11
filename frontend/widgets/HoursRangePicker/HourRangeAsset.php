<?
namespace frontend\widgets\HoursRangePicker;

use yii\web\AssetBundle;

class HourRangeAsset extends AssetBundle
{
	public $sourcePath = '@frontend/widgets/HoursRangePicker/assets';	
	public $css = [
    	'css/styles.css',
	];
	public $js =[
		'js/scripts.js'
	];
	public $depends = ['yii\web\JqueryAsset'];

}