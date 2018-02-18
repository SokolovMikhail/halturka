<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

$this->params['header_links'] = [
	[
		'icon'	=> 'fa-arrow-circle-left',
		'title'	=> 'Назад к списку опросов',
		'link'	=> '/quiz/?topicId='.$model->topic_id,
	],
];
$this->title = 'Редактировать опрос';
?>
<div class="row">
	<div class="col-xs-6">
		<div class="panel">
			<div class="panel-body">
				<? $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
				<div class="row">
					<div class="col-xs-12">
						<h4><?= $model->name?></h4>
					</div>
					<div class="col-xs-12">
						<?= $form->field($model, 'name')->textInput(['maxlength' => 512]) ?>
						<?= $form->field($model, 'description')->textArea(['maxlength' => 2048, 'rows' => 4]) ?>
						<?= $form->field($model, 'template_name')->textInput(['maxlength' => 512, 'disabled' => true]) ?>
						<?= $form->field($uploadModel, 'imageFile')->fileInput() ?>
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
