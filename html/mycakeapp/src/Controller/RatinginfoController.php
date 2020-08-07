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
class RatinginfoController extends AppController
{
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
        $ratinginfo = $this->paginate($this->Ratinginfo);

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
        $ratinginfo = $this->Ratinginfo->get($id, [
            'contain' => ['Bidinfo', 'Users'],
        ]);

        $this->set('ratinginfo', $ratinginfo);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ratinginfo = $this->Ratinginfo->newEntity();
        if ($this->request->is('post')) {
            $ratinginfo = $this->Ratinginfo->patchEntity($ratinginfo, $this->request->getData());
            if ($this->Ratinginfo->save($ratinginfo)) {
                $this->Flash->success(__('The ratinginfo has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The ratinginfo could not be saved. Please, try again.'));
        }
        $bidinfo = $this->Ratinginfo->Bidinfo->find('list', ['limit' => 200]);
        $users = $this->Ratinginfo->Users->find('list', ['limit' => 200]);
        $this->set(compact('ratinginfo', 'bidinfo', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Ratinginfo id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ratinginfo = $this->Ratinginfo->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ratinginfo = $this->Ratinginfo->patchEntity($ratinginfo, $this->request->getData());
            if ($this->Ratinginfo->save($ratinginfo)) {
                $this->Flash->success(__('The ratinginfo has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The ratinginfo could not be saved. Please, try again.'));
        }
        $bidinfo = $this->Ratinginfo->Bidinfo->find('list', ['limit' => 200]);
        $users = $this->Ratinginfo->Users->find('list', ['limit' => 200]);
        $this->set(compact('ratinginfo', 'bidinfo', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Ratinginfo id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ratinginfo = $this->Ratinginfo->get($id);
        if ($this->Ratinginfo->delete($ratinginfo)) {
            $this->Flash->success(__('The ratinginfo has been deleted.'));
        } else {
            $this->Flash->error(__('The ratinginfo could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
