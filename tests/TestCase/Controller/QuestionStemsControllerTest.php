<?php
namespace App\Test\TestCase\Controller;

use App\Controller\QuestionStemsController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\QuestionStemsController Test Case
 */
class QuestionStemsControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.question_stems',
        'app.questions',
        'app.question_options',
        'app.question_answers',
        'app.attempts',
        'app.lti_users',
        'app.lti_keys',
        'app.lti_contexts',
        'app.lti_resources',
        'app.lti_resource_links',
        'app.assays',
        'app.techniques',
        'app.notes',
        'app.standard_assays',
        'app.standards',
        'app.technique_results',
        'app.technique_usefulness',
        'app.sites',
        'app.schools',
        'app.children',
        'app.attempts_schools',
        'app.sample_stages',
        'app.question_scores',
        'app.reports',
        'app.sections',
        'app.reports_sections'
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
