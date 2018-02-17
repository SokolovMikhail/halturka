<?php
/* @var $this yii\web\View */



$this->title = 'Вопросы к иску '.$quiz->name;
$this->params['header_links'] = [
	[
		'icon'	=> 'fa-arrow-circle-left',
		'title'	=> 'Назад к списку опросов',
		'link'	=> '/quiz/index/?topicId='.$topicId,
	],
];
?>
<div class="row">
	<div class="col-xs-12">				
			<a href="/question/create/?quizId=<?= $quizId?>" class="btn btn-primary mb-10" data-stage-topic-add="">Добавить вопрос</a>					
	</div>				
</div>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Опросы по теме</h3>
				<div class="pull-right">
					<span class="clickable filter" data-toggle="tooltip" title="Поиск" data-container="body">
						<i class="glyphicon glyphicon-search"></i>
					</span>
				</div>
			</div>
			<div class="panel-body-hide">
				<input type="text" class="form-control mt-10 mb-10" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Темы исков" />
			</div>
			<table class="table table-hover" id="dev-table">
				<thead>
					<tr>
						<th>#</th>
						<th>Вопрос</th>
						<th></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?$i = 1;?>
					<?foreach($questions as $item){?>
					<tr data-question-row="<?= $item['id']?>">
						<td style="width: 10%;"><?= $i?></td>
						<td style="width: 85%;"><a href="/question/update/?id=<?= $item['id']?>"><?= $item['text_native']?></a></td>
						<td><a href="/question/update/?id=<?= $item['id']?>"><i class="fa fa-pencil clickable text-black" aria-hidden="true" data-toggle="tooltip" title="Редактировать"></i></a></td>
						<td><i class="fa fa-times clickable text-red" aria-hidden="true" data-toggle="tooltip" title="Удалить" data-question-delete="<?= $item['id']?>"></i></td>
					</tr>							
					<?$i++;}?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<a href="/question/create/?quizId=<?= $quizId?>" class="float clickable" data-float-button-target="data-stage-topic-add" data-toggle="tooltip" data-placement="left" title="Добавить вопрос">
	<i class="fa fa-plus my-float "></i>
</a>