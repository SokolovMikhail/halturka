<?
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\modules\admin\models\InstallationStage;
?>
<div class="row">
	<div class="col-md-12">
		<? $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'data-stage-form' => '123', 'data-stage-submit-url' => '/admin/configuration/update-stage/?id=' . $stage->id], 'action' => '/admin/configuration/update-stage/?id=' . $stage->id]); ?>
			<?= $form->field($stage, 'text')->textArea(['maxlength' => 20000, 'rows' => 3]) ?>
			<?= $form->field($stage, 'sort')->dropDownList(InstallationStage::getStagesList()) ?>
			<?= $form->field($uploadForm, 'imageFile[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>			
			<a class="example-image-link" href="/rest/images/get/?id=<?= $stage->attachemnt_id?>" data-lightbox="example-set-5" title=""><img src="/rest/images/get/?id=<?= $stage->attachemnt_id?>" class="img-rounded  mt-10"></a>
			<a class="btn btn-warning mt-10" data-stage-hide="<?= $stage->id?>">Скрыть</a>
			<?= Html::csrfMetaTags() ?>
		<? ActiveForm::end(); ?>	
	</div>
</div>