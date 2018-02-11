<?
use yii\helpers\Html;
use frontend\models\Equipment;
?>
<div class="mt-10" data-service-list="">
	<h2 class="mt-0">Услуги</h2>
	<button type="button" class="btn btn-success mb-10" data-service-add="new">Добавить услугу</button>
	<div class="row">
		<div class="col-md-3">
			<label class="control-label" for="brandsconfig-name">Название</label>
		</div>
		<div class="col-md-1">
			<label class="control-label" for="brandsconfig-name">Кол-во</label>
		</div>
		<div class="col-md-2">
			<label class="control-label" for="brandsconfig-name">Цена за ед.</label>
		</div>	
		<div class="col-md-2">
			<label class="control-label" for="brandsconfig-name">Сумма</label>
		</div>			
	</div>
	<?
	$amount = 0;
	$sum = 0;
	?>
	<?foreach($serviceOrdersList as $service){?>
	<?
	$amount += $service->amount;
	$sum += $service->total_price;
	?>
	<div class="row" data-service-wrap="">
		<div class="col-md-3">
			<?= Html::dropDownList(
				'ServiceOrder[types][]', $service->type, 
				$services, 
				['class'=>'type-select form-control', 'data-ajax-form-service'=> '']
			);?>
		</div>
		<div class="col-md-1">
			<div class="form-group mb-0">
				<input class="form-control mb-0" type="number" name="ServiceOrder[amounts][]" value="<?= $service->amount?>" data-service-amount="">
				<input type="hidden" name="ServiceOrder[ids][]" data-service-id="" value="<?= $service->id?>">
			</div>						
		</div>
		<div class="col-md-2">
			<div class="form-group mb-0">
				<input class="form-control mb-0" name="ServiceOrder[prices][]" value="<?= $service->price?>" data-service-price="">
			</div>	
		</div>
		<div class="col-md-2">
			<div class="form-group mb-0">
				<input class="form-control mb-0" name="ServiceOrder[totalPrices][]" value="<?= $service->total_price?>" data-service-totalPrices="">
			</div>		
		</div>
		<input type="hidden" class="form-control" name="ServiceOrder[owners][]" value="<?= $service->owner_type?>">
		<span class="fontello icon-cancel icon_delete-harness text-red" data-toggle="tooltip" data-placement="top" title="Удалить услугу" data-delete-service=""></span>
	</div>
	<?}?>
</div>
<div class="mb-20 " data-service-result="">
	<div class="row">
		<div class="col-md-3">
			<label class="control-label" >Итого</label>
		</div>
		<div class="col-md-1">
			<label class="control-label" data-amount-result=""><?= $amount?></label>
		</div>
		<div class="col-md-2">
			
		</div>	
		<div class="col-md-2">
			<label class="control-label" data-sum-result=""><?= $sum?></label>
		</div>			
	</div>	
</div>
<h3 class="">Техника</h3>
<a href="/admin/main-devices/create/?orderId=<?= $model->id?>"><button type="button" class="btn btn-success mb-10">Добавить технику</button></a>
<div class="row">
	<div class="col-md-12">
		<form data-filter-form>
			<label>
				<?= Html::checkbox('sort', $sort, ['data-order-devices-sort' => '']) ?>
				Отсортировать по алфавиту
			</label>
			<input type="hidden" name="id" value="<?= $model->id?>" date-order-id = ""/>
		</form>	
	</div>
</div>					
<? if(isset($result['items'][0]) && count($result['items'][0])){?>
	<div class="">
	<table class="table table-hover personal-task mb-0">
		<tbody>
			<tr class="personal-task_head">
				<td>№</td>
				<td>Модель</td>
				<td>Год</td>
				<td>Серийный номер</td>
				<td>Гаражный номер</td>
				<td style="max-width: 200px;">Примечание</td>
				<td class="ta-c">Действие</td>
				<td></td>
				<td></td>
			</tr>
			<? 
			$i=0;
			foreach ($result['items'][0] as $item){
				$i++;?>
				<tr id="item-<?= $item['id']?>">
					<td>
					<?=$i?>
					</td>
					<td>
						<a href="/admin/main-devices/update/?id=<?= $item['id']?>&orderId=<?= $model->id?>" class="<?= (isset($item['red']) || !isset($item['brands'])) ? 'text-red' : ''?>"><?= isset($item['brands']) ? $item['brands'] : 'Модель не выбрана'?></a>
					</td>
					<td>
					<?= $item['year']?>
					</td>								
					<td>
						<?= isset($item['serial_number']) ? $item['serial_number'] : 'Серийный номер отсутствует'?>
					</td>
					<td>
						<?= isset($item['garage_number']) ? $item['garage_number'] : 'Гаражный номер отсутствует'?>
					</td>
					<td class="word-break" style="max-width: 200px;">
						<?= isset($item['configuration']) ? $item['configuration'] : 'Нет примечания'?>
					</td>								
					<td class="ta-c">
						<a href="/admin/main-devices/create/?orderId=<?=$model->id?>&deviceId=<?=$item['id']?>"><span class="fa fa-files-o copy-icon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Копировать"></span></a>
						<a class="clickable" data-delete-device-order-name="<?= isset($item['brands']) ? $item['brands'] : 'Модель не выбрана'?>" data-delete-device-order="/admin/main-devices/delete/?orderId=<?=$model->id?>&deviceId=<?=$item['id']?>"><span class="fa fa-trash-o delete-icon" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Удалить"></span></a>
					</td>
					<td>
						<span class="<?= $item['active'] ? 'green-bg' : 'red-bg'?> device-status" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?= $item['active'] ? 'Учитывать оборудование' : 'Не учитывать оборудование'?>"></span>					
					</td>
					<td>
						<span class="<?= $item['photo'] ? 'green-clr' : 'red-clr'?> fa fa-file-image-o fa-1 clickable"
							aria-hidden="true" 
							data-toggle="tooltip" 
							data-placement="top" 
							title="" 
							data-original-title="<?= $item['photo'] ? 'Есть фото' : 'Нет фото'?>"
							data-device-id="<?= $item['id']?>"
							data-device-photo-status="<?= $item['photo']?>"
							>
						</span>
					</td>
				</tr>
				<?
			}?>
		</tbody>
	</table>
	</div>
<?}?>
<h3 class="mt-20">Оборудование</h3>	
<?if($equipmentArray['config']){?>
	<a href="/admin/configuration/update/?id=<?= $equipmentArray['config']?>"><button type="button" class="btn btn-success mb-10">Редактирование списка оборудования</button></a>
	<?if($equipmentArray['items']){?>
	<?$i = 0?>
	<table class="table table-hover personal-task mb-0">
		<tbody>
			<tr class="personal-task_head">
				<td style="width: 40px;">№</td>
				<td>Тип</td>
				<td>Название</td>
				<td>Количество</td>							
			</tr>
			<?foreach ($equipmentArray['items'] as $item){$i++;?>
				<tr>
					<td>
						<?=$i?>
					</td>				
					<td>
						<?= Equipment::getTypeList()[$item['type']]?>
					</td>
					<td>
						<?= $item['name']?>
					</td>
					<td>
						<?= $item['count']?>
					</td>									
				</tr>
			<?}?>
		</tbody>
	</table>
	<?}?>
<?}else{?>
	<a href="/admin/configuration/create/?brandId=<?= $model->id?>&ownerType=order_new"><button type="button" class="btn btn-primary mb-10">Редактирование списка оборудования</button></a>
<?}?>
<div class="row mt-10">
	<div class="col-md-12">
		<?= $form->field($model, 'comments_new_order')->textArea(['maxlength' => 2048, 'rows' => 5]) ?>
	</div>
</div>