<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use frontend\modules\admin\assets\AdminAsset;

AdminAsset::register($this);

$this->params['main_nav_current'] = 'admin/equipment';
$this->params['header_links'] = [
	[
		'icon'	=> 'fa-arrow-circle-left',
		'title'	=> 'Назад к списку оборудования',
		'link'	=> '/admin/equipment/',
	],
	[
		'icon'	=> 'fa-plus-circle',
		'title'	=> 'Добавить оборудование',
		'link'	=> '/admin/equipment/create/',
	],
];
$this->title = 'Редактирование оборудования';
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<div class="panel-body">
				<? $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
				<div class="row">
					<div class="col-xs-12">
						<h4><?= $model['name']?></h4>
					</div>
					<div class="col-xs-4">
						<?= $form->field($model, 'name')->textInput(['maxlength' => 128]) ?>
						<?= $form->field($uploadForm, 'imageFile')->fileInput() ?>
						<?if($image){?>
							<a class="example-image-link" href="/rest/images/get/?id=<?=$image['id']?>" data-lightbox="example-set-1"><img src ="/rest/images/get/?id=<?=$image['id']?>"class="img-thumbnail  mb-10"></a>
						<?}?>						
						<?= $form->field($model, 'type')->dropDownList($types)?>
						<?= $form->field($model, 'sorting')->textInput(['maxlength' => 128]) ?>
						<?= $form->field($model, 'external_id')->textInput(['maxlength' => 128]) ?>
						<div class="form-group">
							<?= Html::submitButton('Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
							<a href="/admin/equipment/delete/?id=<?=$model['id']?>" data-confirm="Вы действительно хотите удалить данное оборудование?" data-method="post" data-pjax="0"><button type="button" class="btn btn-danger">Удалить</button></a>
						</div>
					</div>


				<?= Html::csrfMetaTags() ?>
				<? ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>
