<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Ratinginfo $ratinginfo
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $ratinginfo->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $ratinginfo->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Ratinginfo'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="ratinginfo form large-9 medium-8 columns content">
    <?= $this->Form->create($ratinginfo) ?>
    <fieldset>
        <legend><?= __('Edit Ratinginfo') ?></legend>
        <?php
            echo $this->Form->control('bidinfo_id');
            echo $this->Form->control('user_id', ['options' => $users]);
            echo $this->Form->control('rating_score');
            echo $this->Form->control('rating_msg');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
