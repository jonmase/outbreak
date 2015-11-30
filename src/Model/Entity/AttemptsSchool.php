<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AttemptsSchool Entity.
 *
 * @property int $attempt_id
 * @property \App\Model\Entity\Attempt $attempt
 * @property int $school_id
 * @property \App\Model\Entity\School $school
 * @property bool $acuteDisabled
 * @property bool $convalescentDisabled
 * @property bool $returnTripOk
 */
class AttemptsSchool extends Entity
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
        '*' => true,
        'attempt_id' => false,
        'school_id' => false,
    ];
}
