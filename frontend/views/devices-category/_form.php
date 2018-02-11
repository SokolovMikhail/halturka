<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\widgets\FilterItemsCheckListWidget;

$icons = [
	[
		'id'	=> 2,
		'name'	=> 'Погрузчик',
	],
	[
		'id'	=> 3,
		'name'	=> 'Ричтрак',
	],
	[
		'id'	=> 1,
		'name'	=> 'Электротележка',
	],
	[
		'id'	=> 6,
		'name'	=> 'Ножничный подъемник',
	],
	[
		'id'	=> 4,
		'name'	=> 'Узкопроходной штабелер',
	],
	[
		'id'	=> 8,
		'name'	=> 'Комплектовщик верхнего уровня',
	],
	[
		'id'	=> 9,
		'name'	=> 'Комплектовщик верхнего уровня',
	],
	[
		'id'	=> 7,
		'name'	=> 'Дизельный погрузчик',
	],
	[
		'id'	=> 5,
		'name'	=> 'Прочая техника',
	],
];
?>
<div class="devices-form"> 
    <?php $form = ActiveForm::begin(); ?>
	<div class="row">
		<div class="col-xs-4">
			<?= $form->field($model, 'name')->textInput(['maxlength'=>255, 'readonly' => true]) ?>
		</div>
		<div class="col-xs-4">
			<?= $form->field($model, 'sorting')->textInput([
				'class'				=>'slider-input',
				'data-slider-min'	=>'0',
				'data-slider-max'	=>'100',
				'data-slider-value'	=> $model->sorting,
			]) ?>
		</div>
		<div class="col-xs-12 btn-group mb-20" data-toggle="buttons">	
			<div class="mb-10">
				<b>Иконка для типа техники</b>
			</div>
			<?foreach($icons as $icon){?>
			<label class="btn device-cat-btn <?= $icon['id']==$model->icon ? 'active' : ''?>" title="<?=$icon['name']?>">
				<input type="radio" name="DevicesCategory[icon]" id="icon-<?=$icon['id']?>" autocomplete="off" value="<?=$icon['id']?>" <?= $icon['id']==$model->icon ? 'checked' : ''?>>
				<span class="fontello icon-device-cat-<?=$icon['id']?>"></span>
			</label>
			<?}?>
		</div>
		<div class="col-xs-12">
			<h2>KPI по загрузке</h2>
		</div>
		<div class="col-xs-4">
			<?= $form->field($model, 'engine_kpi')->textInput([
				'class'					=> 'slider-input-float',
				'data-slider-min'		=> '0',
				'data-slider-max'		=> '12',
				'data-slider-step'		=> '0.1',
				'data-slider-value'		=> $model->engine_kpi,
			]) ?>
		</div>
		<div class="col-xs-4">
			<?= $form->field($model, 'active_kpi')->textInput([
				'class'=>'slider-input-float',
				'data-slider-min'		=>'0',
				'data-slider-max'		=>'12',
				'data-slider-step'		=>'0.1',
				'data-slider-value'		=> $model->active_kpi,
			]) ?>
		</div>
		<div class="col-xs-4">
			<?= $form->field($model, 'work_kpi')->textInput([
				'class'=>'slider-input-float',
				'data-slider-min'		=>'0',
				'data-slider-max'		=>'12',
				'data-slider-step'		=>'0.1',
				'data-slider-value'		=> $model->work_kpi,
			]) ?>
		</div>
		<div class="col-xs-12 mt-20">
			<div class="form-group">
				<?= Html::submitButton('Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
			</div>
		</div>
	</div>	
</div>
<?php ActiveForm::end(); ?>
