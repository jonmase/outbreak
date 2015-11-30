<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Technique Entity.
 *
 * @property int $id
 * @property string $code
 * @property string $menu
 * @property string $name
 * @property string $video
 * @property string $content
 * @property bool $revision_only
 * @property bool $lab_only
 * @property int $order
 * @property int $time
 * @property int $money
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property \App\Model\Entity\Assay[] $assays
 * @property \App\Model\Entity\Note[] $notes
 * @property \App\Model\Entity\StandardAssay[] $standard_assays
 * @property \App\Model\Entity\TechniqueResult[] $technique_results
 * @property \App\Model\Entity\TechniqueUsefulnes[] $technique_usefulness
 */
class Technique extends Entity
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
