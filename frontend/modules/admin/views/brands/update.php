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
	[
		'icon'	=> 'fa fa-files-o ',
		'title'	=> 'Добавить копированием',
		'link'	=> '/admin/brands/copy/?id='.$model->id,
	]	
];
$this->title = 'Редактирование модели';
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<div class="panel-body">
				<? $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
				<div class="row">
					<div class="col-xs-12">
						<h4><?= $model['brand']?></h4>
					</div>
					<div class="col-xs-4">
						
						<?= $form->field($model, 'brand')->textInput(['maxlength' => 128]) ?>
						<?= $form->field($model, 'model')->textInput(['maxlength' => 128]) ?>
						<?= $form->field($model, 'release_date')->textInput(['maxlength' => 128]) ?>
						<?= $form->field($model, 'pseudonym')->textArea(['maxlength' => 128, 'rows' => 5]) ?>
						<?//$form->field($model, 'series')->textInput(['maxlength' => 128]) ?>


						<div class="form-group mt-10">
							<?= Html::submitButton('Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
							<a href="/admin/brands/delete/?id=<?=$model['id']?>" data-confirm="Вы действительно хотите удалить данную модель?" data-method="post" data-pjax="0"><button type="button" class="btn btn-danger">Удалить</button></a>
						</div>
					</div>
					<div class="col-xs-4">
						<?= $form->field($model, 'voltage')->dropDownList($voltage)?>
						<?= $form->field($model, 'license')->dropDownList($license)?>
						<?= $form->field($model, 'type')->dropDownList($type)?>
						<?= $form->field($model, 'comments')->textArea(['maxlength' => 20000, 'rows' => 5]) ?>					
					</div>
					<div class="col-xs-4">
						<b><p style="font-size: 14px;">Конфигурации</p></b>
						<div class="row mb-20">
							<div class="col-xs-12">
								<a href="/admin/configuration/create/?brandId=<?=$model['id']?>" class=""><button type="button" class="btn btn-primary">Добавить конфигурацию</button></a>
							</div>
						</div>
						<?if (isset($result['configs'])){?>
							<b><p>Доступные конфигурации</p></b>
							<div class="row mb-20">
							<div class="col-xs-12">
																
							<?foreach($result['configs'] as $item){?>
								<div class="row" data-config-wrap="">
									<div class="col-xs-11">
									<a href="/admin/configuration/update/?id=<?=$item['id']?>" class="list-group-item"><?= $item['name']?>
									<?if($item['checked']){?>
										<span class="green-bg legend-config-small-circle" data-toggle="tooltip" data-placement="left" title="Проверено"></span>
									<?}else{?>
										<span class="red-bg legend-config-small-circle" data-toggle="tooltip" data-placement="left" title="Не проверено"></span>
									<?}?>
									</a>
									</div>
									<span class="fontello icon-cancel icon_delete-harness text-red" title="Удалить конфигурацию" data-delete-config="<?=$item['id']?>" data-delete-config-name="<?= $item['name']?>"></span>
								</div>
							<?}?>
															
						<?}?>					
						<b><p style="font-size: 14px;" class="mt-10">Изображение</p></b>
						<?= $form->field($uploadForm, 'imageFile')->fileInput() ?>
						<?if($image){?>
							<a class="example-image-link" href="/rest/images/get/?id=<?=$image['id']?>" data-lightbox="example-set-1"><img src ="/rest/images/get/?id=<?=$image['id']?>"class="img-thumbnail  mb-10"></a>
						<?}?>												
						<?// $form->field($model, 'relay')->dropDownList($relay)?>

					</div>						
				<?= Html::csrfMetaTags() ?>
				<? ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>
