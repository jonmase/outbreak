<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AssaysFixture
 *
 */
class AssaysFixture extends TestFixture
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
        'technique_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'sample_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'standard_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'before_submit' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'fk_assays_attempts1_idx' => ['type' => 'index', 'columns' => ['attempt_id'], 'length' => []],
            'fk_assays_techniques1_idx' => ['type' => 'index', 'columns' => ['technique_id'], 'length' => []],
            'fk_assays_samples1_idx' => ['type' => 'index', 'columns' => ['sample_id'], 'length' => []],
            'fk_assays_standards1_idx' => ['type' => 'index', 'columns' => ['standard_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'fk_assays_samples1' => ['type' => 'foreign', 'columns' => ['sample_id'], 'references' => ['samples', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_assays_standards1' => ['type' => 'foreign', 'columns' => ['standard_id'], 'references' => ['standards', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_assays_attempts1' => ['type' => 'foreign', 'columns' => ['attempt_id'], 'references' => ['attempts', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_assays_techniques1' => ['type' => 'foreign', 'columns' => ['technique_id'], 'references' => ['techniques', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
            'technique_id' => 1,
            'sample_id' => 1,
            'standard_id' => 1,
            'before_submit' => 1,
            'created' => '2015-12-04 16:35:51',
            'modified' => '2015-12-04 16:35:51'
        ],
    ];
}
