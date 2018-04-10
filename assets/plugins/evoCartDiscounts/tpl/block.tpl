<div class="block" data-config="<?= $block['config'] ?>">
	<div class="block-inner">


		<?php if (!empty($block['id'])): ?>
			<style>
				.dropdown-add-block { display: none;}
			</style>
			<input type="hidden" name="contentblocks_id" value="<?= $block['id'] ?>">
		<?php endif; ?>

		<div class="fields-list">
			<?php if (!isset($configs[$block['config']]['fields'])): ?>
				<b><?= $configs[$block['config']]['title'] ?></b><br>
				<i><?= $l['No fields provided in this block'] ?></i>
			<?php else: ?>
				<?php foreach ($configs[$block['config']]['fields'] as $name => $field): ?>
					<?= $this->renderField($field, $name, isset($block['values'][$name]) ? $block['values'][$name] : null); ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>


	</div>

</div>
