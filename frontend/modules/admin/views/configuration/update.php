<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use frontend\modules\admin\assets\AdminAsset;
use frontend\models\Brands;
use app\modules\admin\models\InstallationStage;
use app\modules\admin\models\PurchasingOrder;

AdminAsset::register($this);
if($model->owner_type == 'configuration'){//Если конфигурация модели
	$this->params['main_nav_current'] = 'admin/brands';
	$this->params['header_links'] = [
		[
			'icon'	=> 'fa-arrow-circle-left',
			'title'	=> 'Назад к модели',
			'link'	=> '/admin/brands/update/?id=' . $model->owner_id,
		],
	];	
	$brand = Brands::findOne($model->owner_id);
	if($model->id){
		$this->title = 'Редактирование конфигурации модели ' . $brand->brand . ' ' . $brand->model;
	$this->params['header_links'][] =
		[
			'icon'	=> 'fa-files-o ',
			'title'	=> 'Копировать конфигурацию',
			'link'	=> '/admin/configuration/copy/?id=' . $model->id,
		];	
		
		
	}else{
		$this->title = 'Копирование конфигурации модели ' . $brand->brand . ' ' . $brand->model;
	}
}else{//Если список оборудования заявки
	$order = PurchasingOrder::findOne($model->owner_id);
	$this->params['main_nav_current'] = 'admin/order';
	$this->params['header_links'] = [
		[
			'icon'	=> 'fa-arrow-circle-left',
			'title'	=> 'Назад к заявке',
			'link'	=> '/admin/order/update/?id=' . $model->owner_id,
		],
	];	
	$this->title = 'Редактирование списка оборудования заявки ' . $order->name;	
}
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<div class="panel-body">
				<ul class="nav nav-tabs nav-tabs-custom" role="tablist">
					<li role="presentation" class="active clickable">
						<a href="/admin/configuration/update/?id=<?= $model->id?>" class="clickable">
							Оборудование
						</a>
					</li>
					<?if($model->id && $model->owner_type == 'configuration'){?>
					<li role="presentation">
						<a href="/admin/configuration/instruction/?id=<?= $model->id?>">
							Инструкция
						</a>
					</li>
					<?}?>
				</ul>				
				<div class="row mt-10">
					<div class="col-xs-6">
						
						<div class="" data-form-wrap>
							<div class="" data-all-harnesses='<?=$harness?>'>							
							</div>							
						</div>
								<? $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
								<div class="" data-wrap-config>
										<?if($model->owner_type == 'configuration'){?>
										<?= $form->field($model, 'name')->textInput(['maxlength' => 128]) ?>								
										<?= $form->field($model, 'comments')->textArea(['maxlength' => 20000, 'rows' => 5]) ?>
										<?= $form->field($model, 'checked')->checkbox() ?>
										<?}?>
										<button type="button" class="btn btn-success mb-20" data-harness-add>Добавить оборудование</button>
								</div>
									<? 	$i=0;
										if(isset($equipmentList)){
										foreach($equipmentList as $equipItem) {
									?>
									<div class="row mb-10" data-ajax-form-harness-row>
										<?if(isset($equipItem['image'])){?>
											<a class="example-image-link" href="/rest/images/get/?id=<?= $equipItem['image']?>" data-lightbox="example-set-<?=$i?>" data-toggle="tooltip" data-placement="top" title="Изображение оборудования"><i class="font-18 fa fa-search-plus" aria-hidden="true"></i></a>								
										<?}?>
										<div class="col-md-4">
										<?= Html::dropDownList(
											'TypeFilter', isset($equipItem['current_type']) ? $equipItem['current_type'] : '', 
											$result['types'], 
											['class'=>'type-select form-control', 'data-ajax-form-harness-type'=> '']
										);?>
										</div>
										<div class="col-md-5">								
										<?= Html::dropDownList(
											'NameFilter', isset($equipItem['current_name']) ? $equipItem['current_name'] : '', 
											isset($equipItem['names']) ? $equipItem['names'] : [], 
											['class'=>'name-select form-control', 'data-ajax-form-harness-name'=> '']
										);?>
										</div>
										<div class="col-md-2">
											<div class="form-group mb-0">
												<input class="form-control mb-0" type="number" name="BrandsConfig[counts][]" value="<?= $equipItem['count']?>" <?= $model->owner_type == 'configuration' ? 'disabled' : ''?>>
											</div>
										</div>										
											<span class="fontello icon-cancel icon_delete-harness text-red" data-toggle="tooltip" data-placement="top" title="Удалить оборудование" data-delete-harness=""></span>								
										<input type="hidden" id="brands-harnesses" name="BrandsConfig[harnesses][]" data-harness-id="" value="<?= isset($equipItem['id']) ? $equipItem['id'] : ''?>">
									</div>
									<?	$i++;}}?>								
								<?= Html::submitButton('Сохранить', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
								<?= Html::csrfMetaTags() ?>
								<? ActiveForm::end(); ?>								
							<div data-hidden-select>
								<div class="invisible" data-all-harnesses='<?=$harness?>'>
									<div class="row mb-10" data-ajax-form-harness-row> 
									<div class="col-md-4">
									<?= Html::dropDownList(
										'TypeFilter', '', 
										$result['types'], 
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
											<input class="form-control mb-0" type="number" id="brands-counts" name="BrandsConfig[counts][]" value="1" <?= $model->owner_type == 'configuration' ? 'disabled' : ''?>>
										</div>
									</div>
									<span class="fontello icon-cancel icon_delete-harness text-red" data-toggle="tooltip" data-placement="top" title="Удалить оборудование" data-delete-harness=""></span>
									<input type="hidden" id="brands-harnesses" data-harness-id="" name="BrandsConfig[harnesses][]" value="">
									</div>
								</div>
							</div>							
					</div>
			</div>
		</div>
	</div>
</div>
</div>
<a href="#" class="float invisible-form-wrap" data-float-button-target="data-stage-save-all" data-toggle="tooltip" data-placement="left" title="Сохранить инструкцию">
<i class="fa fa-floppy-o my-float "></i>
</a>