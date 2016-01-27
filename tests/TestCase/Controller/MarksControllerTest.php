<?php
namespace App\Test\TestCase\Controller;

use App\Controller\MarksController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\MarksController Test Case
 */
class MarksControllerTest extends IntegrationTestCase
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
