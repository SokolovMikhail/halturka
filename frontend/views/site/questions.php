<?php
/* @var $this yii\web\View */

$this->title = '';
?>
<div class ="row">
	<div class ="col-md-6 col-md-offset-3">
		<div class = "name-questions"><?foreach($quiz as $quiz){?>
			<option> <?= $quiz['name']?></option> 
			<?}?>
		</div>
	</div>
</div>
