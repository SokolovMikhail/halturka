<?
use frontend\widgets\FilterStoragesWidget;

$this->title = 'Операторы';
$this->params['main_nav_current'] = 'operators';
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<div class="panel-body">
				<div class="row">
					<?= FilterStoragesWidget::widget([
						'storages' => $result['storages'],							
						'form' => true,							
					])?>
					<div class="col-xs-4">
						<a href="/operators/create/" class="btn btn-primary">Добавить нового оператора</a>
					</div>
				</div>
			</div>
			<? if(count($result['operators'])){?>
			<table class="table table-hover personal-task">
				<tbody>
					<tr class="personal-task_head">
						<td></td>
						<td>Имя оператора</td>
						<td>Роль</td>
						<td>Отдел</td>
						<td class="ta-c">Карты</td>
						<? if(isset(Yii::$app->modules['speedcontrol'])){?>
						<td class="ta-c">Макс. скорость, км/ч</td>
						<? }?>
						<td></td>
					</tr>
					<? 
					$i=0;
					foreach ($result['operators'] as $item){?>
					<tr id="item-<?= $item->id?>">
						<td>
							<span class="badge <?= $item->active ? 'green-bg' : 'grey-bg'?>" title="<?= $item->active ? 'Учитывать в статистике' : 'Не учитывать в статистике'?>">
								<?=++$i;?>
							</span>
						</td>
						<td>
							<a href="/operators/update/<?= $item->id?>/" title="Редактировать оператора"><?= $item->name?></a>
						</td>
						<td><?= $item->roleArray ? implode('<br/>', array_intersect_key($result['roles'], $item->roleArray)) : 'Не назначено';?></td>
						<td><?= $result['storages']['available_by_id'][$item->storage_id]['name']?></td>
						<td class="ta-c">
							<span class="fontello icon-pass <?= isset($result['cards'][$item->id]) ? 'text-green' : 'text-grey'?>" 
								title="<?= isset($result['cards'][$item->id]) ? 'Зарегистрировано карт: '.count($result['cards'][$item->id]) : 'Нет зарегистрированых карт'?>"
							></span>
						</td>
						<? if(isset(Yii::$app->modules['speedcontrol'])){?>
						<td class="ta-c"><?= $item->speed_limit ? $item->speed_limit : '<span class="fontello icon-device-cat-2" title="Определяется ТС"></span>'?></td>
						<? }?>
						<td>
							<a href="/operators/delete/<?= $item->id?>/" data-confirm="Вы действительно хотите удалить оператора?<br>Будет удалена вся статистика по данному оператору." data-method="post" data-pjax="0">
								Удалить
							</a>
						</td>
					</tr>
					<? }?>
				</tbody>
			</table>
			<?}else{?>
			<div class="panel-body">
				<h4>В данный отдел ни одного оператора не добавлено.</h4>
			</div>
			<?}?>
		</div>
	</div>
</div>