<?php
/* @var $this yii\web\View */
use yii\bootstrap\ActiveForm;

$this->title = 'Завершение';
?>
<div class="row">
	<div class="col-md-6 col-md-offset-3">
	 <div class="text-top">Ваш иск был составлен. <br> Введите в поле E-mail вашу электронную почту.</div>
	 
	</div>
</div>

<?php $form = ActiveForm::begin(['id' => 'topic-form']); ?>
<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<?= $form->field($model, 'email')->textInput(['maxlength' => 512]) ?>
	</div>
</div>
<div class="row">
	<div class ="col-md-6 col-md-offset-3 ta-c">
		<button type="submit" class="btn btn-primary btn-lg button-start">
		Отправить
		</button>
	</div>
</div>
<?php ActiveForm::end(); ?>
