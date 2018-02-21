<?php
use frontend\assets\AccountAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
/* @var $this \yii\web\View */
/* @var $content string */

AccountAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => 'Legal-Bot',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [

                    'class' => 'navbar-default navbar-fixed-top',

                ],
            ]);
            $menuItems = [
                ['label' => 'Главная', 'url' => ['/site/index']],
            ];

            // if (Yii::$app->user->isGuest) {
                // $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
            // } else {
                // $menuItems[] = [
                    // 'label' => 'Выйти (' . Yii::$app->user->identity->username . ')',
                    // 'url' => ['/site/logout'],
                    // 'linkOptions' => ['data-method' => 'post']
                // ];
            // }

            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]);
            NavBar::end();
        ?>
        <div class="container">
			<div class="row">
				<div class="col-xs-12 mb-20">
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
			</div>
			<?= Breadcrumbs::widget([
				'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
			]) ?>
			<?= $content ?>
        </div>
    </div>


    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
