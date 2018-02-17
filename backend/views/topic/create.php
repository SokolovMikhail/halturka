<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

$this->params['header_links'] = [
	[
		'icon'	=> 'fa-arrow-circle-left',
		'title'	=> 'Назад к списку тем',
		'link'	=> '/',
	],
	[
		'icon'	=> 'fa-plus-circle',
		'title'	=> 'Добавить тему',
		'link'	=> '/topic/create/',
	],
];
$this->title = 'Создать новую тему';
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<div class="panel-body">
				<? $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
				<div class="row">
					<div class="col-xs-12">
						<h4>Новая тема</h4>
					</div>
					<div class="col-xs-4">
						<?= $form->field($model, 'name')->textInput(['maxlength' => 512]) ?>
						<?= $form->field($model, 'description')->textArea(['maxlength' => 2048, 'rows' => 4]) ?>
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
