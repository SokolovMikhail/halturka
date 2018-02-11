<?
use yii\helpers\ArrayHelper;
?>
<?= $form->field($model, 'access_control')->dropDownList(ArrayHelper::map($model::$accessMode, 'id', 'title')); ?>