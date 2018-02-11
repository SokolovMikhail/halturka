<select name="<?=$name?>" class="filter_items-select filter-field selectpicker" data-live-search="true">
	<? if($heading){?>
	<option value="0" <?= $active ? '' : 'selected'?>><?=$heading?></option>
	<?}?>
	<?
	if(isset($items) && is_array($items)){
		foreach($items as $item){?>
			<option value="<?=$item['id']?>" <?= $active == $item['id'] ? 'selected' : ''?>><?=$item['name']?></option>
			<?
		}
	}
	?>
</select>