<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Ratinginfo $ratinginfo
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Ratinginfo'), ['action' => 'edit', $ratinginfo->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Ratinginfo'), ['action' => 'delete', $ratinginfo->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ratinginfo->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Ratinginfo'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Ratinginfo'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="ratinginfo view large-9 medium-8 columns content">
    <h3><?= h($ratinginfo->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $ratinginfo->has('user') ? $this->Html->link($ratinginfo->user->id, ['controller' => 'Users', 'action' => 'view', $ratinginfo->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Rating Msg') ?></th>
            <td><?= h($ratinginfo->rating_msg) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($ratinginfo->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Bidinfo Id') ?></th>
            <td><?= $this->Number->format($ratinginfo->bidinfo_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Rating Score') ?></th>
            <td><?= $this->Number->format($ratinginfo->rating_score) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($ratinginfo->created) ?></td>
        </tr>
    </table>
</div>
