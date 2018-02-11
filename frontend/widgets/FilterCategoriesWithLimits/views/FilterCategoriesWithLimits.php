<?
use frontend\widgets\FilterDinamicLimitsWidget;
?>
<?switch ($viewType){
	case 'btn':
		?>
		<div class="btn-group" data-toggle="buttons">
			<?$i = 0;?>
			<?foreach($items as $k=>$item){?>
			<label class="btn device-cat-btn <?= in_array($item['id'], $active) ? 'active' : ''?>" title="<?=$item['name']?>" data-label-cat="<?= $i?>">
				<input type="checkbox" 
					autocomplete="off" 
					name="<?=$name?>_list[<?=$k?>]" 
					value="<?=$item['id']?>" 
					<?= in_array($item['id'], $active) ? 'checked' : ''?> 
					data-related-checkbox="data-device-category-<?=$item['id']?>"
					data-input-cat="<?= $i?>">
				<span class="fontello icon-device-cat-<?=$item['icon']?>"></span>
			</label>
			<?$i++;?>
			<?}?>
		</div>
		<?$i = 0;?>
		<ul class="nav nav-tabs inner-nav-tabs mb-20">
			<?foreach($items as $k=>$item){?>
			<li class="<?= $i == 0 ? 'active' : ''?>" data-button-cat-tab="<?= $i?>">
				<a data-toggle="tab" href="#tab-<?= $i?>" class="mb-10 mt-10"><?=$item['name']?></a>
			</li>
			<?$i++;?>
			<?}?>
		</ul>
		<?$i = 0;?>
		<div class="tab-content">
			<?foreach($items as $k=>$item){?>
			<div id="tab-<?= $i?>" class="tab-pane <?= $i == 0 ? 'active' : ''?>" data-button-cat-tab-content="<?= $i?>">
				<?foreach($crashes_types as $k=>$v){?>
					<div class="col-xs-4">
						<div class="row">
							<div class="col-xs-3">
								<label><?=$v['name']?>:</label>
							</div>
							<div class="col-xs-9">
								<input name="crash_limit[<?=$item['id']?>][<?=$k?>]" type="text" class="slider-input" 
									value="<?=isset($crash_limits[$item['id']][$k]) ? $crash_limits[$item['id']][$k] : 0?>" 
									data-slider-min="0" 
									data-slider-max="100" 
									data-slider-step="1"
									data-slider-orientation="horizontal" 
									data-slider-selection="before"
									data-slider-tooltip="show"
									data-slider-value="<?=isset($crash_limits[$item['id']][$k]) ? $crash_limits[$item['id']][$k] : 0?>"
								>
							</div>
						</div>
					</div>
				<?}?>				
			</div>
			<?$i++;?>
			<?}?>			
		</div>		
		
		<?
		break;
}?>