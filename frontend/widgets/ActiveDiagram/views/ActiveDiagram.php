<?
use yii\helpers\ArrayHelper;
use frontend\models\Storages;


$arStorages = ArrayHelper::index(Storages::find()->asArray()->all(), 'id');
$useIdle = Yii::$app->config->params['account']['db'] == 'tvz_sportmaster' ? 1 : 0;
$diagramHeight = 50*$useActive+50*$useWork+50*$useIdle+50*$useVoltage+75;

foreach($items as $item)
{?>
	<div class="panel rating-panel">
		<div class="panel-body">
			<div class="row">
				<div class="col-xs-3" data-status>
					<?if(isset($item['is_active'])){?>
						<div>
						<span class="event-statement <?=$item['status_class']?> inline-statement" data-status-icon></span>
						<div class="statement-label">
							<b data-status-text><?= isset($item['status_title']) ? $item['status_title'] : ''?></b>
						</div>
						</div>
						<div class="mt-10">
							<? if($item['is_active'] == 4 && (in_array('superadmin',Yii::$app->config->params['user']['roles'],false)||in_array('unlocker',Yii::$app->config->params['user']['roles'],false))){?>
							<a href="#" data-unlock-url="/device-server/unlock-device/?deviceId=" data-unlock-id="<?= $item['id']?>">Cбросить блокировку</a>
							<?}?>
						</div>
					<?}
					elseif(isset($item['day_start'])){?>
						<b><?=$item['day_start']?> - <?=$item['day_end']?></b>
					<?}?>
				</div>
				<div class="col-xs-3">
					<a href="/reports/<?=$entityName?>s/?<?=$entityName?>=<?=$item['id']?>"><?=$item['name']?></a>
					<br>
					<?= isset($arStorages[$item['storage_id']]['name']) ? $arStorages[$item['storage_id']]['name'] : ''?>
				</div>
				<div class="col-xs-6">
				<ul class="ls-n active-diagram_entities">
					<?
					if(isset($item['subentities'])){
						$other = false;
						foreach ($item['subentities'] as $subItem){
							if($subItem['id']){
								$name = isset($subItem['name']) ? $subItem['name'] : $subItem['card_id'];
								$namePrint = isset($subItem['current']) && $subItem['current'] ? '<b>'.$name.'</b>' : $name;
								echo '<li class="li-circle-marker entity-'.$subItem['id'].'">';
								if(isset($subItem['name'])){
									echo '<a href="/reports/'.$subentityName.'s/?'.$subentityName.'='.$subItem['id'].'">'.$namePrint.'</a>';
								}
								// elseif(array_intersect(Yii::$app->config->params['user']['roles'], ['admin', 'superadmin'])){
									// echo '<span class="js-link" data-create-operator-with-card="'.$subItem['card_id'].'" data-create-operator-on-storage="'.$item['storage_id'].'">'.$namePrint.'</span>';
								// }
								else{
									echo '<span class="normal-text">'.$namePrint.'</span>';
								}
								echo '</li>';
							}
							else{
								$other = true;
							}
						}
						if($other){
							echo '<li class="li-circle-marker text-grey">Прочие</li>';
						}
					}
					?>
				</ul>
				</div>
			</div>
		</div>
		<table class="operator-diagram_table">
			<tbody>
				<tr>
					<td>
						<div class="active-diagram-description">
							<ul>
								<li class="active-diagram-description_text">
									Включен: <b><?= isset($item['total_engine']) ? $item['total_engine'] : '0' ?> </b>
									<?= isset($item['total_engine_percent']) ? '('.$item['total_engine_percent'].'%)' : '';?>
								</li>
								<li class="active-diagram-description_text">
									<a href="/reports/crashes/?<?=$entityName?>=<?=$item['id']?>">Происшествия: </a>
									<span title="C первышением порога/с блокировкой">
										<?= isset($item['crashes']) ? $item['crashes']['total_excess'].' / <span class="text-red">'.$item['crashes']['total_locking'].'</span>' : ''?>
									</span> 
								</li>
								<?if($useVoltage){?>
								<li class="nb-arrow_wrap">
									<div class="nb-arrow" data-nb="arrow">
										<div class="nb-arrow__top orange-border"></div>
										<div class="nb-arrow__bottom orange-border"></div>
										<div class="nb-arrow__content orange-border">
											Напряжение АКБ<br>
											<?= isset($item['voltage']) ? '<b>Текущее:</b> '.$item['voltage'].' в.' : ''?>
										</div>
									</div>
								</li>
								<?}?>
								<?if($useWork){?>
								<li class="nb-arrow_wrap">
									<div class="nb-arrow" data-nb="arrow">
										<div class="nb-arrow__top blue-border"></div>
										<div class="nb-arrow__bottom blue-border"></div>
										<div class="nb-arrow__content blue-border">
											Работа вилами:<br>
											<b><?= isset($item['total_work']) ? $item['total_work'] : '0'?></b>
											<?if(isset($item['total_work_percent'])){?>
												&nbsp;&nbsp;(<?=$item['total_work_percent']?>%)
											<?}
											elseif(isset($item['total_active_to_work'])){?>
												&nbsp;&nbsp;(<?=$item['total_active_to_work']?>%)
											<?}?>
										</div>
									</div>
								</li>
								<?}?>
								<?if($useActive){?>
								<li class="nb-arrow_wrap">
									<div class="nb-arrow" data-nb="arrow">
										<div class="nb-arrow__top green-border"></div>
										<div class="nb-arrow__bottom green-border"></div>
										<div class="nb-arrow__content green-border">
											Передвижение:<br>
											<b><?= isset($item['total_active']) ? $item['total_active'] : '0'?></b>
											<?if(isset($item['total_active_percent'])){?>
												&nbsp;&nbsp;(<?=$item['total_active_percent']?>%)
											<?}?>
										</div>
									</div>
								</li>
								<?}?>
								<?if($useIdle){?>
								<li class="nb-arrow_wrap">
									<div class="nb-arrow" data-nb="arrow">
										<div class="nb-arrow__top red-border"></div>
										<div class="nb-arrow__bottom red-border"></div>
										<div class="nb-arrow__content red-border">
											Простои:<br>
											<b><?= isset($item['total_idle']) ? $item['total_idle'] : '0'?></b>
											<?if(isset($item['total_idle_percent'])){?>
												&nbsp;&nbsp;(<?=$item['total_idle_percent']?>%)
											<?}?>
										</div>
									</div>
								</li>
								<?}?>
							</ul>
						</div>
						<div class="active-diagram">
							<canvas  height="<?=$diagramHeight?>" width="960" 
								data-operator-diagram<?= $isHourly ? '-hourly' : ''?>
								data-active-log='<?= isset($item['active_log']) ? json_encode($item['active_log']) : ''?>' 
								data-work-log='<?= isset($item['active_log']) ? json_encode($item['work_log']) : ''?>'
								data-idle-log='<?= isset($item['idle_log']) ? json_encode($item['idle_log']) : ''?>'
								data-service-log='[<? if(isset($item['service_log'])) echo '"'.implode('", "', $item['service_log']).'"'?>]'
								data-hi-crash-log='[<? if(isset($item['crashes']['locking_log'])) echo '"'.implode('", "', $item['crashes']['locking_log']).'"'?>]'
								data-middle-crash-log='[<? if(isset($item['crashes']['excess_log'])) echo '"'.implode('", "', $item['crashes']['excess_log']).'"'?>]'
								data-start-hour="<?=$diagramTimeData['diagram_start_hour']?>"
								data-shift-start="<?=$diagramTimeData['shift_start_hour']?>"
								data-current-hour="<?= isset($diagramTimeData['diagram_current_hour']) ? $diagramTimeData['diagram_current_hour'] : ''?>"
								data-start-day="<?= isset($diagramTimeData['diagram_start_day']) ? $diagramTimeData['diagram_start_day'] : ''?>"
								data-end-day="<?= isset($diagramTimeData['diagram_end_day']) ? $diagramTimeData['diagram_end_day'] : ''?>"
								data-devices-log = '<?= isset($item['subentities']) ? json_encode($item['subentities']) : ''?>'
								data-voltage-log = '<?= isset($item['voltage_log']) ? json_encode($item['voltage_log']) : ''?>'
								data-use-active = '<?=$useActive?>'
								data-use-work = '<?=$useWork?>'
								data-use-idle = '<?=$useIdle?>'
								data-use-voltage = '<?=$useVoltage?>' 
							>
							</canvas>
							<?if(isset($item['activities'])){?>
								<canvas  height="10" width="960" data-activity-diagram data-activities='<?= json_encode($item['activities'])?>'></canvas>
								<ul class="ls-n active-diagram_entities">
									<? foreach($item['activities'] as $activity=>$points){
										echo '<li class="li-circle-marker activity-'.str_replace (' ','-',$activity).'"><span class="normal-text">'.$activity.'</span></li>';
									}?>
								</ul>
							<?}?>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
<?}?>