<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->params['header_links'] = [
	[
		'icon'	=> 'fa-arrow-circle-left',
		'title'	=> 'Назад к списку отделов',
		'link'	=> '/storages/#storage-'.$model->id,
	],
	[
		'icon'	=> 'fa-plus-circle',
		'title'	=> 'Добавить отдел',
		'link'	=> '/storages/create/',
	],
];
?>
<div class="storages-form" data-ajax-container-1>
    <? 
	$form = ActiveForm::begin([
		'action' => ['storages/'.($model->isNewRecord ? 'create/' : 'update/'.$model->id)],
		'options' => [
			'data-ajax-url'=>'/storages/reload-form-parameters/'.($model->isNewRecord ? 0 : $model->id).'/'
			]
	]); 
	?>
	<div class="row">
		<div class="col-xs-12">
			<h4>Основные настройки</h4>
		</div>
		<div class="col-xs-12">
			<div class="row">
				<div class="col-xs-3">
					<?= $form->field($model, 'name')->textInput(['maxlength' => 128]) ?>
				</div>
				<div class="col-xs-3">
					<?= $form->field($model, 'description')->textInput(['maxlength' => 128]) ?>
				</div>
				<div class="col-xs-3">
					<?= $form->field($model, 'type')->dropDownList($model::$storageType, ['data-ajax-control'=>'1']); ?>
				</div>
				<div class="col-xs-3">
					<?= $form->field($model, 'parent_id')->dropDownList($parents['output'], ['options' => $parents['options'], 'class' => 'form-control selectpicker custom-select-search-default', 'data-live-search' => 'true']); ?>
				</div>
			</div>
		</div>
		<div class="col-xs-12">
			<div class="row">		
				<? if($model->type == 1){?>
				<div class="col-xs-3">
					<?= $form->field($model, 'time_zone')->dropDownList($timezoneList); ?>
				</div>
				<div class="col-xs-3">
					<?= $form->field($model, 'day_start')->dropDownList($model::$dayStartHourArr)?>
				</div>
				<? }?>
				<div class="col-xs-3">
				<?= $form->field($model, 'active')->dropDownList(['1' => 'Да', '0' => 'Нет']); ?>
				</div>
				<div class="col-xs-3">
					<?= $form->field($model, 'sorting')->textInput(['maxlength' => 128])?>
				</div>
			</div>
		</div>
		<div class="col-xs-12 tree-ul">
			<input type="hidden" name="Storages[storageUsersArray][]" value="">
			<?= $users['output']?>
		</div>
		<div class="col-xs-12 mt-20">
			<div class="form-group">
				<?= Html::submitButton('Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'data-form-save' => '']) ?>
				<?if(!$model->isNewRecord){?>
					<a href="/storages/delete/<?=$model->id?>/" class="btn btn-danger" data-method="post">Удалить</a>
				<?}?>
			</div>
		</div>
	</div>
	<?= Html::csrfMetaTags() ?>
    <? ActiveForm::end(); ?>
</div>