<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * TechniqueUsefulnessFixture
 *
 */
class TechniqueUsefulnessFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'technique_usefulness';

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
        'useful' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'fk_techniques_useful_attempts1_idx' => ['type' => 'index', 'columns' => ['attempt_id'], 'length' => []],
            'fk_techniques_useful_techniques1_idx' => ['type' => 'index', 'columns' => ['technique_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'fk_techniques_useful_attempts1' => ['type' => 'foreign', 'columns' => ['attempt_id'], 'references' => ['attempts', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_techniques_useful_techniques1' => ['type' => 'foreign', 'columns' => ['technique_id'], 'references' => ['techniques', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
            'useful' => 1,
            'created' => '2015-11-30 17:13:04',
            'modified' => '2015-11-30 17:13:04'
        ],
    ];
}
