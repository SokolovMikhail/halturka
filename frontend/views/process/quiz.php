<?php
/* @var $this yii\web\View */
use yii\bootstrap\ActiveForm;
use frontend\assets\CheckboxAsset;

CheckboxAsset::register($this);
$this->title = '';
?>
    <div class="row chat-window col-xs-12" id="chat_window_1" style="margin-left:10px;">
        <div class="col-xs-12 col-md-12">
        	<div class="panel panel-default chat">
                <div class="panel-heading top-bar">
                    <div class="col-md-8 col-xs-8">
                        <h3 class="panel-title mt-20"><span class="glyphicon glyphicon-comment"></span> Chat</h3>
                    </div>
                </div>
                <div class="panel-body msg_container_base">
 
                    <div class="row msg_container">
                        <div class="col-md-2 col-xs-2 avatar ">
                            <img src="\img\avatar1.png" class=" img-responsive avatar">
                        </div>
                        <div class="col-md-10 col-xs-10">
                            <div class="messages msg_receive">
								<?= $question['text_native']?>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="panel-footer">
					<?php $form = ActiveForm::begin(['id' => 'topic-form']); ?>
						<?if($question['type'] == 0){?>
						<?foreach($answers as $item){?>
						<div class="form-check">
							<label>
								<input type="radio" name="AnswerForm[answer]" value="<?= $item['id']?>" 
								<?= $item['quiz_redirect_id'] ? 'data-redirect-id="'.$item['quiz_redirect_id'].'"' : ''?>
								>
								<span class="label-text"><?= $item['text_native']?></span>
							</label>
						</div>
						<?}?>
						<?}else{?>
							<div class="form-group">
							<label class="control-label" for="question-text_native">Открытый ответ</label>
							<textarea class="form-control" name="AnswerForm[answer]" maxlength="4096" rows="4" aria-invalid="false"></textarea>
							</div>						
						<?}?>
						<div class = "ta-c"><button type="submit" class="btn btn-primary button-answer ">
						Ответить
						</button></div>
						
						
						<nav aria-label="...">
						  <ul class="pager"><div>
							<li class="previous"><a href="#"><span aria-hidden="true">&larr;</span> К предыдущему вопросу</a></li></div>
						  </ul>
						</nav>
										
					<?php ActiveForm::end(); ?>					                   
                </div>
    		</div>
        </div>
    </div>

