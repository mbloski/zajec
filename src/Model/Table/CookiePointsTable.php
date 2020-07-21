<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CookiePoints Model
 *
 * @method \App\Model\Entity\CookiePoint newEmptyEntity()
 * @method \App\Model\Entity\CookiePoint newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\CookiePoint[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CookiePoint get($primaryKey, $options = [])
 * @method \App\Model\Entity\CookiePoint findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\CookiePoint patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CookiePoint[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\CookiePoint|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CookiePoint saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CookiePoint[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\CookiePoint[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\CookiePoint[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\CookiePoint[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class CookiePointsTable extends Table
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

        $this->setTable('cookie_points');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
    }
}
