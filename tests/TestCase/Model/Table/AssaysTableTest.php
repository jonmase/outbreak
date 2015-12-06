<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AssaysTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AssaysTable Test Case
 */
class AssaysTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.assays',
        'app.attempts',
        'app.lti_users',
        'app.lti_keys',
        'app.lti_contexts',
        'app.lti_resources',
        'app.notes',
        'app.techniques',
        'app.standard_assays',
        'app.technique_results',
        'app.technique_usefulness',
        'app.question_answers',
        'app.question_stems',
        'app.questions',
        'app.question_options',
        'app.question_scores',
        'app.reports',
        'app.sections',
        'app.reports_sections',
        'app.schools',
        'app.attempts_schools',
        'app.samples',
        'app.standards'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Assays') ? [] : ['className' => 'App\Model\Table\AssaysTable'];
        $this->Assays = TableRegistry::get('Assays', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Assays);

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
