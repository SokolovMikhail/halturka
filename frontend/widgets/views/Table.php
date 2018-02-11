<<?=$container?> class="">
	<? foreach($fields as $fieldName=>$field){?>
	<?if(isset($field['views'])&&!in_array('web', $field['views'])){
		continue;
	}?>	
	<td class="<?= isset($field['class']) ? $field['class'] : 'class'?>">
		<? if(isset($items[$fieldName])){?>
			<?if(isset($field['type'])){?>
				<?if($field['type']=='link'){?>
				<a href="<?= $field['href']?><?= $items['id']?>"><?=$items[$fieldName]?></a>
				<?}?>
				<?if($field['type']=='hours_decimal'){?>
					<?= round($items[$fieldName]/3600, 2)?>
				<?}?>						
			<?}else{?>
				<?=$items[$fieldName]?>
			<?}?>
		<?}?>
	</td>
	<? }?>
</<?=$container?>>
