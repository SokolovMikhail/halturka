<?
$this->title = 'Редактировать отдел: '. $model->name;
$this->params['main_nav_current'] = 'storages';


// echo'<pre>';
// print_r($storages['available']);
// echo'</pre>';
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
					'model'		=> $model,
					'users' 	=> $users,
					'parents' 	=> $parents,
					'timezoneList' 	=> $timezoneList,
				]) ?>
			</div>
		</div>
	</div>
</div>
<a class="float clickable" data-float-button-target="data-form-save" data-toggle="tooltip" data-placement="left" title="Сохранить">
	<i class="fa fa-floppy-o my-float "></i>
</a>
