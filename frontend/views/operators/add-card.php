<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\Devices;
use frontend\models\Report;

$report = new Report;
$this->title = 'Добавить карту доступа для оператора';
$this->params['main_nav_current'] = 'operators';
$devices = Devices::getDevicesStatus(['id'=>$report->checkItemsAccessPermission([], 'device')]);
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<div class="panel-heading tab-bg-dark-navy-blue" data-nav-tabs-linked>
				<ul class="nav nav-tabs">
					<li class="active">
						<a data-toggle="tab" href="#tab1">Добавить через считыватель</a>
					</li>
					<li class="">
						<a data-toggle="tab" href="#tab2">Добавить код карты вручную</a>
					</li>
				</ul>
			</div>
			<div class="panel-body">
				<div class="tab-content">
					<div id="tab1" class="tab-pane active">
					<?if(count($devices))
					{?>
						<h2>Выберите технику для добавления карты:</h2>
						<p>
							Карта добавляется через считыватель на погрузчике.
						</p>
						<p>
							<form data-get-card-form>
							<input type="hidden" name="operator" value="<?=$operatorId?>"/>
								<ul class="ls-n radio-ul">
								<?
								$i = 0;
								foreach($devices as $device)
								{
									$i++;
									if(isset($device['is_active']) && (($device['is_active']==1)||($device['is_active']==2)||($device['is_active']==3))){
										$class = 'icon-ok text-green';
										$status = 'на связи';
									}
									else{
										$class='icon-cancel text-grey';
										$status = 'нет связи';
									}
									?>
									<li>
										<input type="radio" name="device" id="device<?=$device['id']?>" value="<?=$device['id']?>" <?=$i == 1 ? 'checked' : ''?>/>
										<label for="device<?=$device['id']?>">
											<?=$device['name']?> (<span class="fontello <?=$class?>"></span> <?= $status?>)
										</label>
									</li>
									<?
								}?>
								</ul>
							</form>
						</p>
						<p>
							<a href="#" class="btn btn-primary" data-get-card-btn>Добавить карту</a>
						</p>
						<div class="row dsp-n preloader">
							<div class="col-xs-1">
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
							<div class="col-xs-11" style="padding-top:12px">
								<p>
									<b>
									Поднесите карту к считывателю погрузчика.<br/>
									Ожидание карты длится не более 60 секунд.
									</b>
								</p>
							</div>
						</div>
						<p class="bg-danger message dsp-n" data-get-card-error>
							<span data-get-card-error-text>
							</span> 
							<br/><br/>
							<a href="#" class="btn btn-primary" onclick="$('[data-get-card-btn]').trigger('click');">Повтор</a>&nbsp;&nbsp;&nbsp;
							<a href="/operators/update/<?=$operatorId?>/" class="btn btn-primary" >Отмена</a>
						</p>
						<?
					}
					else{?>
						<h2>Нет активных устройств</h2>
						<p>
							Включите питание на одном из устройств, а затем обновите страницу
						</p>
					<?}?>
					</div>
					<div id="tab2" class="tab-pane">
						<h2>Введите код карты</h2>
						<p>
							Если у вас имеется код карты - введите его в поле и нажмите "Добавить карту":
						</p>
						<div class="operators-form row">
							<div class="col-xs-6 mb-20">
								<?$form = ActiveForm::begin(); ?>
									<?= $form->field($card, 'id')->textInput(['maxlength' => 10]) ?>
									<?= Html::submitButton('Добавить карту', ['class' => 'btn btn-primary']) ?>
								<?ActiveForm::end();?>
							</div>
							<div class="col-xs-12 mb-20">
								<?if(Yii::$app->session->hasFlash('error')){?>
									<p class="bg-danger message">
										<?= Yii::$app->session->getFlash('error');?>
									</p>
								<?}?>
								<?if(Yii::$app->session->hasFlash('success')){?>
									<p class="bg-success message">
										<?= Yii::$app->session->getFlash('success');?>
									</p>
								<?}?>
							</div>
							<div class="col-xs-12">
								<h3>Где взять код карты?</h3>
								<p>
									Возможное расположение кода карты показано на рисунке (либо 2 числа через запятую, либо 1 число с нулями в начале):
								</p>
								<img src="/img/em-marine.jpg"/>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>