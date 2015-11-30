<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ChildrenTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ChildrenTable Test Case
 */
class ChildrenTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.children',
        'app.schools',
        'app.assays',
        'app.attempts',
        'app.lti_users',
        'app.notes',
        'app.question_answers',
        'app.question_scores',
        'app.reports',
        'app.standard_assays',
        'app.technique_usefulness',
        'app.attempts_schools',
        'app.techniques',
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
        $config = TableRegistry::exists('Children') ? [] : ['className' => 'App\Model\Table\ChildrenTable'];
        $this->Children = TableRegistry::get('Children', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Children);

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
