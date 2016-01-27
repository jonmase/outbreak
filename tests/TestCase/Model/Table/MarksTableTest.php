<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MarksTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MarksTable Test Case
 */
class MarksTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.marks',
        'app.lti_resources',
        'app.lti_contexts',
        'app.lti_keys',
        'app.lti_users',
        'app.attempts',
        'app.assays',
        'app.techniques',
        'app.notes',
        'app.standard_assays',
        'app.standards',
        'app.technique_results',
        'app.technique_usefulness',
        'app.sites',
        'app.samples',
        'app.schools',
        'app.attempts_schools',
        'app.children',
        'app.sample_stages',
        'app.question_answers',
        'app.question_stems',
        'app.questions',
        'app.question_options',
        'app.question_scores',
        'app.reports',
        'app.reports_sections',
        'app.sections'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Marks') ? [] : ['className' => 'App\Model\Table\MarksTable'];
        $this->Marks = TableRegistry::get('Marks', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Marks);

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
