<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MarksFixture
 *
 */
class MarksFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'lti_resource_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'lti_user_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'mark' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'mark_comment' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'marker_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'reivison' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'fk_marks_lti_resources1_idx' => ['type' => 'index', 'columns' => ['lti_resource_id'], 'length' => []],
            'fk_marks_lti_users1_idx' => ['type' => 'index', 'columns' => ['lti_user_id'], 'length' => []],
            'fk_marks_lti_users2_idx' => ['type' => 'index', 'columns' => ['marker_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'fk_marks_lti_resources1' => ['type' => 'foreign', 'columns' => ['lti_resource_id'], 'references' => ['lti_resources', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_marks_lti_users1' => ['type' => 'foreign', 'columns' => ['lti_user_id'], 'references' => ['lti_users', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_marks_lti_users2' => ['type' => 'foreign', 'columns' => ['marker_id'], 'references' => ['lti_users', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
            'lti_resource_id' => 1,
            'lti_user_id' => 1,
            'mark' => 'Lorem ipsum dolor sit amet',
            'mark_comment' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'marker_id' => 1,
            'reivison' => 1,
            'created' => '2016-01-25 16:47:45',
            'modified' => '2016-01-25 16:47:45'
        ],
    ];
}
