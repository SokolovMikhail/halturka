<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1>Техновизор.Склад</h1>

    <p>Авторизуйтесь, чтобы получить доступ к системe</p>
	<br><br>
    <div class="row">
        <div class="col-lg-4">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                <?= $form->field($model, 'username')->textInput()->label('Логин') ?>
                <?= $form->field($model, 'password')->passwordInput()->label('Пароль') ?>
                <!--
				<div style="color:#999;margin:1em 0">
                    If you forgot your password you can <?= Html::a('reset it', ['site/request-password-reset']) ?>.
                </div>-->
				<br>
                <div class="form-group">
                    <?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
				
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
