<?
use frontend\models\helpers\ViewHelper;
?>
<div class="tree-ul">
	<?echo ViewHelper::buildTree($storages, 0, $fieldName, $active, $dataFilterName, $current_storages);?>
</div>

