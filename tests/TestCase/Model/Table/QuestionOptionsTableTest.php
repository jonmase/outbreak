<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\QuestionOptionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\QuestionOptionsTable Test Case
 */
class QuestionOptionsTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.question_options',
        'app.questions',
        'app.question_scores',
        'app.question_stems',
        'app.question_answers',
        'app.attempts',
        'app.lti_users',
        'app.lti_keys',
        'app.lti_contexts',
        'app.lti_resources',
        'app.lti_resource_links',
        'app.assays',
        'app.techniques',
        'app.sites',
        'app.schools',
        'app.children',
        'app.sample_stages',
        'app.notes',
        'app.reports',
        'app.standard_assays',
        'app.technique_usefulness',
        'app.attempts_schools'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('QuestionOptions') ? [] : ['className' => 'App\Model\Table\QuestionOptionsTable'];
        $this->QuestionOptions = TableRegistry::get('QuestionOptions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->QuestionOptions);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
