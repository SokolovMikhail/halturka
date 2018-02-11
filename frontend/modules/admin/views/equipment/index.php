<?
use frontend\widgets\FilterStoragesWidget;
use yii\widgets\LinkPager;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use frontend\modules\admin\assets\AdminAsset;


AdminAsset::register($this);
$this->title = 'Справочник оборудования';
$this->params['main_nav_current'] = 'admin/equipment';
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<div class="panel-body">
				<div class="row">
						<div class="col-md-2">
							<a href="/admin/equipment/create/" class="btn btn-primary">Добавить оборудование</a>
						</div>
						<div class="col-md-2">
							<form data-filter-form>
								<div class="">
									<?= Html::dropDownList(
										'statusFilter', $statuses_list['current'], 
										ArrayHelper::map($statuses_list['availbale'], 'id', 'name'), 
										['class'=>'mb-10  form-control', 'data-page-reloader-form'=>'']
									);?>
								</div>
							</form>	
						</div>
				</div>
			
			<div class="row">
			<div class="col-md-12">
			<? if(count($result['items'])){?>
				<table class="table table-hover personal-task">
					<tbody>
						<tr class="personal-task_head">
							<td>Название</td>
							<td></td>							
							<td>Тип</td>
							<td>Код</td>
						</tr>
						<? 
						$i=0;
						foreach ($result['items'] as $item){
							$i++;?>
							<tr id="item-<?= $item->id?>">
								<td>
									<a href="/admin/equipment/update/?id=<?=$item['id']?>" title="Перейти к редактированию"><?= $item['name']?></a>
								</td>
								<td>
									<?if($result['images'][$item->id]){?>
										<a class="example-image-link" href="/rest/images/get/?id=<?= $result['images'][$item->id]->id?>" data-lightbox="example-set-<?=$i?>" title="Изображение оборудования">	<i class="fa fa-search-plus" aria-hidden="true"></i></a>
									<?}?>								
								</td>								
								<td>
									<?= $types[$item['type']]?>
								</td>
								<td>
									<?= $item['external_id']?>
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
			<div class="row">
				<div class="ta-c">
				<?php
					echo LinkPager::widget([
					'pagination' => $pagenator,
					'registerLinkTags' => true
				]);
				?>
				</div>
			</div>
</div>			
		</div>
	</div>
</div>