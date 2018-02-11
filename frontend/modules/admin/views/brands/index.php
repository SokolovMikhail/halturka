<?
use frontend\widgets\FilterStoragesWidget;
use yii\widgets\LinkPager;
use frontend\modules\admin\assets\AdminAsset;
use frontend\models\Brands;

use yii\helpers\Html;

AdminAsset::register($this);
$this->title = 'Справочник';
$this->params['main_nav_current'] = 'admin/brands';
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<div class="panel-body">
				<div class="row mb-10">
					<div class="col-md-2">
						<form data-filter-form>
							<div class="">
								<?= Html::dropDownList(
									'brandFilter', $filterItems['brands_current'], 
									$filterItems['brands'], 
									['class'=>'mb-10  form-control selectpicker', 'data-page-reloader-form'=>'', 'data-live-search' => 'true']
								);?>
							</div>
						</form>	
					</div>				
				</div>
				<div class="row">
					<div class = "col-xs-12">					
						<a href="/admin/brands/create/" class="btn btn-primary mb-10">Добавить модель</a>					
					</div>
				</div>
			<div class="row">
			<div class = "col-xs-12">
			<? if(count($result['items'])){?>
				<table class="table table-hover personal-task">
					<tbody>
						<tr class="personal-task_head">
							<td>№</td>
							<td>Брэнд</td>
							<td></td>
							<td>Модель</td>
							<td>Тип</td>
							<td>Напряжение</td>
							<td>Дата выпуска</td>
							<td>Доступные конфигурации</td>
						</tr>
						<? 
						$i=0;
						foreach ($result['items'] as $item){
							$i++;?>
							<tr id="item-<?= $item->id?>">
								<td>
									<?= $i?>
								</td>
								<td>
									<a href="/admin/brands/update/?id=<?=$item['id']?>" title="Перейти к редактированию"><?= $item['brand']?></a>

								</td>
								<td>
									<?if($result['images'][$item->id]){?>
										<a class="example-image-link" href="/rest/images/get/?id=<?= $result['images'][$item->id]->id?>" data-lightbox="example-set-<?=$i?>" title="Изображение модели">	<i class="fa fa-search-plus" aria-hidden="true"></i></a>
									<?}?>								
								</td>
								<td>
									<?= $item['model']?><?= $item['pseudonym'] ? ' ('.$item['pseudonym'].')' : ''?>
								</td>
								<td>
									<?= Brands::getTehTypeList()[$item['type']]?>
								</td>
								<td>
									<?= Brands::getVoltage()[$item->voltage]?>
								</td>
								<td>
									<?= $item['release_date']?>
								</td>
								<td class='mark-red1'>
									<?= $item['series']!='' ? $item['series'] : 'Нет доступных конфигураций'?>
								</td>								
							</tr>
							<?
						}?>
					</tbody>
				</table>
			<?}else{?>
				<div class="panel-body">
					<h4>Справочник пуст.</h4>
				</div>
			<?}?>
			</div>
			</div>
</div>			
		</div>
	</div>
</div>