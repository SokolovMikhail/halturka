<?
$this->title = 'Добавить оператора';
$this->params['main_nav_current'] = 'operators';
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<div class="panel-body">
				<?= $this->render('_form', [
					'model'			=> $model,
					'formParams'	=> $formParams,
				]) ?>
			</div>
		</div>
	</div>
</div>