<?
use yii\helpers\Html;

if(count($storagesSelect)>1){?>
<div class="<?= $containerClass ? $containerClass : 'col-xs-3'?>">
	<? if($form){?><form><? }?>
		<div class="dsp-inline">
			<b>Отдел:</b>
		</div>
		<div class="dsp-inline">
			<?= Html::dropDownList(
				'storage',
				$currentStorage, 
				$storagesSelect,
				['class'=>'selectpicker filter-field storage-select', 'data-page-reloader-form'=>'', 'data-storage-id' => '', 'data-live-search' => 'true']
			)?>
		</div>
	<? if($form){?></form><? }?>
</div>
<? }?>