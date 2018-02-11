<?
$this->title = 'Категории прав';
$this->params['main_nav_current'] = 'licenses';
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<!--div class="panel-body">
				<a href="/licenses/create/" class="btn btn-primary">Добавить новую категорию</a>
			</div-->
			<?if(count($arResult['items'])){?>
				<div class="panel-body tree-ul">
					<table class="table table-hover personal-task">
						<tbody>
							<tr class="personal-task_head">
								<td></td>
								<td>Имя категории</td>
								<td>Комментарий</td>
							</tr>
							<?
							$i=0;
							foreach($arResult['items'] as $item){?>
							<tr>
								<td><?= ++$i?></td>
								<td>
									<a href="/licenses/update/<?=$item->id?>/"><?=$item->name?></a>
								</td>
								<td><?=$item->description?></td>
							</tr>	
							<?}?>
						</tbody>
					</table>
				</div>
			<?}else{?>
				<div class="panel-body">
					<h4>Ни одной категории не добавлено.</h4>
				</div>
			<?}?>
		</div>
	</div>
</div>
