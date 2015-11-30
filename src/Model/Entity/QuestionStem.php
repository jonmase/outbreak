<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * QuestionStem Entity.
 *
 * @property int $id
 * @property int $question_id
 * @property \App\Model\Entity\Question $question
 * @property string $text
 * @property int $question_option_id
 * @property \App\Model\Entity\QuestionOption $question_option
 * @property string $feedback
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class QuestionStem extends Entity
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
