<?
$this->title = 'Создать пользователя';
$this->params['main_nav_current'] = 'user';
?>
<?= $this->render('_form', ['model' => $model, 'result' => $result]) ?>			
			