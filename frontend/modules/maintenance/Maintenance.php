<?
namespace frontend\modules\maintenance;

use Yii;
use frontend\models\AppOptions;
use frontend\models\helpers\TimeHelper;

class Maintenance extends \yii\base\Module
{
    // public $controllerNamespace = 'app\modules\wms\controllers';

    public function init()
    {
        parent::init();
		
		
		if(isset($_SERVER['HTTP_HOST'])){
			$bases = require(__DIR__ . '/../../../common/config/bases.php');
			$sdn = explode('.', $_SERVER['HTTP_HOST']);			
			if(array_key_exists($sdn[0], $bases)){				
				if(in_array('superadmin', array_keys(Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId()))) || Yii::$app->user->isGuest){				
					return;
				}
				$option = AppOptions::find()->where(['option_name' => 'maintenance'])->one();
				if($option && $option->option_value){
					$now = TimeHelper::createUtcDateTime();//Сейчас
					$now->setTimezone(new \DateTimeZone('Europe/Moscow'));//Перевод к Московскому времени
					$maintenanceDate = new \DateTime($option->option_value, new \DateTimeZone('Europe/Moscow'));

					if($now < $maintenanceDate){
						$urlData = explode( '/', Yii::$app->urlManager->parseRequest(Yii::$app->request)[0]);
						
						
						if(isset($urlData[0]) && isset($urlData[0])){
							if(!($urlData[0] == 'site' && $urlData[1] == 'maintenance')){
								return Yii::$app->response->redirect(['site/maintenance']);
							}
						}else{
							return Yii::$app->response->redirect(['site/maintenance']);
						}
					}
				}
			}
		}
	}
	
	
	/**
	 * Доступ к модулю по ролям 
	 */
	public function getPermission(){
		return [];
	}
}
