<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title = 'Настройки KPI';
$this->params['main_nav_current'] = 'settings/kpi/';

?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<div class="panel">
				<div class="panel-body">
					<div class="row">
						<?php $form = ActiveForm::begin(); ?>
							<div class="col-xs-12">
								<h2 class="page-header">KPI по простоям (движение)</h2>
							</div>
							<div class="col-xs-4">
								<h4>Малые простои</h4>
								<!--
								<?= $form->field($model, 'active_idle_kpi_1')->textInput([
									'class'=>'slider-input',
									'data-slider-min'		=>'0',
									'data-slider-max'		=>'10',
									'data-slider-value'		=> $model->active_idle_kpi_1,
								]) ?>
								-->
								<?= $form->field($model, 'active_idle_kpi_timeout_1')->textInput([
									'class'=>'slider-input',
									'data-slider-min'		=>'0',
									'data-slider-max'		=>'180',
									'data-slider-value'		=> $model->active_idle_kpi_timeout_1,
								]) ?>
							</div>
							<div class="col-xs-4">
								<h4>Средние простои</h4>
								<!--
								<?= $form->field($model, 'active_idle_kpi_2')->textInput([
									'class'=>'slider-input',
									'data-slider-min'		=>'0',
									'data-slider-max'		=>'10',
									'data-slider-value'		=> $model->active_idle_kpi_2,
								]) ?>
								-->
								<?= $form->field($model, 'active_idle_kpi_timeout_2')->textInput([
									'class'=>'slider-input',
									'data-slider-min'		=>'0',
									'data-slider-max'		=>'180',
									'data-slider-value'		=> $model->active_idle_kpi_timeout_2,
								]) ?>
							</div>
							<div class="col-xs-4">
								<h4>Крупные простои</h4>
								<!--
								<?= $form->field($model, 'active_idle_kpi_3')->textInput([
									'class'=>'slider-input',
									'data-slider-min'		=>'0',
									'data-slider-max'		=>'10',
									'data-slider-value'		=> $model->active_idle_kpi_3,
								]) ?>
								-->
								<?= $form->field($model, 'active_idle_kpi_timeout_3')->textInput([
									'class'=>'slider-input',
									'data-slider-min'		=>'0',
									'data-slider-max'		=>'180',
									'data-slider-value'		=> $model->active_idle_kpi_timeout_3,
								]) ?>
							</div>
							<!--div class="col-xs-12">
								<h2 class="page-header">KPI по простоям (работа вилами)</h2>
							</div>
							<div class="col-xs-4">
								<h4>Малые простои</h4>
								<?= $form->field($model, 'work_idle_kpi_1')->textInput([
									'class'=>'slider-input',
									'data-slider-min'		=>'0',
									'data-slider-max'		=>'10',
									'data-slider-value'		=> $model->work_idle_kpi_1,
								]) ?>
								<?= $form->field($model, 'work_idle_kpi_timeout_1')->textInput([
									'class'=>'slider-input',
									'data-slider-min'		=>'0',
									'data-slider-max'		=>'180',
									'data-slider-value'		=> $model->work_idle_kpi_timeout_1,
								]) ?>
							</div>
							<div class="col-xs-4">
								<h4>Средние простои</h4>
								<?= $form->field($model, 'work_idle_kpi_2')->textInput([
									'class'=>'slider-input',
									'data-slider-min'		=>'0',
									'data-slider-max'		=>'10',
									'data-slider-value'		=> $model->work_idle_kpi_2,
								]) ?>
								<?= $form->field($model, 'work_idle_kpi_timeout_2')->textInput([
									'class'=>'slider-input',
									'data-slider-min'		=>'0',
									'data-slider-max'		=>'180',
									'data-slider-value'		=> $model->work_idle_kpi_timeout_2,
								]) ?>
							</div>
							<div class="col-xs-4">
								<h4>Крупные простои</h4>
								<?= $form->field($model, 'work_idle_kpi_3')->textInput([
									'class'=>'slider-input',
									'data-slider-min'		=>'0',
									'data-slider-max'		=>'10',
									'data-slider-value'		=> $model->work_idle_kpi_3,
								]) ?>
								<?= $form->field($model, 'work_idle_kpi_timeout_3')->textInput([
									'class'=>'slider-input',
									'data-slider-min'		=>'0',
									'data-slider-max'		=>'180',
									'data-slider-value'		=> $model->work_idle_kpi_timeout_3,
								]) ?>
							</div-->
							<div class="col-xs-12">
								<h2 class="page-header">KPI по движению без работы</h2>
							</div>
							<div class="col-xs-4">
								<h4>Малые простои</h4>
								<!--
								<?= $form->field($model, 'empty_active_kpi_1')->textInput([
									'class'=>'slider-input',
									'data-slider-min'		=>'0',
									'data-slider-max'		=>'10',
									'data-slider-value'		=> $model->empty_active_kpi_1,
								]) ?>
								-->
								<?= $form->field($model, 'empty_active_kpi_timeout_1')->textInput([
									'class'=>'slider-input',
									'data-slider-min'		=>'0',
									'data-slider-max'		=>'180',
									'data-slider-value'		=> $model->empty_active_kpi_timeout_1,
								]) ?>
							</div>
							<div class="col-xs-4">
								<h4>Средние простои</h4>
								<!--
								<?= $form->field($model, 'empty_active_kpi_2')->textInput([
									'class'=>'slider-input',
									'data-slider-min'		=>'0',
									'data-slider-max'		=>'10',
									'data-slider-value'		=> $model->empty_active_kpi_2,
								]) ?>
								-->
								<?= $form->field($model, 'empty_active_kpi_timeout_2')->textInput([
									'class'=>'slider-input',
									'data-slider-min'		=>'0',
									'data-slider-max'		=>'180',
									'data-slider-value'		=> $model->empty_active_kpi_timeout_2,
								]) ?>
							</div>
							<div class="col-xs-4">
								<h4>Крупные простои</h4>
								<!--
								<?= $form->field($model, 'empty_active_kpi_3')->textInput([
									'class'=>'slider-input',
									'data-slider-min'		=>'0',
									'data-slider-max'		=>'10',
									'data-slider-value'		=> $model->empty_active_kpi_3,
								]) ?>
								-->
								<?= $form->field($model, 'empty_active_kpi_timeout_3')->textInput([
									'class'=>'slider-input',
									'data-slider-min'		=>'0',
									'data-slider-max'		=>'180',
									'data-slider-value'		=> $model->empty_active_kpi_timeout_3,
								]) ?>
							</div>
							<div class="col-xs-12 mt-20">
								<?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
							</div>
						<?php ActiveForm::end(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>