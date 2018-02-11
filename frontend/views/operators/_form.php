<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\widgets\Alert;

$this->params['header_links'] = [
	[
		'icon'	=> 'fa-arrow-circle-left',
		'title'	=> 'Назад к списку операторов',
		'link'	=> '/operators/#item-'.$model->id,
	],
	[
		'icon'	=> 'fa-plus-circle',
		'title'	=> 'Добавить оператора',
		'link'	=> '/operators/create/',
		'roles'	=> ['superadmin', 'admin'],
	],
];

extract($formParams);
?>
<?= Alert::widget()?>
<div class="operators-form">
	<? $form = ActiveForm::begin()?>
	<div class="row">
		<div class="col-xs-6">
			<?= $form->field($model, 'name')->textInput(['maxlength' => 255])?>
			
			<? if(isset(Yii::$app->config->params['account']['wms'])){?>
			<?= $form->field($model, 'ext_id')->textInput(['maxlength' => 255])?>
			<? }?>
			
			<?= $form->field($model, 'storage_id')->dropDownList($storagesSelect['output']); ?>
			<?= $form->field($model, 'active')->dropDownList(['1' => 'Да', '0' => 'Нет']); ?>
			
			<? if(isset(Yii::$app->modules['speedcontrol'])){?>
			<?= $form->field($model, 'speed_limit')->textInput([
				'class'					=> 'slider-input-float',
				'data-slider-min'		=> '0',
				'data-slider-max'		=> '25',
				'data-slider-step'		=> '0.1',
				'data-slider-value'		=> $model->speed_limit ? $model->speed_limit : 0,
			])->hint('(0 - не ограничивать скорость)')?>
			<? }?>
		</div>
		<div class="col-xs-6">
			<div class="form-group field-operators-rolearray">
				<label class="control-label">Роль</label>
				<input type="hidden" name="Operators[roleArray]" value="">
				<div id="operators-rolearray" class="labels-list" data-checkbox-group>
					<? foreach($model->roles as $roleData){?>
					<label>
						<input type="checkbox" 
							name="Operators[roleArray][]" 
							value="<?= $roleData['id']?>" 
							<?= in_array($roleData['id'], $model->roleArray) ? 'checked' : ''?>
							<? foreach($roleData['data'] as $dataParam){ ?>
							<?= 'data-'.$dataParam.' ' ?>
							<? }?>
							> <?= $roleData['title']?>
						<div class="hint-block"><?= $roleData['description'] ?></div>
					</label>
					<? }?>
				</div>
				<div class="help-block"></div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<h3>Права на управление техникой</h3>
		</div>
		<div class="col-xs-12 check-box-list">
			<? if(!$licenses){?>
			Нет активных категорий прав
			<? }?>
			<?= $form->field($model, 'licensesArray')->checkBoxList($licenses, ['data-pjax-control'=>'access-list', 'data-pjax-url'=>'/operators/get-access-list/'.$model->id.'/'])->label(false) ?>
		</div>
		<div class="col-xs-12">
			<h3>Доступ к технике</h3>
		</div>
		<div class="col-xs-3 <?= count($rootStorages)>1 ? '' : 'dsp-n'?>">
			<?= $form->field($model, 'rootStorage')->dropDownList($rootStorages, ['data-pjax-control'=>'access-list', 'data-pjax-url'=>'/operators/get-access-list/'.$model->id.'/'])?>
		</div>
		<div class="col-xs-12">
			<?= $accessList?>
		</div>
		<? if(!$model->isNewRecord){?>
		<div class="col-xs-12">
			<div class="form-group">
				<?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
			</div>
		</div>
		<?} else{?>
		<div class="col-xs-12">
			<div class="form-group">
				<?= Html::submitButton($card_id ? 'Сохранить оператора и привязать к нему карту № '.$card_id : 'Сохранить', ['class' => 'btn btn-success']) ?>
			</div>
		</div>
		<? }?>
	</div>
	<? ActiveForm::end();?>
</div>
