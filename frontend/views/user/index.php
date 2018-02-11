<?
$this->title = 'Пользователи';
$this->params['main_nav_current'] = 'user';
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<div class="panel-body">
				<a href="/user/create/" class="btn btn-primary">Добавить нового пользователя</a>
			</div>
			<?if(count($items)){?>
				<table class="table table-hover personal-task">
					<tbody>
						<tr class="personal-task_head">
							<td></td>
							<td>Имя</td>
							<td>Логин</td>
							<td>Роль</td>
							<td>Права</td>
							<td></td>
						</tr>
						<? 
						$i=0;
						foreach ($items as $item){
							$i++;?>
							<tr>
								<td><?=$i;?></td>
								<td>
									<a href="/user/update/<?= $item['id']?>/" title="Редактировать пользователя"><?= $item['name'] ? $item['name'] : 'Имя не задано' ?></a>
								</td>
								<td><?= $item['username']?></td>
								<td>
									<? if(isset($item['assignments'][1])){?>
									<? foreach($item['assignments'][1] as $role){?>
									<?= $role?></br>
									<? }?>
									<? }?>
								</td>
								<td>
									<? if(isset($item['assignments'][2])){?>
									<? foreach($item['assignments'][2] as $permit){?>
									<?= $permit?></br>
									<? }?>
									<? }?>
								</td>
								<td>
									<!--<a href="/devices/delete/<?=$item['id']?>/" data-confirm="Вы действительно хотите удалить пользователя?" data-method="post" data-pjax="0">
										Удалить пользователя
									</a>-->
								</td>
							</tr>
							<?
						}?>
					</tbody>
				</table>
			<?}else{?>
				<div class="panel-body">
					<h4>Ни одного пользователя не создано.</h4>
				</div>
			<?}?>
		</div>
	</div>
</div>
