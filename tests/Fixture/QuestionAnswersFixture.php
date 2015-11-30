<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * QuestionAnswersFixture
 *
 */
class QuestionAnswersFixture extends TestFixture
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
        'stem_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'question_option_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'fk_answers_attempts1_idx' => ['type' => 'index', 'columns' => ['attempt_id'], 'length' => []],
            'fk_answers_stems1_idx' => ['type' => 'index', 'columns' => ['stem_id'], 'length' => []],
            'fk_answers_question_options1_idx' => ['type' => 'index', 'columns' => ['question_option_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'fk_answers_attempts1' => ['type' => 'foreign', 'columns' => ['attempt_id'], 'references' => ['attempts', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_answers_stems1' => ['type' => 'foreign', 'columns' => ['stem_id'], 'references' => ['question_stems', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_answers_question_options1' => ['type' => 'foreign', 'columns' => ['question_option_id'], 'references' => ['question_options', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
            'stem_id' => 1,
            'question_option_id' => 1,
            'created' => '2015-11-30 17:11:31',
            'modified' => '2015-11-30 17:11:31'
        ],
    ];
}
