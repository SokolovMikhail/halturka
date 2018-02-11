<?
use yii\widgets\ActiveForm;
use frontend\widgets\Tabs;

$this->params['header_links'] = [
	[
		'icon'	=> 'fa-arrow-circle-left',
		'title'	=> 'Назад к списку пользователей',
		'link'	=> '/user/#item-'.$model->id,
	],
	[
		'icon'	=> 'fa-plus-circle',
		'title'	=> 'Добавить пользователя',
		'link'	=> '/user/create/',
	],
];
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<div class="panel-body">
				<div class="devices-form panel-tab">
					<div class="row mb-40">
					
					<? 
					$tabItems = [
						[
							'label' => 'Общие настройки',
							'content' =>  $this->render('_form-tab-1', ['model' => $model, 'result' => $result]),
							'active' => true,
							'options' => [
								'id' => 'common-settings'
							]
						]
					];
					
					if($model->id && $result['notifyTabModules']){
						$tabItems[] = 
						[
							'label' => 'Отчеты и уведомления',
							'content' =>  $this->render('_form-tab-2', ['model' => $model, 'result' => $result]),
							'active' => false,
							'options' => [
								'id' => 'notify-settings'
							]
						];
					}
					?>

					<?= Tabs::widget([
						'items' => $tabItems,
						'options' => [
							'class' => 'mb-20',
							'data-tabs-in-form'=>'',
							'data-nav-tabs-linked'=>''
						],
					])?>
					
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
