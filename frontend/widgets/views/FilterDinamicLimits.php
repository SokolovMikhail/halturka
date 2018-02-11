<?
if(is_array($crashLimit) && array_sum($crashLimit)){
	$collapsClass = 'in';
	$nodeClass = '';
	$firstStorage = false;
}
else{
	$collapsClass = '';
	$nodeClass = 'collapsed';
}
?>
<div class="col-xs-12">
	<span class="<?=$nodeClass?>" data-toggle="collapse" data-target="#crashes-limits">
		<span class="fontello tree-node_status-icon"></span>
		<span class="js-link fs-14">
			<b><?=$title?></b>
		</span>
	</span>
	<div id="crashes-limits" class="row collapse <?=$collapsClass?>">
	<br>
	<?foreach($crashesTypes as $k=>$v){?>
		<div class="col-xs-4">
			<div class="row">
				<div class="col-xs-3">
					<label><?=$v['name']?>:</label>
				</div>
				<div class="col-xs-9">
					<input name="crash_limit[<?=$k?>]" type="text" class="slider-input" 
						value="<?=isset($crashLimit[$k]) ? $crashLimit[$k] : 0?>" 
						data-slider-min="0" 
						data-slider-max="100" 
						data-slider-step="1"
						data-slider-orientation="horizontal" 
						data-slider-selection="before"
						data-slider-tooltip="show"
						data-slider-value="<?=isset($crashLimit[$k]) ? $crashLimit[$k] : 0?>"
					>
				</div>
			</div>
		</div>
	<?}?>
	</div>
</div>