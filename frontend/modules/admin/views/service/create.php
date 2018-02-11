<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

$this->params['main_nav_current'] = 'admin/service';
$this->params['header_links'] = [
	[
		'icon'	=> 'fa-arrow-circle-left',
		'title'	=> 'Назад к списку услуг',
		'link'	=> '/admin/service/',
	],
];
$this->title = 'Создать нового клиента';
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<div class="panel-body">
				<? $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
				<div class="row">
					<div class="col-xs-12">
						<h4>Новая услуга</h4>
					</div>
					<div class="col-xs-4">
						<?= $form->field($model, 'name')->textInput(['maxlength' => 128]) ?>
						<?= $form->field($model, 'default_price')->textInput(['maxlength' => 128]) ?>
						<?= $form->field($model, 'sort')->textInput(['maxlength' => 128, 'type' => 'number']) ?>
						<div class="form-group">
							<?= Html::submitButton('Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
						</div>
					</div>


				<?= Html::csrfMetaTags() ?>
				<? ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>
