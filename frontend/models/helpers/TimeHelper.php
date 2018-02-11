<?
namespace frontend\models\helpers;

use Yii;
use yii\base\Object;


/**
 * Вспомогательные методы для работы с датой и временем
 */
class TimeHelper extends Object
{


	public static $timezoneList = [
		2 => [
			'id'	=> 2,
			'label'	=> 'Калиниград (UTC+2)', 
			'zone'	=> 'Europe/Kaliningrad',
		],
		3 => [
			'id'	=> 3,
			'label'	=> 'Москва (UTC+3)', 
			'zone'	=> 'Europe/Moscow',
		],
		4 => [
			'id'	=> 4,
			'label'	=> 'Самара (UTC+4)', 
			'zone'	=> 'Europe/Samara',
		],
		5 => [
			'id'	=> 5,
			'label'	=> 'Екатеринбург (UTC+5)', 
			'zone'	=> 'Asia/Yekaterinburg',
		],
		6 => [
			'id'	=> 6,
			'label'	=> 'Омск (UTC+6)', 
			'zone'	=> 'Asia/Omsk',
		],
		7 => [
			'id'	=> 7,
			'label'	=> 'Красноярск (UTC+7)', 
			'zone'	=> 'Asia/Krasnoyarsk',
		],
		8 => [
			'id'	=> 8,
			'label'	=> 'Иркутск (UTC+8)', 
			'zone'	=> 'Asia/Irkutsk',
		],
		9 => [
			'id'	=> 9,
			'label'	=> 'Якутск (UTC+9)', 
			'zone'	=> 'Asia/Yakutsk',
		],
		10 => [
			'id'	=> 10,
			'label'	=> 'Владивосток (UTC+10)', 
			'zone'	=> 'Asia/Vladivostok',
		],
		11 => [
			'id'	=> 11,
			'label'	=> 'Колыма (UTC+11)', 
			'zone'	=> 'Asia/Magadan',
		],
		12 => [
			'id'	=> 12,
			'label'	=> 'Камчатка (UTC+12)', 
			'zone'	=> 'Asia/Kamchatka',
		],
	];


	/**
	 * Возвращает дату понедельника заданного года
	 */
	public static function findMonday($dateTime){
		$firstDate = strtotime("1 january ".$dateTime->format('Y'));
		if(date("D", $firstDate)=="Mon"){
			$monday = $firstDate;
		}
		else{
			$monday = strtotime("next Monday", $firstDate)-604800;
		}
		return strtotime("+".($dateTime->format('W'))." week", $monday);
	}
	
	
	/**
	 * Возвращает дату воскресенья заданного года
	 */
	public static function findSunday($dateTime){
		return self::findMonday($dateTime)+604799;
	}
	
	
	/**
	 * Заливка для почасового лога
	 */
	public static function dayHoursFill($start){
		for($i=0;$i<24;$i++)
			$dayHoursFill[($start+$i)%24] = 0;
		return $dayHoursFill;
	}
	
	
	/**
	 * Валидация даты
	 */
	public static function validateDate($date, $format = 'd-m-Y'){
		$d = \DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}
	
	
	/**
	 * Валидация даты
	 */
	public static function secToHours($time, $format = 'number'){
		$result = round($time/3600, 1);
		return $result;
	}
	
	
	/**
	 * Представление интервала секунд времени в формате "часы" "разделитель" "минуты"
	 */
	public static function shortTimePeriodFormater($period, $delimiter=false) {
		if ($period <= 0)
			return '0';
		$h = (int)($period/3600); //часы
		$timenothour = $period - ($h * 3600);
		$m = (int)($timenothour/60); //минуты
		if($delimiter){
			if($m<10){
				$m = '0'.$m;
			}
			$s = $h.$delimiter.$m;
		}
		else{
			$s = $h.'ч. '.$m.'м.';
		}
		return $s;
	}
	
	
	/**
	 * Вывод даты с учетом Time Zone
	 * TO_DO: Deprecated
	 */
	public static function printWithTimeZone($date, $storage, $format='d-m-Y H:i', $timeZone=false){
		$date = new \DateTime($date);
		if($timeZone){
			$date->add(new \DateInterval('PT'. $timeZone .'H'));
		}
		elseif($storage){
			$date->add(new \DateInterval('PT'. Yii::$app->storagesData->getTimeZone($storage) .'H'));
		}
		return $date->format($format);
	}


	/**
	 * Возвращает объект DateTime с установкленой TimeZone
	 * TO_DO: Deprecated
	 */
	public static function createWithTimezone($date, $timeZone){
		$result = new \DateTime($date);
		$result->add(new \DateInterval('PT'.$timeZone.'H'));
		return $result;
	}


	/**
	 * Возвращает новый объект DateTime с локальной TimeZone
	 */
	public static function createLocalFromUtc($date, $timeZoneIndex){
		$result = self::createUtcDateTime($date);
		$result->setTimezone(new \DateTimeZone(self::$timezoneList[$timeZoneIndex]['zone']));
		return $result;
	}


	/**
	 * Возвращает объект DateTime, установленный в начало смен
	 */
	public static function setWorkDayStart($date, $dayStartHour, $dayStartMinute = 0){
		$date->setTime($dayStartHour, $dayStartMinute, 0);
		return $date;
	}


	/**
	 * Возвращает новый объект DateTime установленый в UTC
	 */
	public static function createUtcDateTime($date=false){
		if(is_object($date) && get_class($date)=='DateTime'){
			$date = $date->format('Y-m-d H:i:s');
		}
		$result = new \DateTime($date, new \DateTimeZone('UTC'));
		return $result;
	}
	
	
	/**
	 * Список месяцев
	 */
	public function getMonthsList(){
		return [
			1 => 'Январь',
			2 => 'Февраль',
			3 => 'Март',
			4 => 'Апрель',
			5 => 'Май',
			6 => 'Июнь',
			7 => 'Июль',
			8 => 'Август',
			9 => 'Сентябрь',
			10 => 'Октябрь',
			11 => 'Ноябрь',
			12 => 'Декабрь',
		];
	}

}