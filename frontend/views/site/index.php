<?php
/* @var $this yii\web\View */
use yii\bootstrap\ActiveForm;

$this->title = 'Выбор темы';
?>
<div class="row"> 
	<div class="col-md-12">
		<div class="text-overtop">Создай свой документ сам</div>
	</div>	
</div>
<div class="row">
	<div class="col-md-8 col-md-offset-2">
	 <div class="text-top">Выберите категорию документов</div>
	</div>
</div>
<?php $form = ActiveForm::begin(['id' => 'topic-form']); ?>
<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<div class="form-group form-group-lg">			
			<select class="form-control selectpicker my-form-control" name="TopicForm[choice]" data-live-search="true">	
			<?foreach($topics as $item){?>
				<option value="<?= $item['id']?>"> <?= $item['name']?></option>									
			<?}?>
			</select>			
		</div>
</div>
<div class="row">
	<div class ="col-md-6 col-md-offset-3 ta-c">
		<button type="submit" class="btn btn-primary btn-lg button-start">
		Выбрать категорию
		</button>
	</div>
</div>
<?php ActiveForm::end(); ?>
<div class="row">
	<div class="col-md-6 col-md-offset-3 ta-c">
		<!-- Button trigger modal -->
		<button type="button" class="btn btn-primary btn-lg button-help" data-toggle="modal" data-target="#myModal">
		Не знаете как пользоваться сайтом?
		</button>		
	</div>
</div>


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Работа с сайтом</h4>
      </div>
      <div class="modal-body">
		<p><h3>1. Выберите нужный вам иск из выпадающего меню в верху</h3></p>
		
		<p><h3>2. Начните отвечать на вопросы</h3></p>
		
		<p><h3>3. Если вы выбралинеправильный иск,мы предложим вам перейти на более правильный для вас вариант иска</h3></p>
		
		<p><h3>4. Укажите свою электронную почту </h3></p>		
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>