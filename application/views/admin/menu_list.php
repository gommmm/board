<?php foreach($menu_list as $menu) : ?>
<li id="<?=$menu['bc_idx']; ?>" class="selectable <?=$menu['indent'] == 1 ? 'indent ' : ''; ?>ui-sortable-handle">
	<p class="<?=$menu['type'] != '' ? $menu['type'] : 'group'; ?>">
		<?=$menu['bc_name']; ?>
	</p>
</li>
<?php endforeach; ?>