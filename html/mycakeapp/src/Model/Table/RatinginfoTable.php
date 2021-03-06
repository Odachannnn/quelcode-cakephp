<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Ratinginfo Model
 *
 * @property \App\Model\Table\BidinfoTable&\Cake\ORM\Association\BelongsTo $Bidinfo
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Ratinginfo get($primaryKey, $options = [])
 * @method \App\Model\Entity\Ratinginfo newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Ratinginfo[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Ratinginfo|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Ratinginfo saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Ratinginfo patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Ratinginfo[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Ratinginfo findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class RatinginfoTable extends Table
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

        $this->setTable('ratinginfo');
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
            ->integer('rating_score')
            ->requirePresence('rating_score', 'create')
            ->notEmptyString('rating_score');

        $validator
            ->scalar('rating_msg')
            ->maxLength('rating_msg', 400)
            ->requirePresence('rating_msg', 'create')
            ->allowEmptyString('rating_msg');

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
     * あるユーザー評価を取得し、平均を割り出すメソッド
     */
    public function getRatingAvg(string $user_id) {
        $query = $this->find()->contain('Users');
        $avg = $query->select(['avg' => $query->func()->avg('Ratinginfo.rating_score'), 'Users.username'])->where(['user_id' => $user_id])->group('Users.id')->first();
        return $avg;
    }
    /**
     * 全てのユーザーの評価を取得し、それぞれ平均を割り出すメソッド
     */
    public function getAllAvg() {
        $query = $this->find()->contain('Users');
        $allAvg = $query->select(['avg' => $query->func()->avg('Ratinginfo.rating_score'), 'Users.username', 'Users.id'])->group('Users.id');
        return $allAvg;
    }
}
