<?
$this->title = 'Добавить технику';
$this->params['main_nav_current'] = 'devices';
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<div class="panel-body">
				<?= $this->render('_form', [
					'model'			=> $model, 
					'adminModel'	=> $adminModel, 
					'selectArray'	=> $selectArray,
					'formParams'	=> $formParams,
					'uploadForm'	=> $uploadForm,
					'images' => [],
				]) ?>
			</div>
		</div>
	</div>
</div>