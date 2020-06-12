<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Quote Entity
 *
 * @property int $id
 * @property string $name
 * @property string $author_id
 * @property string $value
 * @property bool $deleted
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $said
 *
 * @property \App\Model\Entity\Author $author
 */
class Quote extends Entity
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
        'name' => true,
        'author_id' => true,
        'value' => true,
        'deleted' => true,
        'created' => true,
        'said' => true,
        'author' => true,
    ];
}
