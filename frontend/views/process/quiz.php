<?php
/* @var $this yii\web\View */
use yii\bootstrap\ActiveForm;
use frontend\assets\CheckboxAsset;

CheckboxAsset::register($this);
$this->title = 'Опрос';
?>
<div class="row chat-window col-xs-12" id="chat_window_1" style="margin-left:10px;">
	<div class="col-xs-12 col-md-12">
		<div class="panel panel-default chat">
            <div class="panel-heading top-bar pd-t-b-0">
                <div class="col-md-8 col-xs-8 mt-10">
					<?if($prevQuestionId){?>
						<?if($prevQuestionId == 'first'){?>
						<nav>
						<ul class="pager"><div>
							<li class="previous"><a href="/process/index/?quizId=<?= $quiz->id?>"><span aria-hidden="true">&larr;</span> К предыдущему вопросу</a></li></div>
						</ul>
						</nav>						
						<?}else{?>
						<nav>
						<ul class="pager"><div>
							<li class="previous"><a href="/process/index/?quizId=<?= $quiz->id?>&questionId=<?= $prevQuestionId?>"><span aria-hidden="true">&larr;</span> К предыдущему вопросу</a></li></div>
						</ul>
						</nav>
						<?}?>
                    <?}?>
                </div>
            </div>
            <div class="panel-body msg_container_base" data-message-box="">
				<?foreach($messages as $item){?>
					<div class="row msg_container <?= $item['class']?>">
						<div class="row msg_container" style="width: 500px;">
							<div class="col-xs-2">
							<?if($item['class'] == 'base_recive'){?>							
								<img src="\img\avatar1.png" class="img-circle avatar">							
							<?}else{?>
								<div class="avatar-user">
									<img src="\img\user.png" class="img-circle avatar">
								</div>							
							<?}?>
							</div>
							<div class="col-xs-10">
								<div class="messages <?= $item['class'] == 'base_recive' ? 'msg_recive' : 'msg_sent'?>">
									<?= $item['text']?>
								</div>
							</div>
						</div>
					</div>				
				<?}?>			
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
				<?php ActiveForm::end(); ?>					                   
            </div>			
        </div>
		
		
    </div>
</div>
