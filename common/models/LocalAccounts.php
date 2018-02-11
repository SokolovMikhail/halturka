<?
namespace common\models;

use Yii;


/**
 * Аккаунты, доступные на локальном сервере
 */  
class LocalAccounts{
	

	/**
	 * Обход баз данных и генерация статистики
	 */
	public static function wallkAndRun($obj, $method, $permit=false){
		$accounts = self::getAccountsList();
		foreach($accounts as $a){
			if(isset($a['cron']) && $a['cron']){
				if($permit && (!isset($a['permit']) || !is_array($a['permit']) || !in_array($permit, $a['permit']))){
					echo "--- ".$a['db'].": permit $permit disabled ---\n";
					continue;
				}
				echo "--- ".$a['db']." ---\n";
				echo "start ".date("h:i:s  d.m.Y")."\n";
				Yii::$app->db->close();
				Yii::$app->db->dsn  = 'mysql:host=localhost;dbname='.$a['db'];
				Yii::$app->db->open();
				
				$obj->$method();
				
				echo 'finish '.date("h:i:s  d.m.Y")."\n";
			}
			else{
				echo "--- ".$a['db'].": cron disabled ---\n";
			}
		}
    }
	
	
	/**
	 * Список аккаунтов
	 */
	public static function getAccountsList(){
		return require(__DIR__ . '/../../common/config/bases.php');
	}
	
	
	/**
	 * Информация об аккаунте
	 */
	public static function getCurrentAccountData(){
		$bases = require(__DIR__ . '/../../common/config/bases.php');
		$sdn = explode('.', $_SERVER['HTTP_HOST']);
		
		$result = [];
		$result['ip'] = $bases[$sdn[0]]['ip'];
		$result['device_port'] = $bases[$sdn[0]]['port'];
		$result['battery_port'] = isset($bases[$sdn[0]]['battery_port']) ? $bases[$sdn[0]]['battery_port'] : 8010;
		$result['account_name'] = $sdn[0];
		return $result;
	}	
	
}