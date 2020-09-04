<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Ratinginfo[]|\Cake\Collection\CollectionInterface $ratinginfo
 */
?>
<h2>ミニオークション!</h2>
<h3>※ユーザー評価リスト</h3>
<table cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th class="main" scope="col"><?= $this->Paginator->sort('id') ?></th>
            <th scope="col"><?= $this->Paginator->sort('username') ?></th>
            <th scope="col"><?= $this->Paginator->sort('avg') ?></th>
            <th scope="col" class="actions"><?= __('Actions') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($ratinginfo as $data) : ?>
            <tr>
                <td><?= h($data->user->id) ?></td>
                <td><?= h($data->user->username) ?></td>
                <td><?= h($data->avg) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'View', $data->user->id])?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="paginator">
    <ul class="pagination">
        <?= $this->Paginator->first('<< ' . __('first')) ?>
        <?= $this->Paginator->prev('< ' . __('previous')) ?>
        <?= $this->Paginator->numbers() ?>
        <?= $this->Paginator->next(__('next') . ' >') ?>
        <?= $this->Paginator->last(__('last') . ' >>') ?>
    </ul>
</div>