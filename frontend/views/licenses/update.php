<?
$this->title = 'Редактировать категорию прав: '. $model->name;
$this->params['main_nav_current'] = 'licenses';
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<div class="panel-body">
				<?if(Yii::$app->session->hasFlash('error')){?>
					<p class="bg-danger message">
						<?= Yii::$app->session->getFlash('error');?>
					</p>
				<?}?>
				<?if(Yii::$app->session->hasFlash('success')){?>
					<p class="bg-success message">
						<?= Yii::$app->session->getFlash('success');?>
					</p>
				<?}?>
				<?= $this->render('_form', [
					'model' => $model,
				]) ?>
			</div>
		</div>
	</div>
</div>

