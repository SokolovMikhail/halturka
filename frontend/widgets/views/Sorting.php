<? foreach($fields as $name=>$f){?>
	<?if(isset($f['views']) && !in_array('web', $f['views'])){
		continue;
	}?>
<<?=$container?> class="<?= isset($f['class']) ? $f['class'] : ''?> report-table_table-head">
	<span class="report-table_sortable-column <?= $sorting['field']==$name ? 'active '.$order[$sorting['order']] : 'sort-asc'?>" 
		data-sort-table-by="<?= $sorting['field']==$name ? $sorting['val'] : $name?>">
		<span class="column-name"><?= $f['title']?></span>
		<span class="column-arr"></span>
	</span>
</<?=$container?>>
<? }?>