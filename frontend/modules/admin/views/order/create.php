<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use frontend\modules\admin\assets\AdminAsset;

AdminAsset::register($this);

$this->params['main_nav_current'] = 'admin/order';
$this->title = 'Создать новую заявку';
$this->params['header_links'] = [
	[
		'icon'	=> 'fa-arrow-circle-left',
		'title'	=> 'Назад к списку заявок',
		'link'	=> '/admin/order/',
	]
];
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<div class="panel-body">
				<? $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
				<?= $this->render('_form', [
					'model'			=> $model,  
					'cardTypes'		=> $cardTypes,
					'operators' => $operators,
					'clients' => $clients,
					'files' => $files,
					'form' => $form,
					'parents' => $parents,
				]) ?>
			<?= Html::csrfMetaTags() ?>
			<? ActiveForm::end(); ?>				
			</div>
		</div>
	</div>
</div>
