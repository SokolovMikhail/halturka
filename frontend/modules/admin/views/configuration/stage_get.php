<?
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<input type="hidden" data-stage-id="<?= $stage->id?>" value="<?= $stage->id?>">
<div class="row">
	<div class="col-xs-12">												
		<button type="button" class="close-button" data-delete-stage-button=""><span aria-hidden="true" class="text-red">×</span></button>
		<button type="button" class="edit" data-edit-stage-button=""><span class="fa fa-pencil" aria-hidden="true"></span></button>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">																			
		<div style="font-size: 14px;"><?= $stage->sort?>. <?= $stage->text?></div>
		<?if($stage->attachemnt_id){?>
			<a class="example-image-link" href="/rest/images/get/?id=<?= $stage->attachemnt_id?>" data-lightbox="example-set-30" title="<?= $stage->text?>"><img src ="/rest/images/get/?id=<?= $stage->attachemnt_id?>"class="img-rounded  mt-10"></a>
		<?}?>
	</div>
</div>