<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SchoolsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SchoolsTable Test Case
 */
class SchoolsTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.schools',
        'app.children',
        'app.samples',
        'app.attempts',
        'app.lti_users',
        'app.lti_keys',
        'app.lti_contexts',
        'app.lti_resources',
        'app.assays',
        'app.techniques',
        'app.notes',
        'app.standard_assays',
        'app.technique_results',
        'app.technique_usefulness',
        'app.standards',
        'app.question_answers',
        'app.question_stems',
        'app.questions',
        'app.question_options',
        'app.question_scores',
        'app.reports',
        'app.sections',
        'app.reports_sections',
        'app.attempts_schools',
        'app.sites',
        'app.sample_stages'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Schools') ? [] : ['className' => 'App\Model\Table\SchoolsTable'];
        $this->Schools = TableRegistry::get('Schools', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Schools);

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
}
