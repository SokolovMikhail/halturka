<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use frontend\modules\admin\assets\AdminAsset;
use app\modules\admin\models\PurchasingOrder;

AdminAsset::register($this);


?>

<div class="row">
	<div class="col-xs-12 mb-20">					
		<span class="" data-toggle="collapse" data-target="#main-info" data-collapsable-title='admin-main'>							
			<span class="js-link control-label control-label-size">
				Главная информация
			</span>
			<span class="fontello tree-node_status-icon"></span>
		</span>
		<a class="clickable" data-hide-all="">Свернуть все</a>
		<div id="main-info" class="row collapse in mt-10" data-collapsable-content='admin-main'>
			<div class="col-md-4">
				<?= $form->field($model, 'name')->textInput(['maxlength' => 128]) ?>
				<?= $form->field($model, 'order_parent')->dropDownList($parents, ['class' => 'form-control selectpicker mb-20 custom-select-search-default', 'data-live-search' => 'true']) ?>
				<?= $form->field($model, 'client_id')->dropDownList($clients) ?>
				<?= $form->field($model, 'city')->textArea(['maxlength' => 128, 'rows' => 2]) ?>
				<? $form->field($model, 'wifi')->checkbox(['class' => 'mb-0']);?>
				<? $form->field($model, 'pocket')->checkbox(['labelOptions' => []]);?>						
			</div>
			<div class="col-md-4">
				<?= $form->field($model, 'contacts')->textArea(['maxlength' => 255, 'rows' => 5]) ?>
			</div>
			<div class="col-md-4">
				<?= $form->field($model, 'card_type')->dropDownList($cardTypes)?>
				<?= $form->field($model, 'gsm_operator')->dropDownList($operators)?>
			</div>
			<div class="col-md-6">
				<?= $form->field($model, 'comments')->textArea(['maxlength' => 2048, 'rows' => 5]) ?>
			</div>							
		</div>

	</div>
	<div class="col-xs-12 mb-20">					
		<span class="" data-toggle="collapse" data-target="#main-contractor" data-collapsable-title='admin-contractor'>							
			<span class="js-link control-label control-label-size">
				Установщик
			</span>
			<span class="fontello tree-node_status-icon"></span>
		</span>						
		<div id="main-contractor" class="row collapse in mt-10" data-collapsable-content='admin-contractor'>
			<div class="col-md-4">
				<?= $form->field($model, 'contractor')->dropDownList(PurchasingOrder::getContractors())?>
			</div>
			<div class="col-md-8">
				<?= $form->field($model, 'contractor_comment')->textArea(['maxlength' => 255, 'rows' => 3]) ?>
			</div>							
		</div>

	</div>					
	<div class="col-xs-12 mb-20">
		<span class="" data-toggle="collapse" data-target="#progress" data-collapsable-title='admin-progress'>							
			<span class="js-link control-label control-label-size">
				Статусы заявки
			</span>
			<span class="fontello tree-node_status-icon"></span>
		</span>							
		<div id="progress" class="row collapse in mt-10" data-collapsable-content='admin-progress'>
			<div class="col-md-6">
				<?= $form->field($model, 'date')->textInput([
					'maxlength' => 128, 
					'class' => 'form-control',
					'data-daterangepicker' => '',
					'data-start-date' => $model->date,
					'data-single-date-picker' => 'true',
				]) ?>	
			</div>
			<div class="col-md-6">
				<?= $form->field($model, 'date_shipment')->textInput([
					'maxlength' => 128, 
					'class' => 'form-control',
					'data-daterangepicker' => '',
					'data-start-date' => $model->date_shipment,
					'data-single-date-picker' => 'true',
				]) ?>	
			</div>							
			<div class="col-md-6">
				<?= $form->field($model, 'contract_status')->dropDownList(PurchasingOrder::contractStatuses(), ['data-select-not-first-green' => ''])?>							
				<?= $form->field($model, 'devices_list_status')->dropDownList(PurchasingOrder::devicesListStatuses(), ['data-select-last-green' => ''])?>
				<?= $form->field($model, 'equipment_prod_status')->dropDownList(PurchasingOrder::equipmentProductionStatuses(), ['data-select-last-green' => ''])?>
			</div>						
			<div class="col-md-6">
				<?= $form->field($model, 'harness_prod_status')->dropDownList(PurchasingOrder::harnessProductionStatuses(), ['data-select-harness-green' => ''])?>
				<?= $form->field($model, 'account_status')->dropDownList(PurchasingOrder::accountStatuses(), ['data-select-not-first-green' => ''])?>
				<?= $form->field($model, 'responsible')->dropDownList(PurchasingOrder::responsiblePersonsLong(), ['data-select-not-first-green' => ''])?>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="col-md-3">
						<?= $form->field($model, 'installer_agreement')->checkbox() ?>
					</div>
					<div class="col-md-3">
						<?= $form->field($model, 'client_agreement')->checkbox() ?>
					</div>					
					<div class="col-md-2">
						<?= $form->field($model, 'installed')->checkbox() ?>						
					</div>
					<div class="col-md-4">
						<?= $form->field($model, 'installed_comment')->textArea(['maxlength' => 1024, 'rows' => 5]) ?>
					</div>
				</div>
			</div>
		</div>							
	</div>
	<div class="col-xs-12 mb-20">
		<span class="" data-toggle="collapse" data-target="#controller" data-collapsable-title='admin-controller'>							
			<span class="js-link control-label control-label-size">
				Контроллер
			</span>
			<span class="fontello tree-node_status-icon"></span>
		</span>						
		<div id="controller" class="row collapse in mt-10" data-collapsable-content='admin-controller'>
			<div class="col-md-6">
				<?= $form->field($model, 'KPP')->textInput(['maxlength' => 128]) ?>
				<?= $form->field($model, 'name_controller')->textArea(['maxlength' => 128]) ?>
				<?= $form->field($model, 'engagement')->textArea(['maxlength' => 255, 'rows' => 5]) ?>
			</div>
			<div class="col-md-6">
				<?= $form->field($model, 'customer')->textArea(['maxlength' => 128, 'rows' => 3]) ?>
				<?= $form->field($model, 'blank')->dropDownList($files)?>
			</div>
		</div>
	</div>
	<div class="col-xs-12 mb-20">
		<span class="" data-toggle="collapse" data-target="#contact" data-collapsable-title='admin-contact'>							
			<span class="js-link control-label control-label-size">
				Контактная информация
			</span>
			<span class="fontello tree-node_status-icon"></span>
		</span>						
		<div id="contact" class="row collapse in mt-10" data-collapsable-content='admin-contact'>
				<div class="col-md-4">
				<?= $form->field($model, 'name_defendant')->textArea(['maxlength' => 128]) ?>
				</div>
				<div class="col-md-4">
				<?= $form->field($model, 'phone_defendant')->textInput(['maxlength' => 128]) ?>
				</div>
				<div class="col-md-4">								
				<?= $form->field($model, 'mail_defendant')->textInput(['maxlength' => 128]) ?>
				</div>
		</div>
	</div>
	


	
</div>
	
		<div class="mb-10">
			<div class="row">
				<div class="col-xs-12">
					<?= Html::submitButton('Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'data-stage-save-all' => '']) ?>
				</div>
			</div>
		</div>										
		

					

