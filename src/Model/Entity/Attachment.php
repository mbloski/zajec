<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Attachment Entity
 *
 * @property int $id
 * @property string $author_id
 * @property string $message_id
 * @property string $url
 * @property bool $image
 *
 * @property \App\Model\Entity\Author $author
 * @property \App\Model\Entity\Message $message
 */
class Attachment extends Entity
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
        'message_id' => true,
        'url' => true,
        'image' => true,
        'author' => true,
        'message' => true,
    ];
}
