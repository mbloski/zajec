<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Message Entity
 *
 * @property int $id
 * @property string $author_id
 * @property string $channel_id
 * @property string $message_id
 * @property string|null $message
 * @property bool $deleted
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\Author $author
 * @property \App\Model\Entity\Channel $channel
 * @property \App\Model\Entity\Message[] $messages
 */
class Message extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'author_id' => true,
        'channel_id' => true,
        'message_id' => true,
        'message' => true,
        'deleted' => true,
        'created' => true,
        'author' => true,
        'channel' => true,
        'messages' => true,
    ];
}
