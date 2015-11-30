<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * LtiKey Entity.
 *
 * @property int $id
 * @property string $oauth_consumer_key
 * @property string $secret
 * @property string $description
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property \App\Model\Entity\LtiContext[] $lti_contexts
 * @property \App\Model\Entity\LtiResource[] $lti_resources
 * @property \App\Model\Entity\LtiUser[] $lti_users
 */
class LtiKey extends Entity
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
