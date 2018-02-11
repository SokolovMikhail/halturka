<?
use yii\widgets\Pjax;
use frontend\models\helpers\ViewHelper;
?>

<? Pjax::begin(['id' => 'pjax-control-container-access-list'])?>

<div class="col-xs-12 tree-ul">
	<? if(!isset($model->license_id) || !$model->license_id){?>
	<? if($model->isNewRecord){?>
	<h3>Сохраните технику, чтобы настроить права доступа</h3>
	<? }else{?>
	<h3>Выберите категорию прав доступа к технике</h3>
	<? }?>

	<?} elseif(count($operatorsByStorage)){?>

	<? $tree = ViewHelper::itemsInStoragesTreeBuilder(
		Yii::$app->storagesData->find()->onlyActive(false)->lowTypes()->asTree(),
		$operatorsByStorage, 
		0, 
		'Devices[deviceAccessArray]', 
		$model->deviceAccess
	)?>
	<?=  $tree['output']?>

	<? } else{?>

	<h3>Нет операторов с данной категорией прав</h3>

	<? }?>
</div>

<? Pjax::end(); ?>