<?php

namespace App\Controller;

use App\Controller\AppController;

use Cake\Event\Event; // added.
use Exception; // added.
use Cake\Log\Log;


class AuctionController extends AuctionBaseController
{
	// デフォルトテーブルを使わない
	public $useTable = false;

	// 初期化処理
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Paginator');
		// 必要なモデルをすべてロード
		$this->loadModel('Users');
		$this->loadModel('Biditems');
		$this->loadModel('Bidrequests');
		$this->loadModel('Bidinfo');
		$this->loadModel('Bidmessages');
		$this->loadModel('Talks');
		$this->loadModel('Sendinfo');
		$this->loadModel('ReceivingNotices');
		$this->loadModel('ShippingNotices');
		$this->loadModel('Ratinginfo');
		// ログインしているユーザー情報をauthuserに設定
		$this->set('authuser', $this->Auth->user());
		// レイアウトをauctionに変更
		$this->viewBuilder()->setLayout('auction');
	}


	// トップページ
	public function index()
	{
		// ページネーションでBiditemsを取得
		$auction = $this->paginate('Biditems', [
			'order' => ['endtime' => 'desc'],
			'limit' => 10
		]);
		$this->set(compact('auction'));
	}

	// 商品情報の表示
	public function view($id = null)
	{
		// $idのBiditemを取得
		$biditem = $this->Biditems->get($id, [
			'contain' => ['Users', 'Bidinfo', 'Bidinfo.Users']
		]);
		// オークション終了時の処理
		if ($biditem->endtime < new \DateTime('now') and $biditem->finished == 0) {
			// finishedを1に変更して保存
			$biditem->finished = 1;
			$this->Biditems->save($biditem);
			// Bidinfoを作成する
			$bidinfo = $this->Bidinfo->newEntity();
			// Bidinfoのbiditem_idに$idを設定
			$bidinfo->biditem_id = $id;
			// 最高金額のBidrequestを検索
			$bidrequest = $this->Bidrequests->find('all', [
				'conditions' => ['biditem_id' => $id],
				'contain' => ['Users'],
				'order' => ['price' => 'desc']
			])->first();
			// Bidrequestが得られた時の処理
			if (!empty($bidrequest)) {
				// Bidinfoの各種プロパティを設定して保存する
				$bidinfo->user_id = $bidrequest->user->id;
				$bidinfo->user = $bidrequest->user;
				$bidinfo->price = $bidrequest->price;
				$this->Bidinfo->save($bidinfo);
			}
			// Biditemのbidinfoに$bidinfoを設定
			$biditem->bidinfo = $bidinfo;
		}
		// 終了予定時刻=>$biditem['endtime'] と 現在時刻の差分をjsに渡す
		// 終了予定時刻と現在時刻のタイムスタンプを取得する
		$endtime = strtotime($biditem['endtime']);
		$now = time();
		$timediff = $endtime - $now;

		// Bidrequestsからbiditem_idが$idのものを取得
		$bidrequests = $this->Bidrequests->find('all', [
			'conditions' => ['biditem_id' => $id],
			'contain' => ['Users'],
			'order' => ['price' => 'desc']
		])->toArray();
		// Ratinginfoから出品者の平均評価を取得
		$ratingAvg = $this->Ratinginfo->getRatingAvg($biditem->user_id);
		// オブジェクト類をテンプレート用に設定
		$this->set(compact('biditem', 'bidrequests', 'ratingAvg', 'timediff'));
	}

	// 出品する処理
	public function add()
	{
		// Biditemインスタンスを用意
		//$biditem = $this->Biditems->newEntity();
		// POST送信時の処理
		if ($this->request->is('post')) {
			// $biditemにフォームの送信内容を反映
			//$biditem = $this->Biditems->patchEntity($biditem, $this->request->getData());

			// postされたデータを取得する
			$iteminfo = $this->request->getData();
			// newEntityを作成する。ここで送信されてきたデータを検証
			$biditem = $this->Biditems->newEntity($iteminfo);
			// バリデーションエラーがあった場合
			if (!empty($biditem->getErrors())) {
				$this->Flash->error(__('保存に失敗しました。エラーを確認してください'));
			}
			// バリデーションエラーがなかった場合
			if (empty($biditem->getErrors())) {
				// 以下画像データのファイル名の変更、ファイルの移動について
				// 元々のファイル名を取得する。
				$image_path = $iteminfo['image_path']['name'];
				// 一時保存されているフォルダでのファイル名を取得する。
				$tmp_file = $iteminfo['image_path']['tmp_name'];
				// ファイルの拡張子を取得する
				$image_path = pathinfo($image_path, PATHINFO_EXTENSION);
				// ファイル名につけるidを取得し、$iteminfo['image_path']に代入する
				$biditem_id = $this->Biditems->find('lastId'); //findLastIdメソッド：BiditemsTable.phpに記載、最新のID取得
				$iteminfo['image_path'] = $biditem_id . '.' . $image_path;
				$biditem = $this->Biditems->patchEntity($biditem, $iteminfo, [
					'validate' => false
				]);
				// $biditemを保存する
				if ($this->Biditems->save($biditem)) {
					// ファイルを移動させる
					move_uploaded_file($tmp_file, '../webroot/img/auction/' . $iteminfo['image_path']);
					// 成功時のメッセージ
					$this->Flash->success(__('保存しました。'));
					// トップページ（index）に移動
					return $this->redirect(['action' => 'index']);
				}
				// 失敗時のメッセージ
				$this->Flash->error(__('保存に失敗しました。もう一度入力下さい。'));
			} 
		} else {
			// Biditemインスタンスを用意
			$biditem = $this->Biditems->newEntity();
		}
		// 値を保管
		$this->set(compact('biditem'));
	}

	// 入札の処理
	public function bid($biditem_id = null)
	{
		// 入札用のBidrequestインスタンスを用意
		$bidrequest = $this->Bidrequests->newEntity();
		// $bidrequestにbiditem_idとuser_idを設定
		$bidrequest->biditem_id = $biditem_id;
		$bidrequest->user_id = $this->Auth->user('id');
		// $biditem_idの$biditemを取得する
		$biditem = $this->Biditems->get($biditem_id);
		// POST送信時の処理
		if ($this->request->is('post')) {
			// 送信フォームの内容をチェックする
			$getData = $this->request->getData();
			if ($this->Auth->user('id') !== $biditem->user_id) {
				// $bidrequestに送信フォームの内容を反映する
				$bidrequest = $this->Bidrequests->patchEntity($bidrequest, $getData);
				// Bidrequestを保存
				if ($this->Bidrequests->save($bidrequest)) {
					// 成功時のメッセージ
					$this->Flash->success(__('入札を送信しました。'));
					// トップページにリダイレクト
					return $this->redirect(['action' => 'view', $biditem_id]);
				}
			}
			// 失敗時のメッセージ
			$this->Flash->error(__('入札に失敗しました。もう一度入力下さい。※ 出品者は入札できません。'));
		}
		$this->set(compact('bidrequest', 'biditem'));
	}

	// 落札者とのメッセージ
	public function msg($bidinfo_id = null)
	{
		// Bidmessageを新たに用意
		$bidmsg = $this->Bidmessages->newEntity();
		// POST送信時の処理
		if ($this->request->is('post')) {
			// 送信されたフォームで$bidmsgを更新
			$bidmsg = $this->Bidmessages->patchEntity($bidmsg, $this->request->getData());
			// Bidmessageを保存
			if ($this->Bidmessages->save($bidmsg)) {
				$this->Flash->success(__('保存しました。'));
			} else {
				$this->Flash->error(__('保存に失敗しました。もう一度入力下さい。'));
			}
		}
		try { // $bidinfo_idからBidinfoを取得する
			$bidinfo = $this->Bidinfo->get($bidinfo_id, ['contain' => ['Biditems']]);
		} catch (Exception $e) {
			$bidinfo = null;
		}
		// Bidmessageをbidinfo_idとuser_idで検索
		$bidmsgs = $this->Bidmessages->find('all', [
			'conditions' => ['bidinfo_id' => $bidinfo_id],
			'contain' => ['Users'],
			'order' => ['created' => 'desc']
		]);
		$this->set(compact('bidmsgs', 'bidinfo', 'bidmsg'));
	}

	// 落札情報の表示
	public function home()
	{
		// 自分が落札したBidinfoをページネーションで取得
		$bidinfo = $this->paginate('Bidinfo', [
			'conditions' => ['Bidinfo.user_id' => $this->Auth->user('id')],
			'contain' => ['Users', 'Biditems'],
			'order' => ['created' => 'desc'],
			'limit' => 10
		])->toArray();
		$this->set(compact('bidinfo'));
	}

	// 出品情報の表示
	public function home2()
	{
		// 自分が出品したBiditemをページネーションで取得
		$biditems = $this->paginate('Biditems', [
			'conditions' => ['Biditems.user_id' => $this->Auth->user('id')],
			'contain' => ['Users', 'Bidinfo'],
			'order' => ['created' => 'desc'],
			'limit' => 10
		])->toArray();
		$this->set(compact('biditems'));
	}

	// 取引画面の表示
	public function talkTerms($bidinfo_id = null)
	{
		// 渡されたbidinfo_idの落札情報がなければリダイレクト（メソッドはテーブルクラスに記述）
		if (!$this->Bidinfo->isExists($bidinfo_id)) {
			return $this->redirect(['action' => 'index']);
		}
		$bidder = $this->Bidinfo->findById($bidinfo_id)->first();
		$sellor = $this->Biditems->findById($bidder->biditem_id)->first();
		$user = $this->Auth->user();
		if ($user['id'] !== $bidder->user_id && $user['id'] !== $sellor->user_id) {
			return $this->redirect(['action' => 'index']);
		}

		// 落札情報の取得
		$bidData = $this->Bidinfo->get($bidinfo_id, ['contain' => ['Biditems']]);
		// 新しい取引メッセージエンティティを作成
		$talk = $this->Talks->newEntity();
		// 取引メッセージ$talksを取得 カスタムファインダーはTalksTable.phpに記述
		$talks = $this->Talks->find('Talks', ['bidinfo_id' => $bidinfo_id]);
		// 商品送付先エンティティを作成
		$sendinfo = $this->Sendinfo->newEntity();
		// 発送連絡エンティティを作成
		$shippingNotice = $this->ShippingNotices->newEntity();
		// 受取連絡エンティティを作成
		$receivingNotice = $this->ReceivingNotices->newEntity();
		// ユーザー評価エンティティを作成
		$ratinginfo = $this->Ratinginfo->newEntity();
		/**
		 * すでに送付先情報や発送連絡、受取連絡が送信されているかどうかを判断する変数
		 */
		// case 1 送付先情報
		$is_sendinfo_sent = $this->Sendinfo->findByBidinfo_id($bidinfo_id)->first();
		// case 2 発送連絡
		$is_shipping_notice_sent = $this->ShippingNotices->findByBidinfo_id($bidinfo_id)->toArray();
		// case 3 受取連絡
		$is_receiving_notice_sent = $this->ReceivingNotices->findByBidinfo_id($bidinfo_id)->toArray();
		// case 4 取引評価
		$is_rating_sent = $this->Ratinginfo->findByBidinfo_id($bidinfo_id)->toArray();

		$this->set(compact('bidder','bidData', 'talks', 'talk', 'bidinfo_id', 'sendinfo', 'is_sendinfo_sent', 'is_shipping_notice_sent', 'is_receiving_notice_sent', 'shippingNotice', 'receivingNotice', 'ratinginfo', 'is_rating_sent'));


		//HTTP:POSTメソッドでアクセスされたとき
		if ($this->request->is('post')) {

			//case 1 メッセージの送信(talkエンティティ)
			$talkData = $this->request->getData('Talks');
			if (!empty($talkData)) {
				$talk = $this->Talks->patchEntity($talk, $talkData);
				// $talkをTalksテーブルに保存
				if ($this->Talks->save($talk)) {
					$this->Flash->success(__('送信しました'));
				} else {
					$this->Flash->error(__('送信に失敗しました。'));
				}
				$this->redirect(['action' => 'talkTerms', $bidinfo_id]);
			}

			//case 2 送信先情報の送信(sendinfoエンティティ) 落札者のみ
			$sendinfoData = $this->request->getData('Sendinfo');
			if (!empty($sendinfoData)) {
				$sendinfo = $this->Sendinfo->patchEntity($sendinfo, $sendinfoData);
				// $sendinfoをSendinfoテーブルに保存
				if ($this->Sendinfo->save($sendinfo)) {
					$this->Flash->success(__('送信しました。'));
				} else {
					$this->Flash->error(__('送信に失敗しました。'));
				}
				$this->redirect(['action' => 'talkTerms', $bidinfo_id]);
			}

			// case 3 発送連絡(shippingNoticeエンティティ)
			$shippingNoticeData = $this->request->getData('ShippingNotices');
			if (!empty($shippingNoticeData)) {
				$shippingNotice = $this->ShippingNotices->patchEntity($shippingNotice, $shippingNoticeData);
				// $shippingNoticeを shipping_noticesテーブルに保存
				if ($this->ShippingNotices->save($shippingNotice)) {
					$options = ['bidinfo_id' => $bidinfo_id, 'user_id' => $this->Auth->user('id')];
					$this->Talks->saveSendMsg($options);
				} else {
					$this->Flash->error(__('送信に失敗しました。'));
				}
				$this->redirect(['action' => 'talkTerms', $bidinfo_id]);
			}

			// case 4 受取連絡(receivingNoticeエンティティ)
			$receivingNoticeData = $this->request->getData('ReceivingNotices');
			if (!empty($receivingNoticeData)) {
				$receivingNotice = $this->ReceivingNotices->patchEntity($receivingNotice, $receivingNoticeData);
				// $receivingNoticeを receiving_noticesテーブルに保存
				if ($this->ReceivingNotices->save($receivingNotice)) {
					$options = ['bidinfo_id' => $bidinfo_id, 'user_id' => $this->Auth->user('id')];
					$this->Talks->saveReceiveMsg($options);
				} else {
					$this->Flash->error(__('送信に失敗しました。'));
				}
				$this->redirect(['action' => 'talkTerms', $bidinfo_id]);
			}

			// case 5 取引・ユーザー評価
			$ratinginfoData = $this->request->getData('Ratinginfo');
			if (!empty($ratinginfoData)) {
				$ratinginfo = $this->Ratinginfo->patchEntity($ratinginfo, $ratinginfoData);
				if ($this->Ratinginfo->save($ratinginfo)) {
					$this->Flash->success(__('評価を送信しました'));
				} else {
					$this->Flash->error(__('送信に失敗しました'));
				}
				$this->redirect(['action' => 'talkTerms', $bidinfo_id]);
			}
		}
	}
}
