<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

$this->params['main_nav_current'] = 'admin/clients';
$this->params['header_links'] = [
	[
		'icon'	=> 'fa-arrow-circle-left',
		'title'	=> 'Назад к списку клиентов',
		'link'	=> '/admin/clients/',
	],
	[
		'icon'	=> 'fa-plus-circle',
		'title'	=> 'Добавить клиента',
		'link'	=> '/admin/clients/create/',
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
						<h4>Новый клиент</h4>
					</div>
					<div class="col-xs-4">
						<?= $form->field($model, 'name')->textInput(['maxlength' => 128]) ?>
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
