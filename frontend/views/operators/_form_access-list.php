<?
use yii\widgets\Pjax;
use frontend\models\helpers\ViewHelper;

extract($result);
?>
<? Pjax::begin(['id' => 'pjax-control-container-access-list'])?>
<ul class="ls-n">
	<? if(is_array($currentLicenses) && count($currentLicenses)){?>
	<? foreach($currentLicenses as $l){?>
	<li>
		<h4><?=$l['name']?></h4>
		<? if(isset($devicesByLicenses[$l['id']])){?>
		<div class="tree-ul alert alert-info <?= in_array($l['id'], $model->licensesArray) ? '' : 'dsp-n'?>" data-tree-node-child>
			<? $tree = ViewHelper::itemsInStoragesTreeBuilder(
				Yii::$app->storagesData->find()->onlyActive(false)->lowTypes()->asTree(),
				$devicesByLicenses[$l['id']], 
				0, 
				'Operators[deviceAccessArray]', 
				$model->deviceAccess, 
				$l['id']
			)?>
			<?=  $tree['output']?>
		</div>
		<? } else{?>
		<p>
			В данной категории нет техники
		</p>
		<? }?>
	</li>
	<? }?>
	<? } else{?>
	<h4>Оператору не присвоено ни одной категории прав</h4>
	<? }?>
</ul>
<? Pjax::end(); ?>