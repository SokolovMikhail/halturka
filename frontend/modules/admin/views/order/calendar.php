<?
use frontend\widgets\FilterStoragesWidget;
use yii\widgets\LinkPager;
use app\modules\admin\models\PurchasingOrder;
use app\modules\admin\models\Client;
use yii\helpers\Html;
use frontend\widgets\FilterSelectWidget;
use yii\helpers\ArrayHelper;
use frontend\widgets\FilterItemsCheckListWidget;
use frontend\modules\admin\assets\AdminAsset;

AdminAsset::register($this);

$this->title = 'План установок';
$this->params['main_nav_current'] = 'admin/order/calendar';
?>
<div class="row">
<div class="panel">
<div class="panel-body collectonme">
	<div data-cookie-name="admin-calendar">
		<div class="row">
			<div class="col-xs-12">
				<ul class="nav nav-tabs inner-nav-tabs" data-nav-tabs-linked="">
					<li class="" data-cookie-tab="-1">
						<a data-toggle="tab" href="#tab-map" class="mb-10 mt-10">Карта</a>
					</li>		
					<li class="" data-cookie-tab="0">
						<a data-toggle="tab" href="#tab-0" class="mb-10 mt-10">Календарь</a>
					</li>		
			<?$i = 1;?>
			<?foreach($result['plan'] as $date=>$orders){?>
			
					<li class="<?= $i==1 ? 'active' : ''?>" data-cookie-tab="<?= $i?>">
						<a href="#tab-<?= $i?>" data-toggle="tab"  class="mb-10 mt-10"><?= $date?> <span class="fa fa-home tab-icon"></span><?= $orders['total_orders_count']?> <span class="fa fa-car tab-icon"></span><?= $orders['total_devices_count']?></a>
					</li>
			<?$i++?>
			<?}?>			
				</ul>
			</div>
		</div>
			<div class="tab-content">
				<div id="tab-map" class="tab-pane" data-tab-content="-1" >
						<div class="row mb-10">
							<div class="col-md-12">
								<select class="selectpicker filter-field months-select mb-20" multiple data-select-picker="" data-actions-box="true" data-selected-text-format="count > 6">
								<?foreach($monthsList as $month){?>
									<option <?= $month==$currentMonth ? 'selected' : ''?>><?= $month?></option>
								<?}?>
								</select>
								<label class="ml-10 mt-2">
									<?= Html::checkbox('installed', false, ['data-show-installed' => '']) ?>
									Показывать установленные
								</label>							
								<button class="btn btn-primary ml-10 mt-2" data-show-cities="">Показать на карте</button>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div id="map" style="width: 1098px; height: 650px" data-yandex-map='<?= $cities?>'></div>
							</div>
						</div>
				</div>		
				<div id="tab-0" class="tab-pane" data-tab-content="0" >
					<div class="col-md-12">
						<iframe src="https://calendar.google.com/calendar/embed?height=650&amp;wkst=2&amp;bgcolor=%23FFFFFF&amp;src=52a8m3ramlt94hhs5ftj4d5b6s%40group.calendar.google.com&amp;color=%23182C57&amp;src=73dd0npqji4plp03p051ufcdeg%40group.calendar.google.com&amp;color=%23711616&amp;src=beg2j3n7gh2pvnsr98k8fkfsls%40group.calendar.google.com&amp;color=%23125A12&amp;ctz=Asia%2FYekaterinburg" style="border-width:0" width="1070" height="650" frameborder="0" scrolling="no"></iframe>		
					</div>
				</div>
		<?$i = 1;?>
		<?foreach($result['plan'] as $date=>$orders){?>
		
				<div id="tab-<?= $i?>" class="tab-pane <?= $i==1 ? 'active' : ''?>" data-tab-content="<?= $i?>" data-tab-date="<?= $date?>">
						<div class="row mb-20 mt-10">
							<div class="col-md-6">
								<a href="/admin/order/create/?month=<?= $date?>" class="font-14">Создать заявку <span class="fa fa-rocket" aria-hidden="true"></span></a>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="row">
									<div class="col-md-3">
										<p class="huge-title"><?= $orders['date_string']?></p>
									</div>
									<div class="col-md-9 mt-10">
										<a href="/admin/order/get-warranty-month-xls/?month=<?= $date?>">Гарантийные услуги <span class="fa fa-download" aria-hidden="true"></span></a>
									</div>
								</div>
							</div>					
							<div class="col-md-6">
								<p class="huge-title">Установок: <?= $orders['total_orders_count']?></p>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<p class="medium-title" data-devices-total-amount="">Всего машин: <?= $orders['total_devices_count']?></p>
							</div>
							<div class="col-md-3">
								<p class="medium-title" data-devices-installed-amount="">Установлено: <?= $orders['installed_count']?></p>
							</div>
							<div class="col-md-3">
								<p class="medium-title" data-devices-in-work-amount="">В работе: <?= $orders['work_count']?></p>
							</div>
							<div class="col-md-3">
								<p class="medium-title" data-devices-undefined-amount="">Не распределены: <?= $orders['others_count']?></p>
							</div>						
						</div>
						<table class="table table-hover personal-task mt-10">
							<tbody>
								<tr class="personal-task_head">
									<td>Заявка</td>
									<td  class="ta-c">Количество машин</td>
									<td>Дата отгрузки</td>
									<td>Дата установки</td>
									<td class="ta-c">Ответственный</td>
									<td class="ta-c">Договор</td>													
									<td class="ta-c">Список техники</td>
									<td class="ta-c">Производство оборудования</td>
									<td class="ta-c">Производство жгутов</td>
									<td class="ta-c">Аккаунт</td>
									<td class="ta-c">Согласование</td>
									<td class="ta-c">Документы</td>
									<td>Установщик</td>
									<td></td>
								</tr>
								<?$uniqueId = 0;?>
								<?foreach($orders['items'] as $item){?>
								
									<tr class="<?= $item['background']?>" data-order-tr="">
										<td><a href="/admin/order/update/?id=<?=$item['id']?>" title="Перейти к редактированию"><?= $item['name']?></a></td>										
										<td class="ta-c"><?= $item['devices_count']?>/<?= $item['devices_count_warranty']?></td>
										<td>
											<div class="clickable as-link"
												data-order-id="<?= $item['id']?>"
												data-status-name="date_shipment"
												data-daterangepicker
												data-start-date="<?= $item['date_shipment']?>"
												data-use-time-picker="false"
												data-single-date-picker="true"
												data-show-ranges = "false"><?= $item['date_shipment']?></div>
										</td>
										<td>
											<div class="clickable as-link" 
												data-order-id="<?= $item['id']?>"
												data-status-name="date"												
												data-daterangepicker
												data-start-date="<?= $item['date']?>"
												data-use-time-picker="false"
												data-single-date-picker="true"
												data-show-ranges = "false"><?= $item['date']?></div>
										</td>
										<td class="ta-c">
											<div class="popr" data-id="6" data-order-id="<?= $item['id']?>" data-unique-id="<?= $uniqueId++?>" data-status-name="responsible">
												<?= $item['responsible'] ? PurchasingOrder::responsiblePersonsLong()[$item['responsible']]  : PurchasingOrder::responsiblePersonsLong()[0]?>
											</div>											
										</td>
										<td class="ta-c">
											<div class="popr" data-id="1" data-order-id="<?= $item['id']?>" data-unique-id="<?= $uniqueId++?>" data-status-name="contract_status">
												<?= $item['status_1'] ? PurchasingOrder::contractStatuses()[$item['status_1']]  : PurchasingOrder::contractStatuses()[0]?>
											</div>
										</td>
										<td class="ta-c">
											<div class="popr" data-id="2" data-order-id="<?= $item['id']?>" data-unique-id="<?= $uniqueId++?>" data-status-name="devices_list_status">
												<?= $item['status_2'] ? PurchasingOrder::devicesListStatuses()[$item['status_2']] : PurchasingOrder::devicesListStatuses()[0]?>
											</div>
										</td>
										<td class="ta-c">
											<div class="popr" data-id="3" data-order-id="<?= $item['id']?>" data-unique-id="<?= $uniqueId++?>" data-status-name="equipment_prod_status">
												<?= $item['status_3'] ? PurchasingOrder::equipmentProductionStatuses()[$item['status_3']] : PurchasingOrder::equipmentProductionStatuses()[0]?>
											</div>
										</td>
										<td class="ta-c">
											<div class="popr" data-id="4" data-order-id="<?= $item['id']?>" data-unique-id="<?= $uniqueId++?>" data-status-name="harness_prod_status">
												<?= $item['status_4'] ? PurchasingOrder::harnessProductionStatuses()[$item['status_4']] : PurchasingOrder::harnessProductionStatuses()[0]?>
											</div>
										</td>
										<td class="ta-c">
											<div class="popr" data-id="5" data-order-id="<?= $item['id']?>" data-unique-id="<?= $uniqueId++?>" data-status-name="account_status">
												<?= $item['status_5'] ? PurchasingOrder::accountStatuses()[$item['status_5']] : PurchasingOrder::accountStatuses()[0]?>
											</div>
										</td>
										<td class="ta-c" data-order-id="<?= $item['id']?>">
											<i class="fa fa-cogs icon-big clickable <?= $item['installer_agreement'] ? 'dark-green-clr' : 'dark-red-clr'?>" aria-hidden="true" data-toggle="tooltip" data-placement="top" data-original-title="С установщиком" data-agreement-status="installer_agreement" ></i>
											<span class="separator">|</span>
											<i class="fa fa-user icon-big clickable <?= $item['client_agreement'] ? 'dark-green-clr' : 'dark-red-clr'?>" aria-hidden="true" aria-hidden="true" data-toggle="tooltip" data-placement="top" data-original-title="С клиентом" data-agreement-status="client_agreement"></i>
										</td>
										<td class="ta-c" data-order-id="<?= $item['id']?>">
											<?if($item['installed']){?>
											<i class="fa fa-check icon-big clickable dark-green-clr" aria-hidden="true" aria-hidden="true" data-toggle="tooltip" data-placement="top" data-original-title="Установлено" data-installed-status="installed"></i>
											<?}else{?>
											<i class="fa fa-times icon-big clickable dark-red-clr" aria-hidden="true" aria-hidden="true" data-toggle="tooltip" data-placement="top" data-original-title="Не установлено" data-installed-status="installed"></i>
											<?}?>
											</br>
											<?if($item['installed_comment_short']){?>
											<div <?= $item['installed_comment_full'] ? 'class="clickable-link" tabindex="0" data-trigger="focus" data-container="body" data-toggle="popover" data-placement="bottom" data-content="'.$item['installed_comment_full'].'"' : ''?>><?= $item['installed_comment_short']?></div>
											<?}?>
										</td>									
										<td><?= $item['contractor_comment']?></td>
										<td>
											<span class="fa fa-wrench fa-1 <?= $item['devices_count_warranty'] ? $item['warranty_devices_checked'] ? 'dark-green-clr' : 'dark-red-clr' : 'dsp-none'?>" 
												aria-hidden="true"
												data-toggle="tooltip" 
												data-placement="top" 
												title="" 
												data-original-title="<?= $item['warranty_devices_checked'] ? 'Есть отчет по гарантии' : 'Нет отчета по гарантии'?>"							
												>
											</span>	
											<span class="fa fa-file-image-o fa-1 <?= $item['order_photo'] ? 'dark-green-clr' : 'dark-red-clr'?>" 
												aria-hidden="true"
												data-toggle="tooltip" 
												data-placement="top" 
												title="" 
												data-original-title="<?= $item['order_photo'] ? 'Есть все фото' : 'Нет фото'?>"							
												>
											</span>										
										</td>
									</tr>
								<?}?>
							</tbody>
						</table>				
				</div>
				
		<?$i++?>
		<?}?>		
	
				
			</div>
	</div>
</div>
</div>
</div>
<div class="popr-box" data-box-id="1">
<?foreach(PurchasingOrder::contractStatuses() as $val=>$text){?>
	<?if($text){?>
	<div class="popr-item clickable" data-status-item="" data-val="<?= $val?>"><?= $text?></div>
	<?}else{?>
	<div class="popr-item clickable" data-status-item="" data-val="<?= $val?>">-</div>
	<?}?>
<?}?>
</div>
<div class="popr-box" data-box-id="2">
<?foreach(PurchasingOrder::devicesListStatuses() as $val=>$text){?>
	<?if($text){?>
	<div class="popr-item clickable" data-status-item="" data-val="<?= $val?>"><?= $text?></div>
	<?}else{?>
	<div class="popr-item clickable" data-status-item="" data-val="<?= $val?>">-</div>
	<?}?>
<?}?>
</div>
<div class="popr-box" data-box-id="3">
<?foreach(PurchasingOrder::equipmentProductionStatuses() as $val=>$text){?>
	<?if($text){?>
	<div class="popr-item clickable" data-status-item="" data-val="<?= $val?>"><?= $text?></div>
	<?}else{?>
	<div class="popr-item clickable" data-status-item="" data-val="<?= $val?>">-</div>
	<?}?>
<?}?>
</div>
<div class="popr-box" data-box-id="4">
<?foreach(PurchasingOrder::harnessProductionStatuses() as $val=>$text){?>
	<?if($text){?>
	<div class="popr-item clickable" data-status-item="" data-val="<?= $val?>"><?= $text?></div>
	<?}else{?>
	<div class="popr-item clickable" data-status-item="" data-val="<?= $val?>">-</div>
	<?}?>
<?}?>
</div>
<div class="popr-box" data-box-id="5">
<?foreach(PurchasingOrder::accountStatuses() as $val=>$text){?>
	<?if($text){?>
	<div class="popr-item clickable" data-status-item="" data-val="<?= $val?>"><?= $text?></div>
	<?}else{?>
	<div class="popr-item clickable" data-status-item="" data-val="<?= $val?>">-</div>
	<?}?>
<?}?>
</div>
<div class="popr-box" data-box-id="6">
<?foreach(PurchasingOrder::responsiblePersonsLong() as $val=>$text){?>
	<?if($text){?>
	<div class="popr-item clickable" data-status-item="" data-val="<?= $val?>"><?= $text?></div>
	<?}else{?>
	<div class="popr-item clickable" data-status-item="" data-val="<?= $val?>">-</div>
	<?}?>
<?}?>
</div>