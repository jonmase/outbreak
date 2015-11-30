<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * LtiContext Entity.
 *
 * @property int $id
 * @property int $lti_key_id
 * @property \App\Model\Entity\LtiKey $lti_key
 * @property string $lti_context_id
 * @property string $lti_context_label
 * @property string $lti_context_title
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property \App\Model\Entity\LtiContext[] $lti_contexts
 */
class LtiContext extends Entity
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
