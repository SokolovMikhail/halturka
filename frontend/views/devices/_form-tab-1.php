<?
use frontend\models\Storages;
use frontend\models\DevicesCategory;
use yii\helpers\ArrayHelper;
?>
<div class="panel-body">
	<div class="row">
		<div class="col-xs-6">
			<? if (
				!$model->isNewRecord
				&& in_array('manageDevices', Yii::$app->config->params['user']['roles'])
			) { ?>
			<?= $form->field($model, 'id')->textInput(['maxlength' => 11, 'readonly' => true]) ?>
			<? } ?>
			
			<?= $form->field($model, 'name')->textInput(['maxlength' => 128]) ?>
			
			<?= $this->render('_form_access-control', ['form' => $form, 'model' => $model])?>
			
			<?= $this->render('_form_timeout', ['form' => $form, 'model' => $model])?>
			
			<? if(isset(Yii::$app->modules['speedcontrol'])){?>
			<h4 class="mt-30">Настройки скорости</h4>
			<?= $form->field($model, 'speed_limit')->textInput([
				'class'				=> 'slider-input',
				'data-slider-min'	=> '0',
				'data-slider-max'	=> '100',
				'data-slider-value'	=> $model->speed_limit ? $model->speed_limit : 0,
			])->hint('(0 -  не ограничивать скорость)');?>
			<?= $form->field($model, 'speed_limit_type')->dropDownList(['0' => 'Без воздействия', '1' => 'Звуковое оповещение']); ?>
			<? }?>
		</div>
		<div class="col-xs-6">
			<?= $form->field($model, 'storage_id')->dropDownList(ArrayHelper::map(Storages::avaibleStoragesTree(), 'id', 'name')); ?>
			<?= $form->field($model, 'category')->dropDownList(ArrayHelper::map(DevicesCategory::find()->all(), 'id', 'name')); ?>
			<?= $form->field($model, 'active')->dropDownList(['1' => 'Да', '0' => 'Нет']); ?>
			<?= $form->field($model, 'conservation')->dropDownList(['0' => 'Отключен', '1' => 'Включен'])->hint('В режиме "Консервация" доступ к технике имеют только механики, а время в консервации не учитывается в статистике');?>
			<?= $form->field($model, 'modified')->hiddenInput(['value'=>1])->label(false);?>

			<? if (in_array('manageDevices', Yii::$app->config->params['user']['roles'])) { ?>
			<h4 class="mt-30">Внешние сенсоры</h4>
			<?= $form->field($model, 'speed_sensor')->dropDownList(['1' => 'Да', '0' => 'Нет']); ?>
			<?= $form->field($model, 'fork_sensor')->dropDownList(['1' => 'Да', '0' => 'Нет']); ?>
			<? } ?>
		</div>
	</div>
</div>