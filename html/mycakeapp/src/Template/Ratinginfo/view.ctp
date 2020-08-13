<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Ratinginfo $ratinginfo
 */
?>
<div class="ratinginfo view large-9 medium-8 columns content">
    <h3><?= $ratingAvg->user->username ?>の評価</h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('平均評価') ?></th>
        </tr>
        <tr>
            <td><?= !empty($ratingAvg) ? round($ratingAvg->avg, 2) : '' ?></td>
        </tr>
    </table>
    <table>
        <tr>
            <th scope="row"><?= __('評価') ?></th>
            <th scope="row"><?= __('評価コメント') ?></th>
        </tr>
        <?php foreach ($allRate as $rate) : ?>
            <tr>
                <td><?= $this->Number->format($rate->rating_score) ?></td>
                <td><?= !empty($rate->rating_msg) ? h($rate->rating_msg) : 'なし' ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
