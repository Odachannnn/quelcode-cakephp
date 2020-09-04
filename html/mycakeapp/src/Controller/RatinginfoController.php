<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Ratinginfo Controller
 *
 * @property \App\Model\Table\RatinginfoTable $Ratinginfo
 *
 * @method \App\Model\Entity\Ratinginfo[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RatinginfoController extends AuctionBaseController
{
    /**
     * 初期化処理
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Users');
        $this->loadModel('Bidinfo');
        $this->loadModel('Biditems');
        $this->set('authuser', $this->Auth->user());
        $this->viewBuilder()->setLayout('ratinginfo');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Bidinfo', 'Users'],
        ];
        $ratinginfo = $this->paginate($this->Ratinginfo->getAllAvg());

        $this->set(compact('ratinginfo'));
    }

    /**
     * View method
     *
     * @param string|null $id Ratinginfo id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        // $user_id === $id の評価平均を得る
        $ratingAvg = $this->Ratinginfo->getRatingAvg($id);
        // $user_id === $id の評価データを全て得る
        $allRate = $this->Ratinginfo->findByUser_id($id)->contain(['Users']);
        $this->set(compact('allRate', 'ratingAvg'));
    }
}
