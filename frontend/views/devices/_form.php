<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\widgets\Alert;
use frontend\widgets\Tabs;

$this->params['header_links'] = [
	[
		'icon'	=> 'fa-arrow-circle-left',
		'title'	=> 'Назад к списку техники',
		'link'	=> '/devices/#item-'.$model->id,
	],
	[
		'icon'	=> 'fa-plus-circle',
		'title'	=> 'Добавить технику',
		'link'	=> '/devices/create/',
		'roles'	=> ['manageDevices'],
	],
];
?>

<div class="devices-form panel-tab">
	<? 
	$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); 
	$tabItems = [
		[
			'label' => 'Общие настройки',
			'content' =>  $this->render('_form-tab-1', ['model' => $model, 'form' => $form]),
			'active' => true,
			'options' => [
				'id' => 'common-settings'
			]
		],
		[
			'label' => 'Блокировка при ударе',
			'content' => $this->render('_form_lock-setting', ['model' => $model, 'form' => $form, 'typesCrashes' => $formParams['typesCrashes']]),
			'active' => false,
			'options' => [
				'id' => 'locking'
			]
		],
		[
			'label' => 'Права и доступ к технике',
			'content' => $this->render('_form-tab-3', ['model' => $model, 'form' => $form, 'formParams' => $formParams]),
			'active' => false,
			'options' => [
				'id' => 'access'
			]
		]
	];
	
	if(in_array('manageDevices', Yii::$app->config->params['user']['roles'])){
		$tabItems[] = [
			'label' => 'Администрирование',
			'content' => $this->render('_form-tab-4', ['adminModel' => $adminModel, 'form' => $form, 'selectArray' => $selectArray, 'model' => $model, 'uploadForm' => $uploadForm, 'images' => $images]),
			'active' => false,
			'options' => [
				'id' => 'admin'
			]
		];
	}
	?>
	
	<?= Tabs::widget([
		'items' => $tabItems,
		'options' => [
			'data-tabs-in-form'=>'',
			'data-nav-tabs-linked'=>''
		],
	])?>
	<div class="panel-body">
		<div class="form-group">
			<?= Html::submitButton('Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		</div>
	</div>
    <?php ActiveForm::end(); ?>
</div>