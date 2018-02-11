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
$this->title = 'Заявки на установку';
$this->params['main_nav_current'] = 'admin/order';
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<div class="panel-body">
				<form data-filter-form>
					<div class="row mb-10">							
							<div class="col-xs-2">
								<?= FilterSelectWidget::widget([
									'name'		=> 'client',
									'items'		=> $statuses_list['clients'],
									'heading'	=> 'Все клиенты',
									'active'	=> isset($_GET['client']) ? $_GET['client'] : ($client) ? $client : 0,
								])?>
							</div>
							<div class="col-xs-2">
								<?= FilterSelectWidget::widget([
									'name'		=> 'parent',
									'items'		=> $parents,
									'heading'	=> 'Все заявки',
									'active'	=> isset($_GET['parent']) ? $_GET['parent'] : 0,
								])?>
							</div>							
							<div class="col-xs-2">
								<?= FilterSelectWidget::widget([
									'name'		=> 'contractor',
									'items'		=> PurchasingOrder::getContractorsFilter(),
									'heading'	=> 'Все подрядчики',
									'active'	=> isset($_GET['contractor']) ? $_GET['contractor'] : 0,
								])?>
							</div>								
					</div>
					<div class="row mb-10">							
							<div class="col-xs-12">
								<?= FilterItemsCheckListWidget::widget([
									'name'	=> 'status',
									'items'	=> $statuses_list['progress'],
									'active'=> isset($_GET['status_list']) ? $_GET['status_list'] : ['new', 'installer_agreement', 'client_agreement'],							
								])?>
							</div>								
					</div>				
					<div class="row mb">
						<div class="col-xs-12">
							<button type="submit" class="btn btn-primary" data-no-xls-link>Применить фильтр</button>
						</div>
					</div>				
				</form>			
			</div>
		</div>
		<div class="panel">
			<div class="panel-body">
				<div class="row">
					<div class = "col-xs-12">				
							<a href="/admin/order/create/" class="btn btn-primary mb-10">Добавить заявку</a>					
					</div>				
				</div>
			<div class="row">
			<div class = "col-xs-12">
			<? if(count($result['items'])){?>
				<table class="table table-hover personal-task">
					<tbody>
						<tr class="personal-task_head">
							<td>Название</td>
							<td>Клиент</td>
							<td>Дата отгрузки</td>
							<td>Дата установки</td>
							<td class="ta-c">Количество техник</td>
							<td>Договор</td>													
							<td>Список техники</td>
							<td>Производство оборудования</td>
							<td class="ta-c">Производство жгутов</td>
							<td>Аккаунт</td>
							<td>Установщик</td>
							<td>Действие</td>
						</tr>
						<? 
						$i=0;
						foreach ($result['items'] as $item){
							$i++;?>
							<tr id="item-<?= $item->id?>">
								<td>
									<a href="/admin/order/update/?id=<?=$item['id']?>" title="Перейти к редактированию" class="<?= isset($result['red'][$item->id]) ? 'text-red' : ''?>"><?= $item['name']?></a>
								</td>
								<td>
									<?= isset(Client::getAllClients()[$item['client_id']]) ? Client::getAllClients()[$item['client_id']] : ''?>
								</td>
								<td>
									<?= isset($item['date_shipment']) && ($item['date_shipment']!=NULL) && ($item['date_shipment']!='Invalid date') ? $item['date_shipment']->format('d.m.Y') : 'Дата отгрузки не выбрана'?>
								</td>								
								<td>
									<?= isset($item['date']) && ($item['date']!=NULL) && ($item['date']!='Invalid date') ? $item['date']->format('d.m.Y') : 'Дата установки не выбрана'?>
								</td>
								<td class="ta-c">
									<?= $items[$item->id]['count']?>/<?= $items[$item->id]['countW']?>
								</td>	
								<td>
									<?= $item['contract_status'] ? PurchasingOrder::contractStatuses()[$item['contract_status']]  : PurchasingOrder::contractStatuses()[0]?>
								</td>								
								<td>
									<?= $item['devices_list_status'] ? PurchasingOrder::devicesListStatuses()[$item['devices_list_status']] : PurchasingOrder::devicesListStatuses()[0]?>
								</td>
								<td>
									<?= $item['equipment_prod_status'] ? PurchasingOrder::equipmentProductionStatuses()[$item['equipment_prod_status']] : PurchasingOrder::equipmentProductionStatuses()[0]?>
								</td>
								<td>
									<?= $item['harness_prod_status'] ? PurchasingOrder::harnessProductionStatuses()[$item['harness_prod_status']] : PurchasingOrder::harnessProductionStatuses()[0]?>
								</td>
								<td>
									<?= $item['account_status'] ? PurchasingOrder::accountStatuses()[$item['account_status']] : PurchasingOrder::accountStatuses()[0]?>
								</td>
								<td>
									<?= $item['contractor_comment'] ? $item['contractor_comment'] : ''?>
								</td>
								<td class="ta-c">
									<a href="/admin/order/create/?id=<?=$item['id']?>"><span class="fa fa-files-o copy-icon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Копировать"></span></a> 
									<a href="/admin/order/delete/?id=<?=$item['id']?>"><span class="fa fa-trash-o delete-icon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Удалить"></span></a> 
								</td>								
							</tr>
							<?
						}?>
					</tbody>
				</table>
			<?}else{?>
				<div class="panel-body">
					<h4>Список заявок пуст.</h4>
				</div>
			<?}?>
			</div>
			</div>
			<div class="row">
				<div class="ta-c">
				<?php
					echo LinkPager::widget([
					'pagination' => $pagenator,
					'registerLinkTags' => true
				]);
				?>
				</div>
			</div>
</div>			
		</div>
	</div>
</div>