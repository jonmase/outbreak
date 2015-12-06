<?php
namespace App\Test\TestCase\Controller;

use App\Controller\SchoolsController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\SchoolsController Test Case
 */
class SchoolsControllerTest extends IntegrationTestCase
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
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
