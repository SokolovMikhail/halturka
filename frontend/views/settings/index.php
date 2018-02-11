<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title = 'Настройки приложения';
$this->params['main_nav_current'] = 'settings';
?>
<div class="row">
	<div class="col-xs-12">
			<div class="panel">
				<div class="panel-body">
					<div class="row">
						<?php $form = ActiveForm::begin(); ?>
						<div class="col-xs-12">
							<h4>Общие настройки</h4>
						</div>
						<div class="col-xs-6">							
							<?= $form->field($model, 'company_name')->textInput(['maxlength' => 255]) ?>
							<?= $form->field($model, 'useWorkStatistic')->dropDownList([0=>'Нет',1=>'Да'])?>
							<div class="form-group">
								<?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
							</div>
						</div>
						<div class="col-xs-6">
							<div class="form-group">
								<label class="control-label" for="time">Дата(Мск) окончания работ на сервере(закарывает доступ всем, кроме суперадмина)</label>
								<input type="text" class="filter-field ta-c data-range-picker" 
									name="OptionsForm[maintenance]" 
									data-daterangepicker
									data-start-date="<?= $model->maintenance->format('d/m/Y H:i')?>"
									data-use-time-picker="true"
									data-single-date-picker="true"
									data-show-ranges = "false"
								>
							</div>
						</div>
						<?php ActiveForm::end(); ?>
					</div>
				</div>
			</div>
	</div>
</div>