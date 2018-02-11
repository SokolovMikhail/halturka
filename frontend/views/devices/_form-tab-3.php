<? extract($formParams);?>
<div class="panel-body">
	<div class="row">
		<div class="col-xs-4">
			<?= $form->field($model, 'license_id')->dropDownList($licenses,['data-pjax-control'=>'access-list', 'data-pjax-url'=>'/devices/get-access-list/'.$model->id.'/']); ?>
		</div>
		<div class="col-xs-4 <?= count($rootStorages)>1 ? '' : 'dsp-n'?>">
			<?= $form->field($model, 'rootStorage')->dropDownList($rootStorages,['data-pjax-control'=>'access-list', 'data-pjax-url'=>'/devices/get-access-list/'.$model->id.'/']); ?>
		</div>
		<?= $this->render('_form_access-list', [
			'model' => $model,
			'operatorsByStorage' => $operatorsByStorage,
		]);?>
	</div>
</div>