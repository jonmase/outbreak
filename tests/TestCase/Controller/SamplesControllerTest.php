<?php
namespace App\Test\TestCase\Controller;

use App\Controller\SamplesController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\SamplesController Test Case
 */
class SamplesControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
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
        'app.schools',
        'app.children',
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
