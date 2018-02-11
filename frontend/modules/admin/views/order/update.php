<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use frontend\modules\admin\assets\AdminAsset;
use frontend\models\Equipment;
use frontend\models\BrandsConfig;

AdminAsset::register($this);

$this->params['main_nav_current'] = 'admin/order';
$this->title = isset($clients[$model->client_id]) ? $clients[$model->client_id] . ' ' . $model->name : $model->name;

// получение коллекции кук (yii\web\CookieCollection) из компонента "request"
$cookies = Yii::$app->request->cookies;
// получение куки с названием "prev. Если кука не существует, "index"  будет возвращено как значение по-умолчанию.
$prev = $cookies->getValue('prev', 'index');

if($prev == 'index'){
	$this->params['header_links'] = [
		[
			'icon'	=> 'fa-arrow-circle-left',
			'title'	=> 'Назад к списку заявок',
			'link'	=> '/admin/order/',
		]
	];
}else{
	$this->params['header_links'] = [
		[
			'icon'	=> 'fa-arrow-circle-left',
			'title'	=> 'Назад к плану установок',
			'link'	=> '/admin/order/calendar/',
		]
	];	
}
	$this->params['header_links'] [] = 
		[
			'icon'	=> 'fa-rocket',
			'title'	=> 'Создать дочернюю заявку',
			'link'	=> '/admin/order/create-child/?parentId=' . $model->id,
		];
?>
<? $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
<div class="row">
	<div class="col-xs-12">
		
		<div class="panel">
			
			<div class="panel-body">
				<?= $this->render('_form', [
					'model'			=> $model,  
					'cardTypes'		=> $cardTypes,
					'operators' => $operators,
					'clients' => $clients,
					'files' => $files,
					'form' => $form,
					'parents' => $parents,
				]) ?>
						
				<div class="row mt-10">
					<div class="col-md-4">
						<div class="row">
							<div class="col-xs-12">
								<a href="/admin/order/get-equipment-xls/?id=<?= $model->id?>">Список оборудования в .xls</a>
							</div>
							<div class="col-xs-12">
								<a href="/admin/order/get-equipment-xls-compact/?id=<?= $model->id?>">Список оборудования без жгутов</a>
							</div>
		
							<div class="col-xs-12">
								<a href="/admin/order/get-harness-xls/?id=<?= $model->id?>">Список жгутов</a>
							</div>
		
							<div class="col-xs-12">
								<a href="/admin/order/get-supply-contract/?id=<?= $model->id?>">Договор</a>
							</div>
							<div class="col-xs-12">
								<a href="/admin/order/get-install-contract/?id=<?= $model->id?>">Приложение к договору на установку</a>
							</div>
							<div class="col-xs-12">
								<a href="/admin/order/get-equipment-doc/?id=<?= $model->id?>">Выгрузка оборудования на Мой склад</a>
							</div>
							<div class="col-xs-12">
								<a href="/admin/order/devices-list/?id=<?= $model->id?>">Список техники doc</a>
							</div>
							<div class="col-xs-12">
								<a href="/admin/order/devices-list-xls/?id=<?= $model->id?>">Список техники xls</a>
							</div>							
						</div>
					</div>
					<div class="col-md-4">
						<div class="row">
							<div class="col-xs-12">
								<a href="/admin/order/get-equipment-doc-warranty/?id=<?= $model->id?>">Выгрузка оборудования по гарантии на Мой склад</a>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="row">
							<div class="col-xs-12">
								<a href="/admin/order/get-equipment-doc-all/?id=<?= $model->id?>">Выгрузка всего оборудования на Мой склад</a>
							</div>
							<div class="col-xs-12">
								<a href="/admin/order/get-installation-task/?id=<?= $model->id?>">Задание на установку</a>
							</div>
							<div class="col-xs-12">
								<a class="clickable" data-yandex-disk-upload="<?= $model->id?>"><span class="fa fa-cloud-upload"></span> Создать папки на Яндекс Диске</a>
							</div>							
						</div>
					</div>					
				</div>					
					
			</div>
		</div>
		
		<div class="panel" data-price-list='<?= $priceList?>'>
			<div class="panel-body">
				<ul class="nav nav-tabs nav-tabs-custom" role="tablist"  data-nav-tabs-linked="">
					<li role="presentation" class="active"><a href="#new-order" aria-controls="new-order" role="tab" data-toggle="tab">
						Новая установка <span class="badge blue-custom"><?= isset($result['items'][0]) ? count($result['items'][0]) : 0 ?></span>
						<span class="badge sky-blue-custom"><?= isset($equipmentArrayNew['items']) ? count($equipmentArrayNew['items']) : 0 ?></span>
						</a>
					</li>
					<li role="presentation">
						<a href="#warranty" aria-controls="warranty" role="tab" data-toggle="tab">
							Гарантия <span class="badge blue-custom"><?= isset($result['items'][1]) ? count($result['items'][1]) : 0 ?></span>
							<span class="badge sky-blue-custom"><?= isset($equipmentArrayWarranty['items']) ? count($equipmentArrayWarranty['items']) : 0 ?></span>
						</a>
					</li>
				</ul>
				<div class="tab-content pd-0">
				<div role="tabpanel" class="tab-pane active" id="new-order">
					<?= $this->render('_tab-new-order', [
						'result'			=> $result,  
						'model'		=> $model,
						'sort' => $sort,
						'serviceOrdersList' => $serviceOrdersListNew,
						'services' => $services,
						'form' => $form,
						'equipmentArray' => $equipmentArrayNew,
					]) ?>					
				</div>
				<div role="tabpanel" class="tab-pane" id="warranty">
					<?= $this->render('_tab-warranty', [
						'result'			=> $result,  
						'model'		=> $model,
						'sort' => $sort,
						'equipmentArray' => $equipmentArrayWarranty,
						'serviceOrdersList' => $serviceOrdersListWarranty,
						'services' => $services,
						'form' => $form,
					]) ?>					
				</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?= Html::csrfMetaTags() ?>
<? ActiveForm::end(); ?>
<a class="float clickable" data-float-button-target="data-stage-save-all" data-toggle="tooltip" data-placement="left" title="Сохранить заявку">
	<i class="fa fa-floppy-o my-float "></i>
</a>