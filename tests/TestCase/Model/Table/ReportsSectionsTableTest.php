<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ReportsSectionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ReportsSectionsTable Test Case
 */
class ReportsSectionsTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.reports_sections',
        'app.reports',
        'app.attempts',
        'app.lti_users',
        'app.lti_keys',
        'app.lti_contexts',
        'app.lti_resources',
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
        $config = TableRegistry::exists('ReportsSections') ? [] : ['className' => 'App\Model\Table\ReportsSectionsTable'];
        $this->ReportsSections = TableRegistry::get('ReportsSections', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ReportsSections);

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
