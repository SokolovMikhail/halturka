<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

?>
<div class="row" data-service-wrap="">
	<div class="col-md-3">
		<?= Html::dropDownList(
			'ServiceOrder[types][]', '', 
			$services, 
			['class'=>'type-select form-control', 'data-service-select'=> '']
		);?>
	</div>
	<div class="col-md-1">
		<div class="form-group mb-0">
			<input class="form-control mb-0" type="number" name="ServiceOrder[amounts][]" value="0" data-service-amount="">			
			<input type="hidden" name="ServiceOrder[ids][]" data-service-id="" value="-1">
		</div>						
	</div>
	<div class="col-md-2">
		<div class="form-group mb-0">
			<input class="form-control mb-0" name="ServiceOrder[prices][]" value="0" data-service-price="">
		</div>	
	</div>
	<div class="col-md-2">
		<div class="form-group mb-0">
			<input class="form-control mb-0" name="ServiceOrder[totalPrices][]" value="0" data-service-totalPrices="">
		</div>		
	</div>
	<input type="hidden" class="form-control" name="ServiceOrder[owners][]" value="<?= $type?>">	
	<span class="fontello icon-cancel icon_delete-harness text-red" data-toggle="tooltip" data-placement="top" title="Удалить услугу" data-delete-service=""></span>
</div>
