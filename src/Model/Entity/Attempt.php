<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Attempt Entity.
 *
 * @property int $id
 * @property int $lti_user_id
 * @property \App\Model\Entity\LtiUser $lti_user
 * @property bool $start
 * @property bool $alert
 * @property bool $revision
 * @property bool $questions
 * @property bool $samples
 * @property bool $lab
 * @property bool $hidentified
 * @property bool $nidentified
 * @property bool $report
 * @property bool $research
 * @property int $time
 * @property int $money
 * @property int $happiness
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property \App\Model\Entity\Assay[] $assays
 * @property \App\Model\Entity\Note[] $notes
 * @property \App\Model\Entity\QuestionAnswer[] $question_answers
 * @property \App\Model\Entity\QuestionScore[] $question_scores
 * @property \App\Model\Entity\Report[] $reports
 * @property \App\Model\Entity\StandardAssay[] $standard_assays
 * @property \App\Model\Entity\TechniqueUsefulnes[] $technique_usefulness
 * @property \App\Model\Entity\School[] $schools
 */
class Attempt extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
}
