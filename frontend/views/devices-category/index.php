<?
use frontend\models\AppOptions;
$this->title = 'Типы техники';
$this->params['main_nav_current'] = 'devices-category';
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<!--div class="panel-body">
				<a href="/devices-category/create/" class="btn btn-primary">Добавить новый тип техники</a>
			</div-->
			<?if(count($arResult['items'])){?>
				<table class="table table-hover personal-task">
					<tbody>
						<tr class="personal-task_head">
							<td></td>
							<td>Название типа техники</td>
							<?if(AppOptions::can('use_kpi')){?>
								<td>Авторизован</td>
								<td>Передвижение</td>
								<td>Работа вилами</td>
							<?}?>
						</tr>
						<?
						$i = 0;
						foreach($arResult['items'] as $item){?>
						<tr>
							<td><?= ++$i?></td>
							<td>
								<a href="/devices-category/update/<?=$item->id?>/"><?=$item->name?></a>
							</td>
							<?if(AppOptions::can('use_kpi')){?>
							<td><?=$item->engine_kpi?> часов</td>
							<td><?=$item->active_kpi?> часов</td>
							<td><?=$item->work_kpi?> часов</td>
							<?}?>
						</tr>	
						<?}?>
					</tbody>
				</table>
			<?}else{?>
				<div class="panel-body">
					<h4>Ни одного типа техники не добавлено.</h4>
				</div>
			<?}?>
		</div>
	</div>
</div>