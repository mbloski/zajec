<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MessageEditHistory Model
 *
 * @property \App\Model\Table\MessagesTable&\Cake\ORM\Association\BelongsTo $Messages
 *
 * @method \App\Model\Entity\MessageEditHistory newEmptyEntity()
 * @method \App\Model\Entity\MessageEditHistory newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\MessageEditHistory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MessageEditHistory get($primaryKey, $options = [])
 * @method \App\Model\Entity\MessageEditHistory findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\MessageEditHistory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MessageEditHistory[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\MessageEditHistory|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MessageEditHistory saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MessageEditHistory[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\MessageEditHistory[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\MessageEditHistory[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\MessageEditHistory[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MessageEditHistoryTable extends Table
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

        $this->setTable('message_edit_history');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Messages', [
            'foreignKey' => 'message_id',
            'joinType' => 'INNER',
        ]);
    }
}
