<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * QuestionAnswer Entity.
 *
 * @property int $id
 * @property int $attempt_id
 * @property \App\Model\Entity\Attempt $attempt
 * @property int $stem_id
 * @property \App\Model\Entity\QuestionStem $question_stem
 * @property int $question_option_id
 * @property \App\Model\Entity\QuestionOption $question_option
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class QuestionAnswer extends Entity
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
