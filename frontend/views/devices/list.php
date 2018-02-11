<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\widgets\FilterStoragesWidget;
use frontend\widgets\FilterItemsCheckListWidget;

$this->title = 'Техника';
$this->params['main_nav_current'] = 'devices';
$this->params['header_links'] = [
	[
		'icon'	=> 'fa-plus-circle',
		'title'	=> 'Добавить технику',
		'link'	=> '/devices/create/',
		'roles'	=> ['manageDevices'],
	],
	[
		'icon'	=> 'fa-rocket',
		'title'	=> 'Добавить технику из заявки',
		'link'	=> '#',
		'roles'	=> ['manageDevices'],
		'options'	=> [
			'data-toggle' => 'modal',
			'data-target' => '#batch-insert-devices-form',
		]
	],
];
?>

<div class="row">
	<div class="col-xs-12">
		<div class="panel">

			<div class="panel-body device-cat-filter">
				<div class="row">
					<form>
						<div class="col-xs-3">
							<?= FilterStoragesWidget::widget([							
								'containerClass' => 'dsp-inline device-cat-filter_storages mb-10',							
							])?>
						</div>
						
						<? if(count($result['categories_list'])>1){?>
						<div class="col-xs-7">
							<div class="dsp-inline">
								<b>Тип техники:</b>
							</div>
							<?= FilterItemsCheckListWidget::widget([
								'feildName'	=> 'deviceCategories',
								'viewType'	=> 'btn',
								'items'		=> $result['categories_list'],
								'active'	=> $result['current_categories'],						
							])?>
						</div>
						<div class="col-xs-2">
							<button type="submit" class="btn btn-primary mt-10">Применить фильтр</button>
						</div>
						<? }?>

					</form>
				</div>
			</div>

			<? if(count($result['items'])){?>
				
				<? $form = ActiveForm::begin([
					'action' => ['devices/batch-editing'],
					'options' => ['enctype' => 'multipart/form-data']
				]); ?>
				
				<?= $this->render('_list-batch-editing', [ 
					'result' => $result,
					'form' => $form,
				])?>
				
				<?= $this->render('_list-table', [ 
					'result' => $result,
				])?>

				<? ActiveForm::end(); ?>
			
			<? }else{?>
			<div class="panel-body">
				<h4>Ни одного устройства не добавлено.</h4>
			</div>
			<?}?>
		</div>
	</div>
</div>

<? if(isset($result['ordersSelect'])){?>
<div class="modal fade" id="batch-insert-devices-form" tabindex="-1" role="dialog" aria-labelledby="batch-insert-devices-label">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="batch-insert-devices-label">Добавление техники из заявки</h4>
			</div>
			<form action="/devices/create-from-order/">
				<div class="modal-body">
					<div class="form-group">
					<label class="control-label" for="devices-name">Выберите заявку</label>
					<?= Html::dropDownList(
						'order', '', 
						$result['ordersSelect'], 
						[
							'class'=>'order-select dropdown-style form-control selectpicker', 
							'id' => 'createdevicesform-order',
							'data-order-select' => '',
							'data-live-search' => 'true',
						]
					);?>
					</div>
					<input type="hidden" name="storage" value="<?= Yii::$app->storagesData->mainStorage?>" />
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
					<button type="submit" class="btn btn-primary">Добавить технику из заявки</button>
				</div>
			</form>
		</div>
	</div>
</div>
<? }?>