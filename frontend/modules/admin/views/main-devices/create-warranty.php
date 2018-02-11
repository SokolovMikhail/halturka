<?
use frontend\adminModels\Licenses;
use yii\helpers\ArrayHelper;
use frontend\models\Brands;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\MainDevices;

use frontend\modules\admin\assets\AdminAsset;

AdminAsset::register($this);
$this->params['main_nav_current'] = 'admin/order';
$this->title = 'Создание';
$this->params['header_links'] = [
	[
		'icon'	=> 'fa-arrow-circle-left',
		'title'	=> 'Назад к заявке',
		'link'	=> '/admin/order/update/?id='.$backId . '#warranty',
	]	
];
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<div class="panel-body">		
			
			<div class="row">
			<?$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);?>
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
					<?= $form->field($adminModel, 'configuration')->textArea(['maxlength' => 1024, 'rows' => 5])->label('Проблема') ?>
					<?= $form->field($adminModel, 'warranty_result')->textArea(['maxlength' => 1024, 'rows' => 5]) ?>
					<?$adminModel->active = 1;?>
					<?$adminModel->photo = 0;?>
					<?= $form->field($adminModel, 'active')->checkbox(['value'=>1, 'uncheckValue'=>0]) ?>
					<?= $form->field($adminModel, 'photo')->checkbox() ?>
					
					<? if(isset($model)) {?>
					<?= $form->field($adminModel, 'external_sensors')->textInput(['maxlength' => 1024]) ?>		
					<?= $form->field($adminModel, 'terminal')->textInput(['maxlength' => 1024]) ?>
					<?= $form->field($adminModel, 'terminal_version')->textInput(['maxlength' => 1024])?>
					<?}?>
				<?= Html::submitButton('Сохранить', ['class' => $adminModel->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
								
				</div>
				<div class="col-xs-5">
					<div class="" data-form-wrap>
						<div class="" data-all-harnesses='<?=$harness?>'>							
						</div>							
					</div>
					
					<div class="" data-wrap-config>	
							<?//if($model->owner_type == 'configuration'){?>
								<?// $form->field($configModel, 'name')->textInput(['maxlength' => 128]) ?>								
								<?// $form->field($configModel, 'comments')->textArea(['maxlength' => 20000, 'rows' => 5]) ?>
							<?//}?>
							<button type="button" class="btn btn-success mb-20 mt-20" data-harness-add>Добавить оборудование</button>
							<button type="button" class="btn btn-primary" data-config-import-button data-toggle="tooltip" data-placement="top" title="Импорт оборудования из выбранной конфигурации">Импортировать конфигурацию</button>
					</div>
								
					<div data-hidden-select>
						<div class="invisible" data-all-harnesses='<?=$harness?>'>
							<div class="row mb-10" data-ajax-form-harness-row> 
							<div class="col-md-4">
							<?= Html::dropDownList(
								'TypeFilter', '', 
								$selectItems['types'], 
								['class'=>'type-select form-control', 'data-ajax-form-harness-type'=> '']
							);?>
							</div>
							<div class="col-md-5">
							<?= Html::dropDownList(
								'NameFilter', '', 
								[], 
								['class'=>'name-select form-control', 'data-ajax-form-harness-name'=> '']
							);?>										
							</div>
							<div class="col-md-2">
								<div class="form-group mb-0">
									<input class="form-control mb-0" type="number" id="brands-counts" name="BrandsConfig[counts][]" value="1" <?= $configModel->owner_type == 'configuration' ? 'disabled' : ''?>>
								</div>
							</div>
							<span class="fontello icon-cancel icon_delete-harness text-red" title="Удалить жгут" data-delete-harness=""></span>
							<input type="hidden" id="brands-harnesses" data-harness-id="" name="BrandsConfig[harnesses][]" value="">
							</div>
						</div>
					</div>					
				</div>
			</div>			
					<?= Html::csrfMetaTags() ?>
					<? ActiveForm::end(); ?>

		</div>
	</div>
</div>