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
$this->title = 'Редактирование вопроса';
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel">
			<div class="panel-body">
				<? $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
				<div class="row">
					<div class="col-xs-6">
						<?= $form->field($model, 'order_number')->textInput(['type' => 'number'])?>
						<?= $form->field($model, 'text_native')->textArea(['maxlength' => 4096, 'rows' => 4]) ?>
						<?= $form->field($model, 'text_doc')->textArea(['maxlength' => 4096, 'rows' => 4]) ?>
						<?= $form->field($model, 'type')->dropDownList(Question::getQuestionTypes()) ?>
						<div class="form-group">
							<?= Html::submitButton('Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
						</div>
					</div>
					<div class="col-xs-6">
						<label class="control-label">Ответы</label>	
						
						<div class="list-group">
							<?foreach($answers as $item){?>
								<button type="button" class="list-group-item" data-answer-row="<?= $item['id']?>"><a href="/answer/update/?id=<?= $item['id']?>"><?= $item['text_native']?></a><i class="fa fa-times clickable text-red float-right" aria-hidden="true" data-toggle="tooltip" title="Удалить" data-answer-delete="<?= $item['id']?>"></i></button>
							<?}?>
						</div>
						
						<a href="/answer/create/?questionId=<?= $model->id?>" class="btn btn-primary mb-10" data-stage-topic-add="">Добавить ответ</a>
					</div>

				<?= Html::csrfMetaTags() ?>
				<? ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>
