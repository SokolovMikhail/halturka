<?
use yii\helpers\Html;
?>
<?foreach($equipmentList as $item){?>
	<div class="" data-all-harnesses='<?=$harness?>'>
		<div class="row mb-10" data-ajax-form-harness-row> 
		<div class="col-md-4">
		<?= Html::dropDownList(
			'TypeFilter', $item['type'], 
			$selectItems['types'], 
			['class'=>'type-select form-control', 'data-ajax-form-harness-type'=> '']
		);?>
		</div>
		<div class="col-md-5">
		<?= Html::dropDownList(
			'NameFilter', $item['id'], 
			$harnessList[$item['type']], 
			['class'=>'name-select form-control', 'data-ajax-form-harness-name'=> '']
		);?>										
		</div>
		<div class="col-md-2">
			<div class="form-group mb-0">
				<input class="form-control mb-0" type="number" id="brands-counts" name="BrandsConfig[counts][]" value="1" disabled>
			</div>
		</div>
		<span class="fontello icon-cancel icon_delete-harness text-red" title="Удалить жгут" data-delete-harness=""></span>
		<input type="hidden" id="brands-harnesses" data-harness-id="" name="BrandsConfig[harnesses][]" value="<?= $item['id']?>">
		</div>
	</div>
<?}?>