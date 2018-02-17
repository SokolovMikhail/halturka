<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\Question;

$this->params['header_links'] = [
	[
		'icon'	=> 'fa-arrow-circle-left',
		'title'	=> 'Назад к списку вопросов',
		'link'	=> '/question/index/?quizId='.$model->quiz_id,
	],
];
$this->title = 'Создать новый вопрос';
?>
<div class="row">
	<div class="col-md-6">
		<div class="panel">
			<div class="panel-body">
				<? $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
				<div class="row">
					<div class="col-xs-12">
						<?= $form->field($model, 'order_number')->textInput(['type' => 'number'])?>
						<?= $form->field($model, 'text_native')->textArea(['maxlength' => 4096, 'rows' => 4]) ?>
						<?= $form->field($model, 'text_doc')->textArea(['maxlength' => 4096, 'rows' => 4]) ?>
						<?= $form->field($model, 'type')->dropDownList(Question::getQuestionTypes()) ?>
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
