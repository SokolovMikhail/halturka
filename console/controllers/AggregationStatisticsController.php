<?php
/**
 * Контроллер агрегации статистики по расписанию
 */
namespace console\controllers;

use Yii;
use console\controllers\BaseConsoleController;
use frontend\models\LogCrude24h;
use console\models\SpeedHourLogMaker;
use console\models\LogOperatorIdRecovery;

class AggregationStatisticsController extends BaseConsoleController
{


	/**
	 * Генерация статистики в разрезе оператор/техника за каждый час со скоростью
	 * php -f D:\OpenServer\domains\tvz\yii aggregation-statistics/run-method makeSpeedHourLog
	 */
	public function makeSpeedHourLog()
	{
		$maker = new SpeedHourLogMaker;
		$maker->makeLog();
	}


	/**
	 * Перегенерация статистики СКОРОСТИ в разрезе оператор/техника за каждый час
	 * php -f PATH_TO_YII\yii aggregation-statistics/remake-speed-hour-log tvz_mpc "2017-06-01 00:00:00" "2017-11-12 23:59:59"
	 */	
	public function actionRemakeSpeedHourLog($db, $from, $to)
	{
		Yii::$app->db->close();
		Yii::$app->db->dsn  = 'mysql:host=localhost;dbname='.$db;
		Yii::$app->db->open();
		$report = new SpeedHourLogMaker;
		$report->remakeSpeedLog($from, $to);
	}
	
	
	/**
	 * Удаление сырого лога
	 * php -f PATH_TO_YII\yii aggregation-statistics/run-method clearLogCrude 
	 */
	public function clearLogCrude()
	{
		$to = new \DateTime(null, new \DateTimeZone('UTC'));
		$to->sub(new \DateInterval('PT26H'));
		LogCrude24h::deleteAll(['and',['<=','log_date',$to->format('Y-m-d H:i:s')], ['archive'=>1]]);
	}	

	
	/**
	 * Замена id карт на соответствующие id оператора в часовом логе (HourLog)
	 * php -f PATH_TO_YII\yii aggregation-statistics/recover-logs tvz_lm "2017-12-01 00:00:00" "2018-01-01 23:59:59"
	 */	
	public function actionRecoverLogs($db, $from, $to)
	{
		Yii::$app->db->close();
		Yii::$app->db->dsn  = 'mysql:host=localhost;dbname='.$db;
		Yii::$app->db->open();		
		$maker = new LogOperatorIdRecovery();
		$maker->recoverHourLogOperatorIds($from, $to);
	}		
}