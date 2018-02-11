<?
$this->title = 'Редактировать технику: '. $model->name;
$this->params['main_nav_current'] = 'devices';
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<?= $this->render('_form', [
				'model' => $model, 
				'adminModel' => $adminModel, 
				'selectArray' => isset($selectArray) ? $selectArray : [],
				'formParams' => $formParams,
				'uploadForm' => $uploadForm,
				'images' => isset($images) ? $images : false,
			]) ?>
		</div>
	</div>
</div>

