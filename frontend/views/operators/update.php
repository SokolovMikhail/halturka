<?
use frontend\models\Cards;
use frontend\models\helpers\TimeHelper;

$this->title = 'Настройки оператора: '.$model->name;
$this->params['main_nav_current'] = 'operators';
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<div class="panel-body">
				<?= $this->render('_form', [
					'model'			=> $model,
					'formParams'	=> $formParams,
				]) 
				?>
				<h3>Зарегистрированные карты</h3>
				<?if(count($cards)){?>
				<table class="table table-hover personal-task">
					<tbody>
						<tr class="personal-task_head">
							<td>ID карты</td>
							<td>Дата добавления</td>
							<td></td>
						</tr>
						<? foreach($cards as $card){?>
						<tr>
							<td><?= $card->id?> (<?= Cards::secondCardIdFormat($card->id)?>)</td>
							<td><?= TimeHelper::printWithTimeZone($card->date_create, $model->storage_id)?></td>
							<td>
								<a href="/operators/delete-card/<?=$card->id?>/" data-confirm="Вы действительно хотите удалить карту?" data-method="post" data-pjax="0">
									Удалить карту
								</a>
							</td>
						</tr>
						<?}?>
					</tbody>
				</table>
				<?}
				else
				{?>
				<div class="bs-callout" id="callout-overview-not-both">						
					<p>У оператора нет зарегистрированных карт. Добавьте карты, чтобы оператор мог получить доступ к технике</p>
				</div>
				<?}?>
				<a href="/operators/add-card/<?=$model->id ?>/" class="btn btn-primary">Добавить новую карту</a>
			</div>
		</div>
	</div>
</div>