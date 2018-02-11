<?  
namespace console\models;

use Yii;
use frontend\models\LogCrude24h;
use frontend\models\Cards;
use yii\helpers\ArrayHelper;
use frontend\models\logs\AppLogs;

/**
 * Модель восстановления id операторов в логах
 */	
class LogOperatorIdRecovery{
		
	/**
	* Восстановление id операторов в часовом логе (HourLog)
	*/	
	public function recoverHourLogOperatorIds($from, $to){
		
		$from = new \DateTime($from);
		$to = new \DateTime($to);
		
		$cards = ArrayHelper::index(Cards::find()->asArray()->all(), 'id');
		
		$cardIds = array_keys($cards);
		
		foreach($cardIds as $key=>$id){
			$cardIds[$key] = '-'.$id;
		}
		
		$offset = 0;
		$appLogs = new AppLogs();
		$logModel = $appLogs->hour;
		
		do{
			$hourLog = $logModel::find()
				->where([
					'and',
					['>=', 'log_date', $from->format('Y-m-d H:i:s')],
					['<', 'log_date', $to->format('Y-m-d H:i:s')],
					['operator_id' => $cardIds],
				])
				->offset($offset * 100000)
				->limit(100000)
				->orderBy('log_date ASC')
				->all();
			
			foreach($hourLog as $log){
				$log->operator_id = $cards[mb_substr($log->operator_id, 1)]['operator_id'];
				$log->save();
			}
				
			$offset++;
		}while($hourLog);
		var_dump('Done');		
	}
}