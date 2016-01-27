<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Mark Entity.
 *
 * @property int $id
 * @property int $lti_resource_id
 * @property \App\Model\Entity\LtiResource $lti_resource
 * @property int $lti_user_id
 * @property string $mark
 * @property string $mark_comment
 * @property int $marker_id
 * @property \App\Model\Entity\LtiUser $lti_user
 * @property bool $reivison
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class Mark extends Entity
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
        'id' => false,
    ];
}
