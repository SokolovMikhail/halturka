<?= $form->field($model, 'timeout')->textInput([
		'class'				=> 'slider-input',
		'data-slider-min'	=> '0',
		'data-slider-max'	=> '100',
		'data-slider-value'	=> $model->timeout ? $model->timeout : 0,
	])->hint('(0 - не отключать зажигание по таймауту)');?>