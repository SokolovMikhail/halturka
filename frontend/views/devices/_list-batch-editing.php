<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
?>
<div class="col-xs-12">
	<div class="dsp-n alert alert-info mb-10 mt-10" data-batch-update-settings>
		<div class="row">
			<div class="col-xs-12">
				<h4>Массовое редактирование техники</h4>
				<div class="check-box-list">
					<?= $form
						->field($result['model'], 'settingsList')
						->checkBoxList(ArrayHelper::map($result['model']::$settings, 'id', 'title'), ['data-batch-settings-list'=>'',])
						->label(false)?>
				</div>
				<hr class="mb-30 dsp-n" data-batch-setting-additional>
			</div>
			
			<div class="col-xs-4 dsp-n" data-batch-setting-access-control>
				<?= $this->render('_form_access-control', [
					'form' => $form, 
					'model' => $result['model'],
				])?>
			</div>
			
			<div class="col-xs-4 dsp-n" data-batch-setting-timeout>
				<?= $this->render('_form_timeout', [
					'form' => $form, 
					'model' => $result['model'],
				])?>
			</div>
			
			<div class="dsp-n" data-batch-setting-lock-setting>
				<?= $this->render('_form_lock-setting', [
					'form' => $form, 
					'model' => $result['model'],
					'typesCrashes' => $result['crashes_types'],
				])?>
			</div>
			
			<div class="col-xs-12 dsp-n"  data-batch-setting-additional>
				<?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
			</div>
			
		</div>
	</div>
</div>