<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * LtiUsersFixture
 *
 */
class LtiUsersFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'lti_key_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'lti_user_id' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'lti_eid' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'lti_displayid' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'lti_roles' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'lti_sakai_role' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'lti_lis_person_contact_email_primary' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'lti_lis_person_name_family' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'lti_lis_person_name_full' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'lti_lis_person_name_given' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'fk_lit_contexts_lit_keys_idx' => ['type' => 'index', 'columns' => ['lti_key_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'fk_lit_contexts_lit_keys00' => ['type' => 'foreign', 'columns' => ['lti_key_id'], 'references' => ['lti_keys', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'lti_key_id' => 1,
            'lti_user_id' => 'Lorem ipsum dolor sit amet',
            'lti_eid' => 'Lorem ipsum dolor sit amet',
            'lti_displayid' => 'Lorem ipsum dolor sit amet',
            'lti_roles' => 'Lorem ipsum dolor sit amet',
            'lti_sakai_role' => 'Lorem ipsum dolor sit amet',
            'lti_lis_person_contact_email_primary' => 'Lorem ipsum dolor sit amet',
            'lti_lis_person_name_family' => 'Lorem ipsum dolor sit amet',
            'lti_lis_person_name_full' => 'Lorem ipsum dolor sit amet',
            'lti_lis_person_name_given' => 'Lorem ipsum dolor sit amet',
            'created' => '2015-11-30 17:11:04',
            'modified' => '2015-11-30 17:11:04'
        ],
    ];
}
