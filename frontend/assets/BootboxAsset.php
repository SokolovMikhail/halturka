<?php
namespace frontend\assets;

use Yii;
use yii\web\AssetBundle;

class BootboxAsset extends AssetBundle
{
    public $sourcePath = '@vendor/bower/bootbox';
    public $js = [
        'bootbox.js',
    ];
	
	public static function overrideSystemConfirm()
    {
        Yii::$app->view->registerJs('
            yii.confirm = function(message, ok, cancel) {
				bootbox.confirm({ 
					title: "Подтвердите действие",
					size: "small",
					message: message, 
					callback: function(result){
						if (result) { !ok || ok(); } else { !cancel || cancel(); }
					}
				});
            }
        ');
    }
}