<h2>商品を出品する</h2>
<?= $this->Form->create($biditem, ['enctype' => 'multipart/form-data']) ?>
<fieldset>
	<legend>※商品名と終了日時を入力：</legend>
	<?php
		echo $this->Form->hidden('user_id', ['value' => $authuser['id']]);
		echo '<p><strong>USER: ' . $authuser['username'] . '</strong></p>';
		echo $this->Form->control('name');
		echo $this->Form->control('detail', ['type' => 'textarea', 'label' => '詳細情報']);
		echo $this->Form->file('image_path', [
			'accept' => 'image/jpeg, image/png, image/gif',
			'label' => '商品画像 ※ 拡張子は「jpeg」「png」「gif」のみ。（大文字可）'
		]);		
		echo $this->Form->hidden('finished', ['value' => 0]);
		echo $this->Form->control('endtime');
	?>
</fieldset>
<?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>
