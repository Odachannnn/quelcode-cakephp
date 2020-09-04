<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Log\Log;

/**
 * Talks Model
 *
 * @property \App\Model\Table\BidinfoTable&\Cake\ORM\Association\BelongsTo $Bidinfo
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Talk get($primaryKey, $options = [])
 * @method \App\Model\Entity\Talk newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Talk[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Talk|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Talk saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Talk patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Talk[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Talk findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TalksTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('talks');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Bidinfo', [
            'foreignKey' => 'bidinfo_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('message')
            ->maxLength('message', 200)
            ->requirePresence('message', 'create')
            ->notEmptyString('message');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['bidinfo_id'], 'Bidinfo'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }

    /**
     * 各落札商品の取引メッセージを取得するメソッドを作成
     */
    public function findTalks(Query $query, array $options)
    {
        $bidinfo_id = $options['bidinfo_id'];
        return $query->where(['Talks.bidinfo_id' => $bidinfo_id])->contain(['Bidinfo', 'Users'])->orderDesc('Talks.created');
    }

    /**
     * 発送連絡後に自動で取引メッセージを保存するメソッドを作成
     */
    // case 1 発送連絡
    public function saveSendMsg(array $options) {
        $data = [
            'bidinfo_id' => $options['bidinfo_id'],
            'user_id' => $options['user_id'],
            'message' => '商品が発送されました。'
        ];
        return $this->save($this->newEntity($data));
    }

    // case 2 受取連絡
    public function saveReceiveMsg(array $options) {
        $data = [
            'bidinfo_id' => $options['bidinfo_id'],
            'user_id' => $options['user_id'],
            'message' => '商品が到着しました。',
        ];
        return $this->save($this->newEntity($data));
    }
}
