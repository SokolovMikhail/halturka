<select name="<?= $name?>" class="filter-field">	
	<? for($i=$start; $i<=$end; $i++){?>
	<option value="<?= $i?>" <?= $i==$current ? 'selected' : ''?>>
	<?= $i?>:00
	</option>
	<? }?>
</select>