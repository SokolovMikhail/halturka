<?
$this->title = 'Добавить категорию прав';
$this->params['main_nav_current'] = 'licenses';
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
