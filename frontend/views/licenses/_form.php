<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="devices-form"> 

    <?php $form = ActiveForm::begin(); ?>
	<div class="row">
		<div class="col-xs-6">
			<?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
			<?= $form->field($model, 'description')->textInput(['maxlength' => 255]) ?>
			<div class="form-group">
				<?= Html::submitButton('Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
				<?if(!$model->isNewRecord){?>
					<a href="/storages/delete/<?=$model->id?>" class="btn btn-danger" data-method="post" data-pjax="0">Удалить</a>
				<?}?>
			</div>
		</div>
		<div class="col-xs-6">
		</div>
	</div>
    <?php ActiveForm::end(); ?>

</div>
