<?php
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">You have successfully<?php
/* @var $this yii\web\View */

$this->title = 'Темы исков';
?>
<div class="row">
	<div class="col-xs-12">				
			<a href="/topic/create/" class="btn btn-primary mb-10" data-stage-topic-add="">Добавить тему</a>					
	</div>				
</div>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Темы исков</h3>
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
						<th>Название темы</th>
						<th></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?$i = 1;?>
					<?foreach($topics as $item){?>
					<tr data-topic-row="">
						<td style="width: 10%;"><?= $i?></td>
						<td style="width: 85%;"><a href="/topic/update/?id=<?= $item['id']?>"><?= $item['name']?></a></td>
						<td><a href="/topic/update/?id=<?= $item['id']?>"><i class="fa fa-pencil clickable text-black" aria-hidden="true" data-toggle="tooltip" title="Редактировать"></i></a></td>
						<td><i class="fa fa-times clickable text-red" aria-hidden="true" data-toggle="tooltip" title="Удалить" data-topic-delete="<?= $item['id']?>"></i></td>
					</tr>							
					<?$i++;}?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<a href="/topic/create/" class="float clickable" data-float-button-target="data-stage-topic-add" data-toggle="tooltip" data-placement="left" title="Добавить тему">
	<i class="fa fa-plus my-float "></i>
</a> created your Yii-powered application.</p>

        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/doc/">Yii Documentation &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/extensions/">Yii Extensions &raquo;</a></p>
            </div>
        </div>

    </div>
</div>
