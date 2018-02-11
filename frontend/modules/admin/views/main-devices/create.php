<?
use frontend\adminModels\Licenses;
use yii\helpers\ArrayHelper;
use frontend\models\Brands;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use frontend\modules\admin\assets\AdminAsset;

AdminAsset::register($this);
$this->params['main_nav_current'] = 'admin/order';
$this->title = 'Создание';
$this->params['header_links'] = [
	[
		'icon'	=> 'fa-arrow-circle-left',
		'title'	=> 'Назад к заявке',
		'link'	=> '/admin/order/update/?id='.$backId,
	]	
];
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<div class="panel-body">		
			<?$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);?>
					<?= $this->render('_form-tab-4', ['adminModel' => $adminModel, 'form' => $form, 'selectArray' => $selectArray, 'brand' => 0])?>
			
			<div class="col-xs-6">
			<div class="form-group">
				<?= Html::submitButton('Сохранить', ['class' => $adminModel->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
			</div>			
			</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>