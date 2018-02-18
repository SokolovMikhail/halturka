<?php
/* @var $this yii\web\View */
use yii\bootstrap\ActiveForm;

$this->title = '';
?>
<?php $form = ActiveForm::begin(['id' => 'topic-form']); ?>
<div class ="row">
	<div class ="col-md-6 col-md-offset-3">
		<div class="form-group form-group-lg">			
			<select class="form-control selectpicker my-form-control" name="TopicForm[choice]" data-live-search="true">	
			<?foreach($quiz as $quiz){?>
			<option value="<?= $quiz['id']?>"> <?= $quiz['name']?></option> 
			<?}?>
			</select>			
		</div>
	</div>
</div>
<div class="row">
	<div class ="col-md-6 col-md-offset-3 ta-c">
		<button type="submit" class="btn btn-primary btn-lg button-start">
		Перейти к опросу
		</button>
	</div>
</div>
<?php ActiveForm::end(); ?>
