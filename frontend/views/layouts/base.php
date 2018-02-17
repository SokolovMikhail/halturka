<?php
use yii\helpers\Html;
// use yii\bootstrap\Nav;
// use yii\bootstrap\NavBar;
// use yii\widgets\Breadcrumbs;
use frontend\assets\AccountAsset;
use frontend\assets\BootboxAsset;
use frontend\widgets\NavMenu\NavMenuWidget;
use frontend\widgets\NavMenu\NavMenuAsset;

AccountAsset::register($this);
BootboxAsset::overrideSystemConfirm();
$this->beginPage()
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1 user-scalable=1">
	<meta name="robots" content="noindex,nofollow">
	<link rel="shortcut icon" href="/favicon.ico?v=2" type="image/x-icon">
	<!--meta name="mobile-web-app-capable" content="yes"-->
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body data-spy="scroll" data-target="#help" data-offset="150" data-offset-top="120">
    <?php $this->beginBody() ?>

		<div id="wrapper" class="vis">
        <div class="overlay"></div>
		
		<div class="main">
			<div class="container">
				<?if(isset($this->title) && $this->title){?>
				<div class="row">
					<div class="col-xs-12 mb-20">
						<h1 class=""><?= Html::encode($this->title)?></h1>
						<? if(isset($this->params['header_links'])){?>
						<div class="page-title_links">
							<? foreach($this->params['header_links'] as $item){?>
							<? if(isset($item['roles']) && !array_intersect(Yii::$app->config->params['user']['roles'], $item['roles'])){?>
							<? continue?>
							<? }?>
							<?
							$options = '';
							if(isset($item['options'])){
								foreach($item['options'] as $optionName=>$optionValue){
									$options .= "$optionName = '$optionValue' ";
								}
							}
							?>
							<a href="<?= $item['link']?>" class="page-title_links-item" <?= $options?>>
								<? if(isset($item['icon'])){?><span class="fa <?= $item['icon']?>"></span><? }?><?= $item['title']?>
							</a>
							<? }?>
						</div>
						<? }?>
					</div>
					
					<?if(Yii::$app->session->hasFlash('error')){?>
					<div class="col-xs-12 mb-20">
						<div class="alert alert-warning alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<?= Yii::$app->session->getFlash('error');?>
						</div>
					</div>
					<?}?>					
					<?if(Yii::$app->session->hasFlash('success')){?>
					<div class="col-xs-12 mb-20">
						<div class="alert alert-success alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<?= Yii::$app->session->getFlash('success');?>
						</div>			
					</div>
					<?}?>	
					
				</div>
				<?= $content ?>				
				
				<?}?>
			</div>
		


			<footer class="footer">
				<div class="container">
				<p class="pull-left" data-test-two>&copy; Техновизор <?= date('Y') ?></p>
				<p class="pull-right"></p>
				</div>
			</footer>
		</div>
    <?php $this->endBody() ?>
	
	<script type="text/javascript">
	$(document).on('click', '[data-toggle="lightbox"]', function(event) {
		event.preventDefault();
		$(this).ekkoLightbox();
	});
	</script>
	
	<div class="loading">
		<div class="loading-child">
			<div id="floatingCirclesG">
				<div class="f_circleG" id="frotateG_01"></div>
				<div class="f_circleG" id="frotateG_02"></div>
				<div class="f_circleG" id="frotateG_03"></div>
				<div class="f_circleG" id="frotateG_04"></div>
				<div class="f_circleG" id="frotateG_05"></div>
				<div class="f_circleG" id="frotateG_06"></div>
				<div class="f_circleG" id="frotateG_07"></div>
				<div class="f_circleG" id="frotateG_08"></div>
			</div>
		</div>
		<div class="loading-helper"></div>
	</div>
	<div class="scroll-top-wrapper ">
	<span class="scroll-top-inner">
		<i class="fa fa-2x fa-arrow-circle-up"></i>
	</span>
	</div>	
</body>
</html>
<?php $this->endPage() ?>
