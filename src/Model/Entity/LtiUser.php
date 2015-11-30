<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * LtiUser Entity.
 *
 * @property int $id
 * @property int $lti_key_id
 * @property \App\Model\Entity\LtiKey $lti_key
 * @property string $lti_user_id
 * @property string $lti_eid
 * @property string $lti_displayid
 * @property string $lti_roles
 * @property string $lti_sakai_role
 * @property string $lti_lis_person_contact_email_primary
 * @property string $lti_lis_person_name_family
 * @property string $lti_lis_person_name_full
 * @property string $lti_lis_person_name_given
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property \App\Model\Entity\LtiUser[] $lti_users
 * @property \App\Model\Entity\Attempt[] $attempts
 */
class LtiUser extends Entity
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
