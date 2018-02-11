<? $this->beginContent('@app/views/layouts/base.php'); ?>
<? if(isset($this->params['account_subnav'])){?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<div class="panel-body account_subnav">
				<ul class="nav navbar-nav">
					<?foreach($this->params['account_subnav'] as $item){?>
					<li>
						<a href="<?= $item['link']?>" title="<?= $item['title']?>" <?= $item['active'] ? 'class="curent-nav"' : ''?>>
							<?= $item['title']?>
						</a>
					</li>
					<?}?>
				</ul>
			</div>
		</div>
	</div>
</div>
<?}?>
<?= $content ?>
<? $this->endContent(); ?>