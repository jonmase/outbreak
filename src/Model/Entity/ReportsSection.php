<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ReportsSection Entity.
 *
 * @property int $report_id
 * @property \App\Model\Entity\Report $report
 * @property int $section_id
 * @property \App\Model\Entity\Section $section
 * @property string $text
 */
class ReportsSection extends Entity
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
        'report_id' => false,
        'section_id' => false,
    ];
}
