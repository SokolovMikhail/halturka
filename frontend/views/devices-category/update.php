<?
use frontend\widgets\Alert;
$this->title = 'Редактировать тип техники: '. $model->name;
$this->params['main_nav_current'] = 'devices-category';
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">	
			<div class="panel-body">
				<?= Alert::widget()?>
				<?= $this->render('_form', [
					'model' => $model,
				]) ?>
			</div>
		</div>
	</div>
</div>

