<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * LtiResource Entity.
 *
 * @property int $id
 * @property int $lti_key_id
 * @property \App\Model\Entity\LtiKey $lti_key
 * @property bool $active
 * @property string $lti_resource_link_id
 * @property \App\Model\Entity\LtiResourceLink $lti_resource_link
 * @property string $lti_resource_link_title
 * @property string $lti_resource_link_description
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class LtiResource extends Entity
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
