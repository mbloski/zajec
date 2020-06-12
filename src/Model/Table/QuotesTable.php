<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Quotes Model
 *
 * @property \App\Model\Table\AuthorsTable&\Cake\ORM\Association\BelongsTo $Authors
 *
 * @method \App\Model\Entity\Quote newEmptyEntity()
 * @method \App\Model\Entity\Quote newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Quote[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Quote get($primaryKey, $options = [])
 * @method \App\Model\Entity\Quote findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Quote patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Quote[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Quote|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Quote saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Quote[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Quote[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Quote[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Quote[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class QuotesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('quotes');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Authors', [
            'foreignKey' => 'author_id',
            'joinType' => 'INNER',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('value')
            ->requirePresence('value', 'create')
            ->notEmptyString('value');

        $validator
            ->boolean('deleted')
            ->notEmptyString('deleted');

        $validator
            ->dateTime('said')
            ->requirePresence('said', 'create')
            ->notEmptyDateTime('said');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['author_id'], 'Authors'));

        return $rules;
    }
}
