<?php
namespace console\controllers;

use Yii;
use console\controllers\BaseConsoleController;
use frontend\modules\sportmasterwms\models\DataSinchronize;


/**
 * Внесение изменений в БД аккаунтов
 */
class DataManageController extends BaseConsoleController
{
	
	/**
	 * Применение миграций ко всем аккаунтам сервера
	 * php -f PATH_TO_YII\yii data-manage/run-method applyMigrations migrate
	 */
	public function applyMigrations() {
        $migration = new \yii\console\controllers\MigrateController('migrate', Yii::$app);
        $migration->runAction('up', ['migrationPath' => '@console/migrations/', 'interactive' => false]);
	}
	
	
	/**
	 * Выгрузка данных из wms Спортмастер
	 * TO_DO: перенести в модуль
	 */
	public function actionWmsSportmaster() {
		$accounts = require(__DIR__ . '/../../common/config/bases.php');
		echo "This is CommonDataManageController/actionWmsSportmaster \n";
		foreach($accounts as $a){
			if(isset($a['wms']) && ($a['wms']=='wms-sportmaster')){
				echo $a['db'].'--- start at '.date("h:i:s  d.m.Y")."\n";
				Yii::$app->db->close();
				Yii::$app->db->dsn  = 'mysql:host=localhost;dbname='.$a['db'];
				Yii::$app->db->open();
				$model = new DataSinchronize;
				$model->sinchronize();
				echo '--- reported at '.date("h:i:s  d.m.Y")."\n";
			}
		}
    }
}