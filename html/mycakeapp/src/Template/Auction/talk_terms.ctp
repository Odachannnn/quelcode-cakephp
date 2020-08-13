<?= $this->Flash->render('success') ?>
<?= $this->Flash->render('error') ?>
<h2>取引画面</h2>

<?php
// 取引が終了していなかった場合(お互いの取引終了 === $is_rating_sentの要素の数が 2)
if (count($is_rating_sent) === 2) { ?>
    <h3>取引は終了しました</h3>
<?php  } else {
    echo $this->Form->create($talk);
    echo $this->Form->hidden('Talks.bidinfo_id', ['value' => $bidinfo_id]);
    echo $this->Form->hidden('Talks.user_id', ['value' => $authuser['id']]);
    echo $this->Form->control('Talks.message', ['type' => 'textarea', 'label' => 'メッセージを書き込む']);
    echo $this->Form->button('SEND');
    echo $this->Form->end();
}
?>
<!-- 送付先情報がまだ送られていない、かつアクセスした人間が落札した者である時、送付先フォームを表示する-->
<?php if (empty($is_sendinfo_sent) && $authuser['id'] === $bidData->user_id) : ?>
    <details>
        <summary>送付先住所を送信する</summary>
        <?php
        echo $this->Form->create($sendinfo);
        echo $this->Form->hidden('Sendinfo.bidinfo_id', ['value' => $bidinfo_id]);
        echo $this->Form->hidden('Sendinfo.user_id', ['value' => $authuser['id']]);
        echo $this->Form->control('Sendinfo.name', ['label' => '送付先名前']);
        echo $this->Form->control('Sendinfo.address', ['type' => 'textarea', 'label' => '送付先住所']);
        echo $this->Form->control('Sendinfo.phone_number', ['type' => 'tel', 'label' => '連絡先', 'placeholder' => '(例) 090-1234-5678']);
        echo $this->Form->button('SEND');
        echo $this->Form->end();
        ?>
    </details>
<?php endif; ?>

<!--①送付先情報が送付済み、②発送連絡がまだ、③アクセスした人間が出品者である時のみ、発送連絡ボタンを表示-->
<?php
if (!empty($is_sendinfo_sent) && empty($is_shipping_notice_sent) && $authuser['id'] === $bidData->biditem->user_id) {
    echo $this->Form->create($shippingNotice);
    echo $this->Form->hidden('ShippingNotices.bidinfo_id', ['value' => $bidinfo_id]);
    echo $this->Form->button('発送しました');
    echo $this->Form->end();
}
?>
<!--①発送連絡済み、②受取連絡がまだ、③アクセスした人間が落札者である時のみ、受取連絡ボタンを表示-->
<?php
if (!empty($is_shipping_notice_sent) && empty($is_receiving_notice_sent) && $authuser['id'] === $bidData->user_id) {
    echo $this->Form->create($receivingNotice);
    echo $this->Form->hidden('ReceivingNotices.bidinfo_id', ['value' => $bidinfo_id]);
    echo $this->Form->button('受け取りました');
    echo $this->Form->end();
}
?>

<?php
/**
 * ログインした者がすでに取引評価をしたかどうか？
 * ①落札者、出品者の両者ともしていない === is_rating_sentの要素は0
 * ②相手方のみがした === is_rating_sentの要素は1、かつ$is_rating_sentの要素の'user_id'に自分のidがある
 */ 
if (!empty($is_receiving_notice_sent) && count($is_rating_sent) === 0 || count($is_rating_sent) === 1 && in_array($authuser['id'], array_column($is_rating_sent, 'user_id'))) {
        echo $this->Form->create($ratinginfo);
        echo $this->Form->hidden('Ratinginfo.bidinfo_id', ['value' => $bidinfo_id]);
        // ログインしている人が取引相手の評価できるよう値を設定する
        if ($authuser['id'] === $bidData->user_id) {
            echo $this->Form->hidden('Ratinginfo.user_id', ['value' => $bidData->biditem->user_id]);
        } elseif ($authuser['id'] === $bidData->biditem->user_id) {
            echo $this->Form->hidden('Ratinginfo.user_id', ['value' => $bidData->user_id]);
        }
        echo $this->Form->radio('Ratinginfo.rating_score', [
            ['text' => 'よし', 'value' => 5],
            ['text' => 'よろし', 'value' => 4],
            ['text' => 'ふつう', 'value' => 3, 'checked' => true],
            ['text' => 'わろし', 'value' => 2],
            ['text' => 'わし', 'value' => 1]
        ], ['label' => ['class' => 'rating']]);
        echo $this->Form->control('Ratinginfo.rating_msg', ['type' => 'textarea', 'label' => '評価コメントを記入する（＊空欄可）']);
        echo $this->Form->button(__('取引相手を評価する'));
        echo $this->Form->end();
    }
?>

<table cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th scope="col">送信者</th>
            <th class="main" scope="col">メッセージ</th>
            <th scope="col">送信時間</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($talks)) : ?>
            <?php foreach ($talks as $talk) : ?>
                <tr>
                    <td><?= h($talk->user->username) ?></td>
                    <td><?= h($talk->message) ?></td>
                    <td><?= h($talk->created) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="3">※メッセージがありません。</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<!--送付先情報が送付済み かつ 取引がまだ終わっていない場合に、送付先情報を双方のページに表示する-->
<?php if (!empty($is_sendinfo_sent) && count($is_rating_sent) < 2) : ?>
    <h3>送付先情報</h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col">宛名</th>
                <th class="main" scope="col">住所</th>
                <th scope="col">電話番号</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= h($is_sendinfo_sent->name) ?></td>
                <td><?= h($is_sendinfo_sent->address) ?></td>
                <td><?= h($is_sendinfo_sent->phone_number) ?></td>
            </tr>
        </tbody>
    </table>
<?php endif; ?>