<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use frontend\modules\admin\assets\AdminAsset;

AdminAsset::register($this);
$this->params['main_nav_current'] = 'admin/brands';
$this->params['header_links'] = [
	[
		'icon'	=> 'fa-arrow-circle-left',
		'title'	=> 'Назад к списку моделей',
		'link'	=> '/admin/brands/',
	],
	[
		'icon'	=> 'fa-plus-circle',
		'title'	=> 'Добавить модель',
		'link'	=> '/admin/brands/create/',
	],
];
$this->title = 'Создать новую модель';
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<div class="panel-body">
				<? $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
				<div class="row">
					<div class="col-xs-12">
						<h4>Новая модель</h4>
					</div>
					<div class="col-xs-4">
										
						<?= $form->field($model, 'brand')->textInput(['maxlength' => 128]) ?>
						<?= $form->field($model, 'model')->textInput(['maxlength' => 128]) ?>
						<?= $form->field($model, 'release_date')->textInput(['maxlength' => 128]) ?>
						<?= $form->field($model, 'pseudonym')->textArea(['maxlength' => 128, 'rows' => 5]) ?>
						<?//$form->field($model, 'series')->textInput(['maxlength' => 128]) ?>

						<div class="form-group">
							<?= Html::submitButton('Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
						</div>							
					</div>
					<div class="col-xs-4">
						<?= $form->field($model, 'voltage')->dropDownList($voltage)?>
						<?= $form->field($model, 'license')->dropDownList($license)?>						
						<?= $form->field($model, 'type')->dropDownList($type)?>	
						<?= $form->field($model, 'comments')->textArea(['maxlength' => 20000, 'rows' => 5]) ?>						
					</div>
					<div class="col-xs-4">
						<?= $form->field($uploadForm, 'imageFile')->fileInput() ?>	
					</div>
				
	
				<?= Html::csrfMetaTags() ?>
				<? ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>
