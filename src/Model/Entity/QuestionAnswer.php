<?php
/**
    Copyright 2016 Jon Mason
	
	This file is part of Oubreak.

    Oubreak is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Oubreak is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Oubreak.  If not, see <http://www.gnu.org/licenses/>.
*/

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
