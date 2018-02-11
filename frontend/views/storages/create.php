<?
$this->title = 'Добавить отдел';
$this->params['main_nav_current'] = 'storages';
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<div class="panel-body">
				<?= $this->render('_form', [
					'model' 	=> $model,
					'users' 	=> $users,
					'parents' 	=> $parents,
					'timezoneList' 	=> $timezoneList,
				]) ?>
			</div>
		</div>
	</div>
</div>
