<?
use frontend\adminModels\Licenses;
use yii\helpers\ArrayHelper;
use frontend\models\Brands;
use frontend\models\MainDevices;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use frontend\modules\admin\assets\AdminAsset;

AdminAsset::register($this);
$this->params['main_nav_current'] = 'admin/main-devices';
?>


<div class="row">
	<div class="col-xs-7">
				<? if(!isset($model)) {?>
				<?= $form->field($adminModel, 'order_id')->dropDownList($selectArray['orders'])?>
				<?}?>
			<div class="dsp-inline1">
			
			<div class="row mb-10">
			<div class="col-md-8">
				<b><p class="mb-5">Выберите марку и модель</p></b>
				<div class="row">				
				<div class="col-md-5">
				<?= Html::dropDownList(
					'MainDevices[brand]', $selectArray['currentBrand'], 
					$selectArray['brands'], 
					['class'=>'brand-select dropdown-style form-control', 'data-ajax-form-brand'=>'']
				);?>
				</div>
				<div class="col-md-7">
				<?= Html::dropDownList(
					'MainDevices[model]', $selectArray['currentModel'], 
					$selectArray['models'], 
					['class'=>'model-select dropdown-style form-control selectpicker1 custom-select-search1', 'data-ajax-form-model'=>'', 'data-live-search' => 'true']
				);?>
				</div>
				</div>
			</div>
			<div class="col-md-4">
			<?= $form->field($adminModel, 'year')->dropDownList(MainDevices::GetYears(), ['data-main-devices-year' => ''])?>
			</div>
			</div>
			<div class="row">
			<div class="col-md-12">
			<b><p class="">Выберите год и конфигурацию</p></b>
			</div>		
			<div class="col-md-3">
			<?= Html::dropDownList(
				'adminModelBrendFilter', $selectArray['currentYear'], 
				$selectArray['years'], 
				['class'=>'year-select dropdown-style form-control', 'data-ajax-form-year'=>'']
			);?>
			</div>
			<div class="col-md-9">
			<?= Html::dropDownList(
				'MainDevices[config_id]', $selectArray['currentSeries'], 
				$selectArray['series'], 
				['class'=>'year-select dropdown-style form-control', 'data-ajax-form-series'=>'']
			);?>
			</div>			
			</div>							
			</div>
			<? if(isset($brand)) {?>
			<div class="row mt-10 <?= $brand ? '' : 'model-link-hidden'?>" data-model-link="">
				<div class="col-md-12 page-title_links">
					<a href="/admin/brands/update/?id=<?= $brand ? $brand->id : ''?>" data-model-href=""><span class="fa fa-arrow-circle-right"></span>Перейти к модели</a>
				</div>
			</div>
			<?}?>
		<?= $form->field($adminModel, 'model_id')->hiddenInput()->label(false); ?>		
		<?= $form->field($adminModel, 'garage_number')->textInput(['maxlength' => 1024]) ?>
		<?= $form->field($adminModel, 'serial_number')->textInput(['maxlength' => 1024]) ?>			
		<?= $form->field($adminModel, 'configuration')->textArea(['maxlength' => 1024, 'rows' => 5]) ?>
		<? if (!isset($model)){?>
			<?if($adminModel->isNewRecord){?>
				<?$adminModel->active = 1;?>
				<?$adminModel->photo = 0;?>
			<?}?>
				<?= $form->field($adminModel, 'active')->checkbox(['value'=>1, 'uncheckValue'=>0]) ?>
				<?= $form->field($adminModel, 'photo')->checkbox() ?>
		<?}?>
		
		<? if(isset($model)) {?>
		<?= $form->field($adminModel, 'external_sensors')->textInput(['maxlength' => 1024]) ?>		
		<?= $form->field($adminModel, 'terminal')->textInput(['maxlength' => 1024]) ?>
		<?= $form->field($adminModel, 'terminal_version')->textInput(['maxlength' => 1024])?>
		<?}?>
	</div>
	<? if(isset($model)) {?>
	<div class="col-xs-5">
		<div class="panel">		
			<div class="panel-body" data-server-form>	
		<?= $form->field($adminModel, 'serial_number_terminal')->textInput(['readonly' => true]) ?>
		<?= $form->field($adminModel, 'ext_id')->textInput(['readonly' => true]) ?>
		<?= $form->field($adminModel, 'software_version')->textInput(['readonly' => true]) ?>
		<?= $form->field($adminModel, 'sim_id')->textInput(['readonly' => true]) ?>		
		<?= $form->field($adminModel, 'release_date')->textInput(['readonly' => true]) ?>
		<?= $form->field($adminModel, 'update_date')->textInput(['readonly' => true]) ?>
		<?if(!$model->isNewRecord){?>
		<button type="button" class="btn btn-success" data-get-server-devices>Получить данные с сервера</button>
		<?}else{?>
		<h4>Сохраните устройство, чтобы получить данные с сервера устройств</h4>
		<?}?>		
			</div>
		</div>		
	</div>
	<?}?>
</div>
<? if(isset($model)) {?>
<?$sdn = explode('.', $_SERVER['HTTP_HOST']);?>
<div class="row">
	<div class="col-md-12">
	<div class="col-md-12">
		<?= $form->field($uploadForm, 'imageFile[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>
		<div class="mt-20" style="height: 20px;">
		</div>
		<?if(isset($images)){?>
		<?foreach($images as $item){?>
			<div data-parent-wrap="" style="display: inline; position: relative;">
				<a class="example-image-link" href="/rest/images/get-image/?id=<?= $item->id?>" data-lightbox="example-set-0">
					<img src="/rest/images/get-image/?id=<?= $item->id?>" class="img-rounded mt-30" style="margin-left: 15px;" data-img-responsive="">
				</a>
				<span class="fa fa-window-close fancybox-close" title="Удалить изображение" data-main-device-delete="<?= $item->id?>"></span>	
			</div>
		<?}?>
		<?}?>
	</div>
	</div>
</div>
<?}?>

