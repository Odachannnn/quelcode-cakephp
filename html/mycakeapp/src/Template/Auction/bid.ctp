<h2>「<?= $biditem->name ?>」の情報</h2>
<?php if ($biditem->user_id !== $authuser['id']): ?>
<?= $this->Form->create($bidrequest) ?>
<fieldset>
	<legend><?= __('※入札を行う') ?></legend>
	<?php
		echo $this->Form->hidden('biditem_id', ['value' => $bidrequest->biditems_id]);
		echo $this->Form->hidden('user_id', ['value' => $bidrequest->user_id]);
		echo $this->Form->control('price');
	?>
</fieldset>
<?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>
<?php else : ?>
	<p>※出品者は入札できません</p>
<?php endif; ?>

