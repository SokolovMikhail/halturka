<?
use yii\helpers\Html;
use yii\widgets\ActiveForm; 

extract($result);
?>
<div class="col-xs-6">
<? $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);?>
	<?= $form->field($model, 'username') ?>
	<?= $form->field($model, 'name') ?>
	<?= $form->field($model, 'email') ?>
	<?= $form->field($model, 'phone')->textInput(['data-format' => '+7 (ddd) ddd-dddd', 'class' => 'form-control bfh-phone']) ?>
	<?= $form->field($model, 'password')->passwordInput() ?>
	<?= $form->field($model, 'passwordRepeat')->passwordInput() ?>
	<?= $form->field($model, 'storage_id')->dropDownList($rootStorages); ?>
	<?= $form->field($model, 'mainRole')->dropDownList($roles); ?>
	<div class=" check-box-list">
	<?= $form->field($model, 'permissions')->checkBoxList($permissions); ?>
	</div>
</div>
<div class="col-xs-6">
	<div class="form-group">	
		<h4>Доступные отделы</h4>
		<div class="tree-ul">
			<?= $storagesTree['output']?>
		</div>
	</div>
</div>
<div class="col-xs-12">
	<div class="form-group">
		<?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'signup-button', 'data-user-save' => '']) ?>
		<? ActiveForm::end(); ?>
	</div>
</div>
<a class="float clickable" data-float-button-target="data-user-save" data-toggle="tooltip" data-placement="left" title="Сохранить настройки">
	<i class="fa fa-floppy-o my-float "></i>
</a>