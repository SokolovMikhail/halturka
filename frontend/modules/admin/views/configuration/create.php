<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use frontend\modules\admin\assets\AdminAsset;
use app\modules\admin\models\PurchasingOrder;

AdminAsset::register($this);
if($model->owner_type == 'configuration'){
	$this->params['main_nav_current'] = 'admin/brands';
	$this->params['header_links'] = [
		[
			'icon'	=> 'fa-arrow-circle-left',
			'title'	=> 'Назад к модели',
			'link'	=> '/admin/brands/update/?id=' . $model->owner_id,
		],
	];
	$this->title = 'Создать новую конфигурацию';
}else{
	$order = PurchasingOrder::findOne($model->owner_id);
	$this->params['main_nav_current'] = 'admin/brands';
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
				<div class="row">
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
										<?}?>
										<button type="button" class="btn btn-success mb-20" data-harness-add>Добавить оборудование</button>
								</div>
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
									<span class="fontello icon-cancel icon_delete-harness text-red" title="Удалить жгут" data-delete-harness=""></span>
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