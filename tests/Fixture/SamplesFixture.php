<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * SamplesFixture
 *
 */
class SamplesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'attempt_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'site_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'school_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'child_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'sample_stage_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'before_submit' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'fk_samples_sites1_idx' => ['type' => 'index', 'columns' => ['site_id'], 'length' => []],
            'fk_samples_attempts1_idx' => ['type' => 'index', 'columns' => ['attempt_id'], 'length' => []],
            'fk_samples_schools1_idx' => ['type' => 'index', 'columns' => ['school_id'], 'length' => []],
            'fk_samples_children1_idx' => ['type' => 'index', 'columns' => ['child_id'], 'length' => []],
            'fk_samples_sample_stages1_idx' => ['type' => 'index', 'columns' => ['sample_stage_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'fk_samples_sites1' => ['type' => 'foreign', 'columns' => ['site_id'], 'references' => ['sites', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_samples_attempts1' => ['type' => 'foreign', 'columns' => ['attempt_id'], 'references' => ['attempts', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_samples_schools1' => ['type' => 'foreign', 'columns' => ['school_id'], 'references' => ['schools', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_samples_children1' => ['type' => 'foreign', 'columns' => ['child_id'], 'references' => ['children', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_samples_sample_stages1' => ['type' => 'foreign', 'columns' => ['sample_stage_id'], 'references' => ['sample_stages', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
            'attempt_id' => 1,
            'site_id' => 1,
            'school_id' => 1,
            'child_id' => 1,
            'sample_stage_id' => 1,
            'before_submit' => 1,
            'created' => '2015-12-04 16:36:35',
            'modified' => '2015-12-04 16:36:35'
        ],
    ];
}
