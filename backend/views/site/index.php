<?php
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <div class="body-content">

        <div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">Developers</h3>
						<div class="pull-right">
							<span class="clickable filter" data-toggle="tooltip" title="Toggle table filter" data-container="body">
								<i class="glyphicon glyphicon-filter"></i>
							</span>
						</div>
					</div>
					<div class="panel-body">
						<input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Filter Developers" />
					</div>
					<table class="table table-hover" id="dev-table">
						<thead>
							<tr>
								<th>#</th>
								<th>Название темы</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1</td>
								<td>Kilgore</td>
							</tr>
							<tr>
								<td>2</td>
								<td>Bob</td>
							</tr>
							<tr>
								<td>3</td>
								<td>Holden</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
        </div>

    </div>
</div>
