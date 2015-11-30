<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AttemptsSchoolsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AttemptsSchoolsTable Test Case
 */
class AttemptsSchoolsTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.attempts_schools',
        'app.attempts',
        'app.lti_users',
        'app.assays',
        'app.techniques',
        'app.sites',
        'app.schools',
        'app.children',
        'app.sample_stages',
        'app.notes',
        'app.question_answers',
        'app.question_scores',
        'app.reports',
        'app.standard_assays',
        'app.technique_usefulness'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('AttemptsSchools') ? [] : ['className' => 'App\Model\Table\AttemptsSchoolsTable'];
        $this->AttemptsSchools = TableRegistry::get('AttemptsSchools', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AttemptsSchools);

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
