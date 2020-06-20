<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Controller\Component\DiscordComponent;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use League\OAuth2\Client\Provider\AbstractProvider;

/**
 * Users Model
 *
 * @method \App\Model\Entity\User newEmptyEntity()
 * @method \App\Model\Entity\User newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    public function identify(Event $event, AbstractProvider $provider, array $data)
    {
        $user = $this->find('all', ['conditions' => ['user_id' => $data['user_id']]])->first();
        if (!$user) {
            $user = $this->newEmptyEntity();
        }

        $user->username = $data['username'];
        $user->user_id = $data['user_id'];
        $user->avatar = $data['avatar'];
        $user->discriminator = $data['discriminator'];
        $this->save($user);

        return $user;
    }

    public function createNew(Event $event, AbstractProvider $provider, array $data)
    {
        $user = $this->newEmptyEntity();

        $user->username = $data['username'];
        $user->user_id = $data['id'];
        $user->avatar = $data['avatar'];
        $user->discriminator = $data['discriminator'];
        $this->save($user);

        return $user->toArray();
    }
}
