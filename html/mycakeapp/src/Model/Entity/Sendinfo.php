<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Sendinfo Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $bidinfo_id
 * @property string $name
 * @property string $address
 * @property string $phone_number
 * @property \Cake\I18n\Time $created
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Bidinfo $bidinfo
 */
class Sendinfo extends Entity
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
        'user_id' => true,
        'bidinfo_id' => true,
        'name' => true,
        'address' => true,
        'phone_number' => true,
        'created' => true,
        'user' => true,
        'bidinfo' => true,
    ];
}
