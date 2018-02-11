<?
use frontend\widgets\HoursRangePicker\HourRangeAsset;


HourRangeAsset::register($this);
?>
<div class="row">
	<div class="col-md-12">
		<label class="control-label" for="smsdistribution-distribution">Выберите временные промежутки, в которые вы бы хотели получать уведомления</label>
	</div>
	<div class="col-md-12">
		<span class="js-link filter_check-list-control" data-clear-hours="">Очистить выбранные</span>
		<span class="js-link" data-fill-hours="">Выбрать все</span>	
	</div>

	<div class="col-md-12 mt-5">
		<?for($i = -1; $i<24; $i++){?>
			<label class="ta-c hour-label clickable" data-hour-day=""><?= $i>-1 ? $i : ' '?></label>
		<?}?>
	</div>

<?for($i = 0; $i<7; $i++){?>
	<div class="col-md-12 mg-0" data-hours-row="<?= $i?>">
		<?for($j = 0; $j<24; $j++){?>
			<?if($j==0){?>
				<label class="hour-day ta-c clickable" data-day-week=""><?= $daysOfWeek[$i]?></label>
				<div class="toggle-button toggle-button--tuuli mg-0">
					<input class="mg-0" id="toggleButton<?= $j + $i * 24?>" type="checkbox" data-hour-checkbox="<?= $j + $i * 24?>" <?= (isset($array[$i][$j]) && ($array[$i][$j])) ? 'checked' : ''?> name="<?= $modelName?>[<?= $i?>][<?= $j?>]">
					<label class="mg-0 checkbox-border" for="toggleButton<?= $j + $i * 24?>"></label>				
				</div>			
			<?}else{?>
				<div class="toggle-button toggle-button--tuuli mg-0">
					<input class="mg-0" id="toggleButton<?= $j + $i * 24?>" type="checkbox" data-hour-checkbox="<?= $j + $i * 24?>" <?= (isset($array[$i][$j]) && ($array[$i][$j])) ? 'checked' : ''?> name="<?= $modelName?>[<?= $i?>][<?= $j?>]">
					<label class="mg-0 checkbox-border" for="toggleButton<?= $j + $i * 24?>"></label>				
				</div>
			<?}?>
		<?}?>
	</div>
<?}?>
</div>