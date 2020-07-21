<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CookiePoint Entity
 *
 * @property int $id
 * @property int $author_id
 * @property int $target_author_id
 * @property int $count
 *
 */
class CookiePoint extends Entity
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
        'target_author_id' => true,
        'count' => true,
        'author' => true,
        'target_author' => true,
    ];
}
