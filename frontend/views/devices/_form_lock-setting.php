<?
use yii\helpers\ArrayHelper;
?>
<div class="panel-body">
	<div class="row">
		<? foreach($typesCrashes as $i=>$type){?>
		<div class="col-xs-12">
			<h4><?=$type['name']?></h4>
		</div>
		<div class="col-xs-4">
			<?= $form->field($model, 'lock_type_'.$i)->dropDownList(ArrayHelper::map($model::$lockTypes, 'id', 'title'));?>
		</div>
		<div class="col-xs-4">
			<?= $form->field($model, 'lock_limit_'.$i)->textInput([
				'class'				=> 'slider-input',
				'data-slider-min'	=> '0',
				'data-slider-max'	=> '100',
				'data-slider-value'	=> $model->{'lock_limit_'.$i},
			])?>
		</div>
		<div class="col-xs-4">
			<?= $form->field($model, 'lock_timeout_'.$i)->textInput([
				'class'				=> 'slider-input',
				'data-slider-min'	=> '0',
				'data-slider-max'	=> '250',
				'data-slider-value'	=> $model->{'lock_timeout_'.$i},
			])->hint('(0 - не снимать блокировку по таймауту)')?>
		</div>
		<? }?>
	</div>
</div>