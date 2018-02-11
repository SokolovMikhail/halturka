<?
use yii\helpers\Html;
?>
<table class="table table-hover items-table mb-0">
	<tbody>
		<tr class="">
			<td></td>
			<td>
				<?= Html::checkbox(
					'Devices[devicesList][]', 
					'', 
					['data-batch-all-devices' => '',]
				);?>
			</td>
			<td class="item-name">Название техники</td>
			<td class="ta-c">Доступ</td>
			<td class="ta-c">Категория</td>
			<? foreach($result['crashes_types'] as $typeId=>$type){?>
			<td class="ta-c"><?= $type['name']?></td>
			<? }?>
			<td class="ta-c">Таймаут отключения<br/>зажигания</td>
			<td class="ta-c">Отдел</td>
			<? if(isset(Yii::$app->modules['speedcontrol'])){?>
			<td class="ta-c">Макс. скорость, км/ч</td>
			<? }?>
			<td></td>
		</tr>

		<? $i=0?>
		<? foreach ($result['items'] as $item){?>
		<tr id="item-<?= $item['id']?>" <?= $item['conservation'] ? 'class="light-grey-bg" title="Консервация/резерв"' : ''?>>
			
			<td>
				<span class="badge <?= $item['active'] ? 'green-bg' : 'grey-bg'?>" title="<?= $item['active'] ? 'Учитывается в статистике' : 'Не учитывается в статистике'?>">
					<?= ++$i;?>
				</span>
			</td>
			
			<td>
				<?= Html::checkbox(
					'DevicesBatchEditingForm[devicesList][]', 
					'', 
					[
						'value' => $item['id'],
						'data-batch-device-id' => '',
					]
				);?>
			</td>
			
			<td class="item-name">
				<a href="/devices/update/<?=$item['id']?>/"  <?= !$item['terminal_state'] ? 'class="text-red" title="Терминал отсутствует на СУ"' : 'title="Редактировать"' ?>>
					<?=$item['name']?>	
				</a>
			</td>
			
			<td class="ta-c">
				<span 
					class="fa <?= $result['model']::$accessMode[$item['access_control']]['icon']?> text-<?= $result['model']::$accessMode[$item['access_control']]['color']?>" 
					title="<?= $result['model']::$accessMode[$item['access_control']]['title']?>"></span>
			</td>
			
			<td class="ta-c">
				<?= isset($result['categories_list'][$item['category']]) ? $result['categories_list'][$item['category']]['name'] : ''?>
			</td>
			
			<? foreach($result['crashes_types'] as $typeId=>$type){?>
			<td class="ta-c">
				<?= $item['lock_limit_'.$typeId]?>%
				<span 
					class="fontello <?= $result['model']::$lockTypes[$item['lock_type_'.$typeId]]['class']?>" 
					title="<?= $result['model']::$lockTypes[$item['lock_type_'.$typeId]]['title']?>"
				>												
				</span>
				<?
				if($item['lock_timeout_'.$typeId]>0){
					$clockColor = 'text-orange';
					$clockTitle = 'Блокировка снимается через '.$item['lock_timeout_'.$typeId].' мин.';
				}
				else{
					$clockColor = 'text-grey';
					$clockTitle = 'Блокировка снимается только администратором';
				}
				?>
				<span class="fa fa-clock-o <?= $clockColor?>" title="<?= $clockTitle?>"></span>
			</td>
			<? }?>
			
			<td class="ta-c">
				<?= $item['timeout']?> мин.
			</td>


			<td class="ta-c">
				<?= Yii::$app->storagesData->getAvailableStorageName($item['storage_id'])?>
			</td>
			
			<? if(isset(Yii::$app->modules['speedcontrol'])){?>
			<td class="ta-c"><?= $item['speed_limit'] ? $item['speed_limit'].' ('.$result['model']::$speedLockTypes[$item['speed_limit_type']].')' : '-'?></td>
			<? }?>
			
			<td>
			<?if(in_array('manageDevices', Yii::$app->config->params['user']['roles'])){?>
				<?= Html::a('', ['devices/delete/'.$item['id']], [
				    'data' => ['method' => 'post',],
				    'class' => 'fa fa-trash text-red',
				])?>
			<?}?>
			</td>
			
		</tr>
		<? }?>
	</tbody>
</table>