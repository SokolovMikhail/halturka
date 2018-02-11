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
				<ul class="nav nav-tabs nav-tabs-custom" role="tablist"  data-nav-tabs-linked="">
					<li role="presentation">
						<a href="/admin/configuration/update/?id=<?= $model->id?>">
							Оборудование
						</a>
					</li>
					<li role="presentation" class="active">
						<a href="/admin/configuration/instruction/?id=<?= $model->id?>" class="clickable">
							Инструкция
						</a>
					</li>
				</ul>				
				<div class="row mt-10">
					<?if($model->id && $model->owner_type == 'configuration'){?>
					<div class="col-xs-6">
						<b><p class="mb-5">Инструкция</p></b>
						<button type="button" class="btn btn-success mb-20" data-stage-add-button>Добавить этап</button>
						<div class="invisible-form-wrap" data-ivisible-stage-form>
							<div class="row mb-10">
								<div class="col-xs-12">
									<div class="thumbnail">
										<div class="caption">
											<? $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'], 'action' => '/admin/configuration/add-stage/?ownerId=' . $model->id]); ?>
												<?= $form->field($stage, 'text')->textArea(['maxlength' => 20000, 'rows' => 3]) ?>
												<?= $form->field($stage, 'sort')->dropDownList(InstallationStage::getStagesList()) ?>
												<?= $form->field($uploadForm, 'imageFile[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>
											<?= Html::submitButton('Добавить', ['class' => 'btn btn-primary', 'data-add-distribution-button']) ?>	
											<?= Html::csrfMetaTags() ?>
											<? ActiveForm::end(); ?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?if($stagesList){?>
						<div class="row mb-10">
							<div class="col-xs-12">
								<a href="/admin/configuration/get-instruction/?id=<?= $model->id?>">Скачать инструкцию</a>
							</div>
						</div>	
						<div class="row mb-10">
							<div class="col-xs-12">
								<?foreach($stagesList as $item){?>
								<?$i=0;?>
								<div class="thumbnail" data-stage-wrap-parent="">
									<div class="caption"  data-stage-wrap="">
										<input type="hidden" data-stage-id="<?= $item['id']?>" value="<?= $item['id']?>">
										<div class="row">
											<div class="col-xs-12">												
												<button type="button" class="close-button" data-delete-stage-button=""><span aria-hidden="true" class="text-red">×</span></button>
												<button type="button" class="edit" data-edit-stage-button=""><span class="fa fa-pencil" aria-hidden="true"></span></button>
											</div>
										</div>
										<div class="row">
											<div class="col-xs-12">																			
												<div style="font-size: 14px;"><?= isset(InstallationStage::getStagesList()[$item['sort']]) ? $item['sort'] . ') ' . InstallationStage::getStagesList()[$item['sort']] : $item['sort']?><br><?= $item['text']?></div>
												<?if(isset($item['images'])){?>
													<a class="example-image-link" href="/rest/images/get/?id=<?=$item['images']?>" data-lightbox="example-set-<?= $i?>" title="<?= $item['text']?>"><img src ="/rest/images/get/?id=<?=$item['images']?>"class="img-rounded  mt-10"></a>
												<?}?>
											</div>
										</div>										
									</div>
								</div>
								<?$i++;?>
								<?}?>
							</div>
						</div>
						<?}?>
						<button type="button" class="btn btn-success invisible-form-wrap" data-stage-save-all="">Сохранить изменения</button>
					</div>
					<?}else{?>
						<h4>Что то не так</h4>
					<?}?>
			</div>
		</div>
	</div>
</div>
</div>
<a href="#" class="float invisible-form-wrap" data-float-button-target="data-stage-save-all" data-toggle="tooltip" data-placement="left" title="Сохранить инструкцию">
<i class="fa fa-floppy-o my-float "></i>
</a>