<?
$this->title = 'Добавить тип техники';
$this->params['main_nav_current'] = 'devices-category';
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">			
			<div class="panel-body">
				<?= $this->render('_form', [
					'model' => $model,
				]) ?>
			</div>
		</div>
	</div>
</div>
