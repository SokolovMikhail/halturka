<?
namespace frontend\widgets\ActiveDiagram;
use yii\web\AssetBundle;
class ActiveDiagramAsset extends AssetBundle
{
	public $sourcePath = '@frontend/widgets/ActiveDiagram/assets';	
	public $js = [
    	'js/active-diagram.js',
	];
	public $depends = [
		'yii\web\YiiAsset',
	];
}