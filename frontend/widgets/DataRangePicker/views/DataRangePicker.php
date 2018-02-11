<div class="data-picker-wrap<?= $timePickerVisibility === 'false' ? ' time-picker-hidden' : ''?>">
	<input type="text" class="filter-field ta-c data-range-picker" 
		name="<?= $name?>" 
		data-daterangepicker
		data-start-date="<?= $startDate->format('d/m/Y H:i')?>"
		data-end-date="<?= $endDate->format('d/m/Y H:i')?>"
		data-use-time-picker="<?= $useTimePicker?>"
		data-single-date-picker="false"
		data-show-ranges = "<?= $showRanges?>"
	>
</div>