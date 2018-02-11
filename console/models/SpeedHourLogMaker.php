<?
/**
 * Формирует почасовой лог и пишет в базу
 */  
namespace console\models;

use Yii;
use frontend\models\LogCrude24h;
use frontend\models\LogCrude;
use frontend\models\Devices;
use frontend\models\HourLog;
use frontend\models\Report;
use frontend\models\logs\SpeedHourLog;


class SpeedHourLogMaker{
	
	private $hourLog;
	private $archiveId;
	private $rewriteWhereCause;
	
	private $speedLog;

	
	/**
	 * Формирует почасовой лог вида оператор/техника и пишет в базу
	 */
	public function makeLog(){
		$devices = (new \yii\db\Query())
			->select(['id', 'speed_sensor'])
			->from(Devices::tableName())
			->all();
		if($devices){
			foreach($devices as $d){
				$logs = (new \yii\db\Query())
					->select([
						'id',
						'device_id',
						'operator_id',
						'log_date',
						'active_time',
						'work_time',
						'service',
						'card_id',
						'idle',
						'ign_time',
						'eng_time',
						'av_speed'
					])
					->where(['and', ['archive'=>0], ['device_id'=>$d['id']]])
					->from(LogCrude24h::tableName())
					->limit(8640)
					->all();
				echo "device_id=$d[id] logs_count=".count($logs)."\n";
				if($logs){
					
					//Часовой лог
					$this->prepareDeviceHourLog($logs);
					$this->findRowsForRewriteHourLog();
					
					//Часовой лог скорости
					if($d['speed_sensor']){
						$this->prepareSpeedHourLog($logs);
						$this->calculateSpeedLogValues($d['id']);
						$this->findRowsForRewriteSpeedLog();					
					}
					
					//Запись логов
					$this->writeLog($d['id']);
				}
			}
		}
	}
	

	/**
	 * Подготовка часового лога скорости
	 */		
	public function prepareSpeedHourLog($logs, $serchRewrite=true){
		$this->speedLog = [];
		foreach($logs as $log){
			$currentTime = new \DateTime($log['log_date']);
			$time = $currentTime->format('Y-m-d H:59:59');
			$currentTime->setTime(($currentTime->format('H')+0),0,0);
			$difStep = strtotime($log['log_date']) - $currentTime->getTimestamp();
			$step = (int)($difStep/90)+($difStep%90 ? 1 : 0);
			
			if($log['operator_id']){
				$operator = $log['operator_id'];	// если id оператора отличен от 0 то записываем его
			}
			elseif($log['card_id']){
				$operator = $log['card_id']*(-1);	// в противном случае записываем id карты со знаком -, это позволит отличить карту от оператора и при добавлении карты переработать статистику
			}
			else{
				$operator = 0;						// режим работы без контроля доступа
			}
			
			if(!isset($this->speedLog[$time][$operator]))
			{
				// Если метод запущен в режиме дополнения существующих данных,
				// формируем условие, какие строки в HourLog нужно переписать
				if($serchRewrite){
					$this->rewriteWhereCause[] = ['and', ['log_date' => $time], ['device_id' => $log['device_id']], ['operator_id' => $operator]];
				}
				// инициализируем id нулем, далее, если данные за этот час уже есть в HourLog,
				// элементу будет присвоен id существующей строки, данные будут просуммированы, а строка перезаписана
				$this->speedLog[$time][$operator]['id'] = 0;
				$this->speedLog[$time][$operator]['speed_items'] = [];
				
				$this->speedLog[$time][$operator]['device_id'] = $log['device_id'];
				$this->speedLog[$time][$operator]['av_speed'] = 0;
				$this->speedLog[$time][$operator]['max_speed'] = 0;
				$this->speedLog[$time][$operator]['distance'] = 0;
				$this->speedLog[$time][$operator]['ignition'] = 0;
				$this->speedLog[$time][$operator]['speed_log'] = [];
				$this->speedLog[$time][$operator]['distribution'] = [];
			}
		
			$this->speedLog[$time][$operator]['ignition'] += $log['ign_time'];
		
			if(!$log['service'] && $log['active_time'] && $log['av_speed']){
				$avSpeed = ($log['av_speed'])*(10000/$log['active_time']);//Средняя скорость в движении
				$this->speedLog[$time][$operator]['speed_items'][] = [
						'av_speed'	=> $avSpeed,
						'active'	=> $log['active_time'],
				];
				
				if(!isset($this->speedLog[$time][$operator]['speed_log'][$step])){
					$this->speedLog[$time][$operator]['speed_log'][$step] = [];
				}
				
				$this->speedLog[$time][$operator]['speed_log'][$step][] = [
						'av_speed'	=> $avSpeed,
						'active'	=> $log['active_time'],
				];				
			}
						
		}
	}


	/**
	 * Калькуляция значений часового лога скорости
	 */	
	public function calculateSpeedLogValues($id)
	{
		foreach($this->speedLog as $timeId=>$time){
			foreach($time as $operatorId=>$operator){
				$distance = 0;
				$activeTime = 0;
				$maxSpeed = 0;
				$distribution = [];
				foreach($operator['speed_items'] as $item){
					$currentSpeed = 0;
					if(($item['av_speed']/10) > 0 && ($item['av_speed']/10) < 1){
						$currentSpeed = 1;
					}else{
						$currentSpeed = round($item['av_speed']/10);
					}
					
					if(!isset($distribution[$currentSpeed])){
						$distribution[$currentSpeed] = 0;
					}
					$distribution[$currentSpeed] += $item['active'];
					
					if($item['av_speed'] > $maxSpeed && $item['active'] > 1000){//TODO: ограничение в секундах для отсечения невалидных данных
						$maxSpeed = $item['av_speed'];
					}
					$activeTime += $item['active']/1000;//Время в секундах
					$distance += ($item['av_speed']/10) * ($item['active']/3600);//Дистанция в метрах					
				}
				foreach($operator['speed_log'] as $intervalId=>$interval){
					$intervalAv = 0;
					$intervalDistance = 0;
					$intervalTime = 0;
					foreach($interval as $item){
						$intervalTime += $item['active']/1000;//Время активности за интервал в секундах
						$intervalDistance += ($item['av_speed']/10) * ($item['active']/3600);//Дистанция в метрах
					}
					$intervalAv = $intervalTime ? ($intervalDistance/1000)/($intervalTime/3600) : 0;
					$this->speedLog[$timeId][$operatorId]['speed_log'][$intervalId] = [
						0 => round($intervalTime),
						1 => round($intervalAv * 10)
					];									
				}				
				$avSpeed = $activeTime ? ($distance/1000)/($activeTime/3600) : 0;
				$this->speedLog[$timeId][$operatorId]['av_speed'] = $avSpeed;
				$this->speedLog[$timeId][$operatorId]['max_speed'] = $maxSpeed/10;
				$this->speedLog[$timeId][$operatorId]['distance'] = $distance;
				$this->speedLog[$timeId][$operatorId]['distribution'] = $distribution;
				$this->speedLog[$timeId][$operatorId]['speed_items'] = [];				
			}
		}		
	}

	
	/**
	 * Поиск и подготовка строк для перезаписи Часового Лога
	 */
	private function findRowsForRewriteSpeedLog(){
		if($this->rewriteWhereCause){			
			$logs = SpeedHourLog::find()->where($this->rewriteWhereCause)->asArray()->all();
			if($logs){
				foreach($logs as $log){
					if(isset($this->speedLog[$log['log_date']][$log['operator_id']])){
						$this->speedLog[$log['log_date']][$log['operator_id']]['id'] = $log['id'];
						$this->speedLog[$log['log_date']][$log['operator_id']]['max_speed'] = max(
							$this->speedLog[$log['log_date']][$log['operator_id']]['max_speed'],
							$log['max_speed']/10
						);//Максимальное значение скорости
						$this->speedLog[$log['log_date']][$log['operator_id']]['distance'] += $log['distance'];//Суммарное расстояние можно сложить
						
						//Пересчет распределния скорости за час
						$distributionLog = unserialize($log['distribution']);
						foreach($distributionLog as $step=>$val){
							if(isset($this->speedLog[$log['log_date']][$log['operator_id']]['distribution'][$step])){
								$this->speedLog[$log['log_date']][$log['operator_id']]['distribution'][$step] += $val;
							}
							else{
								$this->speedLog[$log['log_date']][$log['operator_id']]['distribution'][$step] = $val;
							}
						}
						
						//Пересчет средней скорости
						$totalDistance = 0;
						$totalTime = 0;
						foreach($this->speedLog[$log['log_date']][$log['operator_id']]['distribution'] as $speed=>$time){
							$totalTime += $time/1000;//Время в секундах
							$distanceInPeriod = (($time/1000)/3600) * $speed;//Км за период
							$totalDistance += $distanceInPeriod;
						}							
						$this->speedLog[$log['log_date']][$log['operator_id']]['av_speed'] = $totalTime ? $totalDistance/($totalTime/3600) : 0;//Км\ч
						
						//Пересчет лога скорости за полутораминутные интервалы
						$speedLog = unserialize($log['speed_log']);
						foreach($speedLog as $step=>$val){
							if(isset($this->speedLog[$log['log_date']][$log['operator_id']]['speed_log'][$step])){
								//Старые показатели
								$oldTime = $this->speedLog[$log['log_date']][$log['operator_id']]['speed_log'][$step][0];
								$oldSpeed = $this->speedLog[$log['log_date']][$log['operator_id']]['speed_log'][$step][1];
								$oldDistance = ($oldTime/3600)*$oldSpeed;
								
								//Новопосчитанные
								$newTime = $val[0];
								$newSpeed = $val[1];
								$newDistance = ($newTime/3600)*$newSpeed;
								
								//Суммарные показатели
								$sumTime = $oldTime + $newTime;
								$sumDistance = $oldDistance + $newDistance;
								$newAvSpeed = $sumDistance/$sumTime;
								
								//Запись суммарных данных
								$this->speedLog[$log['log_date']][$log['operator_id']]['speed_log'][$step][0] = $sumTime;
								$this->speedLog[$log['log_date']][$log['operator_id']]['speed_log'][$step][1] = $newAvSpeed;
							}
							else{
								$this->speedLog[$log['log_date']][$log['operator_id']]['speed_log'][$step] = $val;
							}
						}						
					}
				}
			}
		}
	}	
	
	
	/**
	 * Пересчитывает почасовой лог за период и перезаписывает в базу
	 */
	public function remakeLog($from, $to){
		$devices = Devices::find()->asArray()->all();
		
		$from = new \DateTime($from);
		$to = new \DateTime($to);
		$periodTo = clone $from;
		$periodTo->add(new \DateInterval('P29DT23H59M59S'));
		
		while($from<$to){
			if($periodTo>$to){
				$periodTo = clone $to;
			}
			echo $from->format('Y-m-d H:i:s').' - '.$periodTo->format('Y-m-d H:i:s')."\n";
			if($devices){
				foreach($devices as $d){
					$logs = (new \yii\db\Query())
						->select([
							'id',
							'device_id',
							'operator_id',
							'log_date',
							'active_time',
							'work_time',
							'service',
							'card_id',
							'idle',
							'ign_time',
							'eng_time',
						])
						->where(['and', ['>=','log_date', $from->format('Y-m-d H:i:s')], ['<=','log_date', $periodTo->format('Y-m-d H:i:s')], ['device_id'=>$d['id']]])
						->from(LogCrude::tableName())
						->all();
					if(count($logs)){
						echo $d['id']." ".count($logs)."\n";
					}						
					if($logs){
						$this->prepareDeviceHourLog($logs, false);
						$this->writeLog($d['id'], ['from'=>$from->format('Y-m-d H:i:s'), 'to'=>$periodTo->format('Y-m-d H:i:s')]);
					}
				}
			}
			$from->add(new \DateInterval('P30D'));
			$periodTo->add(new \DateInterval('P30D'));
		}
	}


	/**
	 * Пересчитывает почасовой лог СКОРОСТИ за период и перезаписывает в базу
	 */
	public function remakeSpeedLog($from, $to){
		$devices = Devices::find()->asArray()->all();
		
		$from = new \DateTime($from);
		$to = new \DateTime($to);
		$periodTo = clone $from;
		$periodTo->add(new \DateInterval('P29DT23H59M59S'));
		
		while($from<$to){
			if($periodTo>$to){
				$periodTo = clone $to;
			}
			echo $from->format('Y-m-d H:i:s').' - '.$periodTo->format('Y-m-d H:i:s')."\n";
			if($devices){
				foreach($devices as $d){
					$logs = (new \yii\db\Query())
						->select([
							'id',
							'device_id',
							'operator_id',
							'log_date',
							'active_time',
							'work_time',
							'service',
							'card_id',
							'idle',
							'ign_time',
							'eng_time',
							'av_speed',
						])
						->where(['and', ['>=','log_date', $from->format('Y-m-d H:i:s')], ['<=','log_date', $periodTo->format('Y-m-d H:i:s')], ['device_id'=>$d['id']]])
						->from(LogCrude::tableName())
						->all();
					if(count($logs)){
						echo $d['id']." ".count($logs)."\n";
					}
					if($logs && $d['speed_sensor']){
						$this->prepareSpeedHourLog($logs, false);						
						$this->calculateSpeedLogValues($d['id']);
						$this->writeLog($d['id'], false, ['from'=>$from->format('Y-m-d H:i:s'), 'to'=>$periodTo->format('Y-m-d H:i:s')]);
					}
				}
			}
			$from->add(new \DateInterval('P30D'));
			$periodTo->add(new \DateInterval('P30D'));
		}
	}	
	
	/**
	 * Вычисление лога по технике
	 */
	private function prepareDeviceHourLog($logs, $serchRewrite=true){
		$this->hourLog = [];
		$this->archiveId = [];
		$this->rewriteWhereCause = [];
		foreach($logs as $log){
			$this->archiveId[] = $log['id'];
			$currentTime = new \DateTime($log['log_date']);
			$time = $currentTime->format('Y-m-d H:59:59');
			$currentTime->setTime(($currentTime->format('H')+0),0,0);
			$difStep = strtotime($log['log_date']) - $currentTime->getTimestamp();
			$step = (int)($difStep/90)+($difStep%90 ? 1 : 0);
			
			if($log['operator_id']){
				$operator = $log['operator_id'];	// если id оператора отличен от 0 то записываем его
			}
			elseif($log['card_id']){
				$operator = $log['card_id']*(-1);	// в противном случае записываем id карты со знаком -, это позволит отличить карту от оператора и при добавлении карты переработать статистику
			}
			else{
				$operator = 0;						// режим работы без контроля доступа
			}
			
			$log = $this->validateLogData($log);
			
			if(!isset($this->hourLog[$time][$operator]))
			{
				// Если метод запущен в режиме дополнения существующих данных,
				// формируем условие, какие строки в HourLog нужно переписать
				if($serchRewrite){
					$this->rewriteWhereCause[] = ['and', ['log_date' => $time], ['device_id' => $log['device_id']], ['operator_id' => $operator]];
				}
				// инициализируем id нулем, далее, если данные за этот час уже есть в HourLog,
				// элементу будет присвоен id существующей строки, данные будут просуммированы, а строка перезаписана
				$this->hourLog[$time][$operator]['id'] = 0;
				$this->hourLog[$time][$operator]['active'] = 0; 
				$this->hourLog[$time][$operator]['work'] = 0;
				$this->hourLog[$time][$operator]['idle'] = 0;
				$this->hourLog[$time][$operator]['ignition'] = 0;
				$this->hourLog[$time][$operator]['engine'] = 0;
				$this->hourLog[$time][$operator]['empty_ride'] = 0;
				$this->hourLog[$time][$operator]['active_log'] = [];
				$this->hourLog[$time][$operator]['work_log'] = [];
				
				$this->hourLog[$time][$operator]['ignition_log'] = [];
				$this->hourLog[$time][$operator]['idle_log'] = [];				
			}
			
			if(!$log['service']){
				$this->hourLog[$time][$operator]['active'] += $log['active_time']; 
				$this->hourLog[$time][$operator]['work'] += $log['work_time'];
				$this->hourLog[$time][$operator]['idle'] += $log['idle'];
				$this->hourLog[$time][$operator]['ignition'] += $log['ign_time'];
				$this->hourLog[$time][$operator]['engine'] += $log['eng_time'];
				$this->hourLog[$time][$operator]['empty_ride'] += $this->calculeteEmptyRide($log);
			}
			
			if($log['active_time']){
				if(!isset($this->hourLog[$time][$operator]['active_log'][$step])){
					$this->hourLog[$time][$operator]['active_log'][$step] = 0;
				}
				$this->hourLog[$time][$operator]['active_log'][$step] += $log['active_time'];
			}
			
			if($log['work_time']){
				if(!isset($this->hourLog[$time][$operator]['work_log'][$step])){
					$this->hourLog[$time][$operator]['work_log'][$step] = 0;
				}
				$this->hourLog[$time][$operator]['work_log'][$step] += $log['work_time'];
			}
			
			if($log['ign_time']){
				if(!isset($this->hourLog[$time][$operator]['ignition_log'][$step])){
					$this->hourLog[$time][$operator]['ignition_log'][$step] = 0;
				}
				$this->hourLog[$time][$operator]['ignition_log'][$step] += $log['ign_time'];
			}	

			if($log['idle']){
				if(!isset($this->hourLog[$time][$operator]['idle_log'][$step])){
					$this->hourLog[$time][$operator]['idle_log'][$step] = 0;
				}
				$this->hourLog[$time][$operator]['idle_log'][$step] += $log['idle'];
			}			
		}
	}
	
	
	/**
	 * Вычисление времени холостой езды
	 */
	private function calculeteEmptyRide($l){
		if($l['idle'] !== null){
			$emptyRide = $l['ign_time'] - $l['idle'] - $l['work_time'];
		}
		else{
			$emptyRide = ($l['active_time']>$l['work_time']) ? ($l['active_time']-$l['work_time']) : 0;
		}
		return $emptyRide;
	}
	
	
	/**
	 * Проверка корректности данных в логе
	 */
	private function validateLogData($log){
		if($log['idle'] === null){
			$log['idle'] = 10000-$log['active_time'];
			$log['idle'] = $log['idle']<0 ? 0 : $log['idle'];
		}
		
		if($log['ign_time'] === null){
			$log['ign_time'] = $log['active_time']+$log['idle'];
			if($log['ign_time'] > 10000){
				$log['ign_time']=10000;
			}
		}

		if($log['eng_time'] === null){
			$log['eng_time'] = $log['ign_time'];
		}
				
		if($log['eng_time'] > $log['ign_time']){
			$log['eng_time'] = $log['ign_time'];
		}
		
		if($log['eng_time']==0 && $log['active_time']>0 && $log['ign_time']>0){
			$log['eng_time'] = $log['ign_time'];
		}
		
		if($log['active_time']>$log['eng_time']){
			$log['active_time'] = $log['eng_time'];
		}
		
		if($log['work_time']>$log['eng_time']){
			$log['work_time'] = $log['eng_time'];
		}
		
		if($log['active_time']>=$log['work_time']){
			if(($log['active_time'] + $log['idle']) > $log['ign_time']){
				$log['idle'] =  $log['ign_time'] - $log['active_time'];
			}
		}
		else{
			if(($log['work_time'] + $log['idle']) > $log['ign_time']){
				$log['idle'] =  $log['ign_time'] - $log['work_time'];
			}
		}
		
		return $log;
	}
	
	
	/**
	 * Поиск и подготовка строк для перезаписи
	 */
	private function findRowsForRewriteHourLog(){
		if($this->rewriteWhereCause){
			if(count($this->rewriteWhereCause)>1){
				array_unshift($this->rewriteWhereCause, 'or');
			}
			else{
				$this->rewriteWhereCause = array_shift($this->rewriteWhereCause);
			}
			
			$logs = HourLog::find()->where($this->rewriteWhereCause)->asArray()->all();
			if($logs){
				foreach($logs as $log){
					if(isset($this->hourLog[$log['log_date']][$log['operator_id']])){
						$this->hourLog[$log['log_date']][$log['operator_id']]['id'] = $log['id'];
						$this->hourLog[$log['log_date']][$log['operator_id']]['active'] += $log['active']*1000; 
						$this->hourLog[$log['log_date']][$log['operator_id']]['work'] += $log['work']*1000;
						$this->hourLog[$log['log_date']][$log['operator_id']]['idle'] += $log['idle']*1000;
						$this->hourLog[$log['log_date']][$log['operator_id']]['ignition'] += $log['ignition']*1000;
						$this->hourLog[$log['log_date']][$log['operator_id']]['engine'] += $log['engine']*1000;
						$this->hourLog[$log['log_date']][$log['operator_id']]['empty_ride'] += $log['empty_ride']*1000;
						
						$activeLog = unserialize($log['active_log']);
						foreach($activeLog as $step=>$val){
							$val = $val*900;
							if(isset($this->hourLog[$log['log_date']][$log['operator_id']]['active_log'][$step])){
								$this->hourLog[$log['log_date']][$log['operator_id']]['active_log'][$step] += $val;
							}
							else{
								$this->hourLog[$log['log_date']][$log['operator_id']]['active_log'][$step] = $val;
							}
						}
						
						$workLog = unserialize($log['work_log']);
						foreach($workLog as $step=>$val){
							$val = $val*900;
							if(isset($this->hourLog[$log['log_date']][$log['operator_id']]['work_log'][$step])){
								$this->hourLog[$log['log_date']][$log['operator_id']]['work_log'][$step] += $val;
							}
							else{
								$this->hourLog[$log['log_date']][$log['operator_id']]['work_log'][$step] = $val;
							}
						}
						
						
						$ignitionLog = unserialize($log['ignition_log']) ? unserialize($log['ignition_log']) : [];
						foreach($ignitionLog as $step=>$val){
							$val = $val*900;
							if(isset($this->hourLog[$log['log_date']][$log['operator_id']]['ignition_log'][$step])){
								$this->hourLog[$log['log_date']][$log['operator_id']]['ignition_log'][$step] += $val;
							}
							else{
								$this->hourLog[$log['log_date']][$log['operator_id']]['ignition_log'][$step] = $val;
							}
						}

						$idleLog = unserialize($log['idle_log']) ? unserialize($log['idle_log']) : [];
						foreach($idleLog as $step=>$val){
							$val = $val*900;
							if(isset($this->hourLog[$log['log_date']][$log['operator_id']]['idle_log'][$step])){
								$this->hourLog[$log['log_date']][$log['operator_id']]['idle_log'][$step] += $val;
							}
							else{
								$this->hourLog[$log['log_date']][$log['operator_id']]['idle_log'][$step] = $val;
							}
						}						
					}
				}
			}
		}
	}
	
	
	/**
	 * Запись в базу
	 */
	private function writeLog($device, $rewriteHourLog=false, $rewriteSpeedLog = false){
		//Если сформированны данные по хотя бы одному логу, то начинаем транзакцию
		if($this->hourLog || $this->speedLog){	
			try{
				
				$transaction = Yii::$app->db->beginTransaction();
				//Очистка лога, если запущен процесс перезаписи часовых логов
				if($rewriteHourLog){
					HourLog::deleteAll(['and', ['device_id'=>$device], ['>=','log_date', $rewriteHourLog['from']], ['<=','log_date', $rewriteHourLog['to']]]);
				}
				
					// LogCrude24h::updateAll(['archive'=>1], ['id'=>$this->archiveId]);
				
				//Очистка лога, если запущен процесс перезаписи часовых логов СКОРОСТИ
				if($rewriteSpeedLog){
					SpeedHourLog::deleteAll(['and', ['device_id'=>$device], ['>=','log_date', $rewriteSpeedLog['from']], ['<=','log_date', $rewriteSpeedLog['to']]]);
				}
				
				if(!$rewriteHourLog && !$rewriteSpeedLog){
					LogCrude24h::updateAll(['archive'=>1], ['id'=>$this->archiveId]);
				}
						
				//Обработка данных часового лога
				if($this->hourLog){
					$this->writeHourLog($device);
				}
				
				//Обработка данных часового лога скорости
				if($this->speedLog){
					$this->writeSpeedHourLog($device);
				}
				
				$transaction->commit();
			}
			catch (\Exception $e) {
				$transaction->rollBack();
				throw $e;				
			}			
		}		
	}


	/**
	 * Запись в базу часового лога
	 */	
	private function writeHourLog($device){
		$newRows = [];
		foreach($this->hourLog as $time=>$operators){
			foreach($operators as $operatorId=>$data){
				if(!($operatorId==0 && $data['ignition']<60000)){ // Отбрасываем логи, сгенерированые в момент деавторизации
					if($data['id']){
						$log = HourLog::findOne($data['id']);
						$log->archive = 0;
						$log->active = (int)($data['active']/1000);
						$log->work = (int)($data['work']/1000);
						$log->idle = (int)($data['idle']/1000);
						$log->ignition = (int)($data['ignition']/1000);
						$log->engine = (int)($data['engine']/1000);
						$log->empty_ride = (int)($data['empty_ride']/1000);
						$log->active_log = serialize(Report::prepareStepValue($data['active_log']));
						$log->work_log = serialize(Report::prepareStepValue($data['work_log']));
						$log->idle_log = serialize(Report::prepareStepValue($data['idle_log']));
						$log->ignition_log = serialize(Report::prepareStepValue($data['ignition_log']));						
						$log->update();
					}
					else{
						$newRows[] = [
							'log_date'		=> $time,
							'operator_id'	=> $operatorId,
							'device_id'		=> $device,
							'active'		=> (int)($data['active']/1000),
							'work'			=> (int)($data['work']/1000),
							'idle'			=> (int)($data['idle']/1000),
							'ignition'		=> (int)($data['ignition']/1000),
							'engine'		=> (int)($data['engine']/1000),
							'empty_ride'	=> (int)($data['empty_ride']/1000),
							'active_log'	=> serialize(Report::prepareStepValue($data['active_log'])),
							'work_log'		=> serialize(Report::prepareStepValue($data['work_log'])),
							'idle_log'		=> serialize(Report::prepareStepValue($data['idle_log'])),
							'ignition_log'		=> serialize(Report::prepareStepValue($data['ignition_log'])),							
						];
					}
				}
			}
		}
					
		if($newRows){
			$columns = [
				'log_date',
				'operator_id',
				'device_id',
				'active',
				'work',
				'idle',
				'ignition',
				'engine',
				'empty_ride',
				'active_log',
				'work_log',
				'idle_log',
				'ignition_log',				
			];
			Yii::$app->db->createCommand()->batchInsert(HourLog::tableName(), $columns, $newRows)->execute();
		}		
	}


	/**
	 * Запись в базу часового лога скорости
	 */		
	private function writeSpeedHourLog($device){
		$newRows = [];
		foreach($this->speedLog as $time=>$operators){
			foreach($operators as $operatorId=>$data){
				if(!($operatorId==0 && $data['ignition']<60000) && ($data['av_speed'] > 0)){
					if($data['id']){
						$log = SpeedHourLog::findOne($data['id']);
						$log->archive = 0;
						$log->av_speed = (int)(round($data['av_speed']*10));
						$log->max_speed = (int)(round($data['max_speed']*10));
						$log->distance = (int)(round($data['distance']));
						$log->speed_log = serialize($data['speed_log']);
						$log->distribution = serialize($data['distribution']);
						$log->update();
					}
					else{
						$newRows[] = [
							'log_date'		=> $time,
							'operator_id'	=> $operatorId,
							'device_id'		=> $device,
							'archive'		=> 0,
							
							'av_speed'		=> (int)(round($data['av_speed']*10)),
							'max_speed'		=> (int)(round($data['max_speed']*10)),
							'distance'		=> (int)(round($data['distance'])),
							
							'speed_log'		=> serialize($data['speed_log']),
							'distribution'	=> serialize($data['distribution']),				
						];
					}							
				}
			}
		}	

		if($newRows){
			$columns = [
				'log_date',
				'operator_id',
				'device_id',
				'archive',
				
				'av_speed',
				'max_speed',
				'distance',
				
				'speed_log',
				'distribution',
			];
			Yii::$app->db->createCommand()->batchInsert(SpeedHourLog::tableName(), $columns, $newRows)->execute();
		}		
	}
}