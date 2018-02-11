<?
use frontend\widgets\FilterStoragesWidget;
use yii\widgets\LinkPager;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = 'Справочник услуг';
$this->params['main_nav_current'] = 'admin/service';
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<div class="panel-body">
				<div class="row mb-10">
						<div class="col-md-2">
							<a href="/admin/service/create/" class="btn btn-primary">Добавить услугу</a>
						</div>
				</div>
				
				<div class="row">
				<div class="col-md-12">
				<? if(count($result['items'])){?>
					<table class="table table-hover personal-task">
						<tbody>
							<tr class="personal-task_head">
								<td>Название</td>
								<td>Цена по умолчанию</td>
							</tr>
							<? 
							$i=0;
							foreach ($result['items'] as $item){
								$i++;?>
								<tr id="item-<?= $item->id?>">
									<td>
										<a href="/admin/service/update/?id=<?=$item['id']?>" title="Перейти к редактированию"><?= $item['name']?></a>
									</td>
									<td>
										<?= $item['default_price']?>
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