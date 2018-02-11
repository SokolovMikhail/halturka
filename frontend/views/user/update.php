<?
$this->title = 'Редактировать пользователя: '.$model->username;
$this->params['main_nav_current'] = 'user';
?>
<?= $this->render('_form', ['model' => $model, 'result' => $result]) ?>