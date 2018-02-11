<?
$this->title = 'Отделы';
$this->params['main_nav_current'] = 'storages';
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<div class="panel-body">
				<a href="/storages/create/" class="btn btn-primary">Добавить новый отдел</a>
			</div>
			<?if($result['storagesTree']){?>
				<div class="panel-body tree-ul">
					<?= $result['storagesTree']['output']?>
				</div>
			<?}else{?>
				<div class="panel-body">
					<h4>Ни одного отдела не добавлено.</h4>
				</div>
			<?}?>
		</div>
	</div>
</div>