<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Ratinginfo Entity
 *
 * @property int $id
 * @property int $bidinfo_id
 * @property int $user_id
 * @property int $rating_score
 * @property string $rating_msg
 * @property \Cake\I18n\Time $created
 *
 * @property \App\Model\Entity\Bidinfo $bidinfo
 * @property \App\Model\Entity\User $user
 */
class Ratinginfo extends Entity
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
        'bidinfo_id' => true,
        'user_id' => true,
        'rating_score' => true,
        'rating_msg' => true,
        'created' => true,
        'bidinfo' => true,
        'user' => true,
    ];
}
