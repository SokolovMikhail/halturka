<?switch ($viewType){
	case 'check':
		?>
		<ul class="ls-n filter_check-list"  data-filter-check-list>
			<?foreach($items as $k=>$item){?>
			<li>
				<input type="checkbox" name="<?=$feildName?>[<?=$k?>]" value="<?=$item['id']?>" <?= in_array($item['id'], $active) ? 'checked' : ''?>><?=$item['name']?>
			</li>
			<?}?>
		</ul>
		<span class="js-link filter_check-list-control" data-clear-filter-check-list>очистить выбранное</span> <span class="js-link" data-set-filter-check-list>выбрать все</span>
		<?
		break;

	case 'btn':
		?>
		<div class="btn-group" data-toggle="buttons">	
			<?foreach($items as $k=>$item){?>
			<label class="btn device-cat-btn <?= in_array($item['id'], $active) ? 'active' : ''?>" title="<?=$item['name']?>">
				<input type="checkbox" 
					autocomplete="off" 
					name="<?=$feildName?>[<?=$k?>]" 
					value="<?=$item['id']?>" 
					<?= in_array($item['id'], $active) ? 'checked' : ''?> 
					data-related-checkbox="data-devices-category-<?=$item['id']?>">
				<span class="fontello icon-device-cat-<?=$item['icon']?>"></span>
			</label>
			<?}?>
		</div>
		<?
		break;
}?>