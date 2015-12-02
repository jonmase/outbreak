<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Attempt'), ['action' => 'edit', $attempt->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Attempt'), ['action' => 'delete', $attempt->id], ['confirm' => __('Are you sure you want to delete # {0}?', $attempt->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Attempts'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Attempt'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Lti Users'), ['controller' => 'LtiUsers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Lti User'), ['controller' => 'LtiUsers', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Assays'), ['controller' => 'Assays', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Assay'), ['controller' => 'Assays', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Notes'), ['controller' => 'Notes', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Note'), ['controller' => 'Notes', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Question Answers'), ['controller' => 'QuestionAnswers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Question Answer'), ['controller' => 'QuestionAnswers', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Question Scores'), ['controller' => 'QuestionScores', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Question Score'), ['controller' => 'QuestionScores', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Reports'), ['controller' => 'Reports', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Report'), ['controller' => 'Reports', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Standard Assays'), ['controller' => 'StandardAssays', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Standard Assay'), ['controller' => 'StandardAssays', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Technique Usefulness'), ['controller' => 'TechniqueUsefulness', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Technique Usefulnes'), ['controller' => 'TechniqueUsefulness', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Schools'), ['controller' => 'Schools', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New School'), ['controller' => 'Schools', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="attempts view large-9 medium-8 columns content">
    <h3><?= h($attempt->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Lti User') ?></th>
            <td><?= $attempt->has('lti_user') ? $this->Html->link($attempt->lti_user->id, ['controller' => 'LtiUsers', 'action' => 'view', $attempt->lti_user->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($attempt->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Time') ?></th>
            <td><?= $this->Number->format($attempt->time) ?></td>
        </tr>
        <tr>
            <th><?= __('Money') ?></th>
            <td><?= $this->Number->format($attempt->money) ?></td>
        </tr>
        <tr>
            <th><?= __('Happiness') ?></th>
            <td><?= $this->Number->format($attempt->happiness) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= h($attempt->created) ?></td>
        </tr>
        <tr>
            <th><?= __('Modified') ?></th>
            <td><?= h($attempt->modified) ?></td>
        </tr>
        <tr>
            <th><?= __('Start') ?></th>
            <td><?= $attempt->start ? __('Yes') : __('No'); ?></td>
         </tr>
        <tr>
            <th><?= __('Alert') ?></th>
            <td><?= $attempt->alert ? __('Yes') : __('No'); ?></td>
         </tr>
        <tr>
            <th><?= __('Revision') ?></th>
            <td><?= $attempt->revision ? __('Yes') : __('No'); ?></td>
         </tr>
        <tr>
            <th><?= __('Questions') ?></th>
            <td><?= $attempt->questions ? __('Yes') : __('No'); ?></td>
         </tr>
        <tr>
            <th><?= __('Samples') ?></th>
            <td><?= $attempt->samples ? __('Yes') : __('No'); ?></td>
         </tr>
        <tr>
            <th><?= __('Lab') ?></th>
            <td><?= $attempt->lab ? __('Yes') : __('No'); ?></td>
         </tr>
        <tr>
            <th><?= __('Hidentified') ?></th>
            <td><?= $attempt->hidentified ? __('Yes') : __('No'); ?></td>
         </tr>
        <tr>
            <th><?= __('Nidentified') ?></th>
            <td><?= $attempt->nidentified ? __('Yes') : __('No'); ?></td>
         </tr>
        <tr>
            <th><?= __('Report') ?></th>
            <td><?= $attempt->report ? __('Yes') : __('No'); ?></td>
         </tr>
        <tr>
            <th><?= __('Research') ?></th>
            <td><?= $attempt->research ? __('Yes') : __('No'); ?></td>
         </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Assays') ?></h4>
        <?php if (!empty($attempt->assays)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Attempt Id') ?></th>
                <th><?= __('Technique Id') ?></th>
                <th><?= __('Site Id') ?></th>
                <th><?= __('School Id') ?></th>
                <th><?= __('Child Id') ?></th>
                <th><?= __('Sample Stage Id') ?></th>
                <th><?= __('Before Submit') ?></th>
                <th><?= __('Created') ?></th>
                <th><?= __('Modified') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($attempt->assays as $assays): ?>
            <tr>
                <td><?= h($assays->id) ?></td>
                <td><?= h($assays->attempt_id) ?></td>
                <td><?= h($assays->technique_id) ?></td>
                <td><?= h($assays->site_id) ?></td>
                <td><?= h($assays->school_id) ?></td>
                <td><?= h($assays->child_id) ?></td>
                <td><?= h($assays->sample_stage_id) ?></td>
                <td><?= h($assays->before_submit) ?></td>
                <td><?= h($assays->created) ?></td>
                <td><?= h($assays->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Assays', 'action' => 'view', $assays->id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'Assays', 'action' => 'edit', $assays->id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Assays', 'action' => 'delete', $assays->id], ['confirm' => __('Are you sure you want to delete # {0}?', $assays->id)]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Notes') ?></h4>
        <?php if (!empty($attempt->notes)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Attempt Id') ?></th>
                <th><?= __('Technique Id') ?></th>
                <th><?= __('Note') ?></th>
                <th><?= __('Created') ?></th>
                <th><?= __('Modified') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($attempt->notes as $notes): ?>
            <tr>
                <td><?= h($notes->id) ?></td>
                <td><?= h($notes->attempt_id) ?></td>
                <td><?= h($notes->technique_id) ?></td>
                <td><?= h($notes->note) ?></td>
                <td><?= h($notes->created) ?></td>
                <td><?= h($notes->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Notes', 'action' => 'view', $notes->id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'Notes', 'action' => 'edit', $notes->id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Notes', 'action' => 'delete', $notes->id], ['confirm' => __('Are you sure you want to delete # {0}?', $notes->id)]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Question Answers') ?></h4>
        <?php if (!empty($attempt->question_answers)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Attempt Id') ?></th>
                <th><?= __('Stem Id') ?></th>
                <th><?= __('Question Option Id') ?></th>
                <th><?= __('Created') ?></th>
                <th><?= __('Modified') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($attempt->question_answers as $questionAnswers): ?>
            <tr>
                <td><?= h($questionAnswers->id) ?></td>
                <td><?= h($questionAnswers->attempt_id) ?></td>
                <td><?= h($questionAnswers->stem_id) ?></td>
                <td><?= h($questionAnswers->question_option_id) ?></td>
                <td><?= h($questionAnswers->created) ?></td>
                <td><?= h($questionAnswers->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'QuestionAnswers', 'action' => 'view', $questionAnswers->id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'QuestionAnswers', 'action' => 'edit', $questionAnswers->id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'QuestionAnswers', 'action' => 'delete', $questionAnswers->id], ['confirm' => __('Are you sure you want to delete # {0}?', $questionAnswers->id)]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Question Scores') ?></h4>
        <?php if (!empty($attempt->question_scores)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Attempt Id') ?></th>
                <th><?= __('Question Id') ?></th>
                <th><?= __('Score') ?></th>
                <th><?= __('Created') ?></th>
                <th><?= __('Modified') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($attempt->question_scores as $questionScores): ?>
            <tr>
                <td><?= h($questionScores->id) ?></td>
                <td><?= h($questionScores->attempt_id) ?></td>
                <td><?= h($questionScores->question_id) ?></td>
                <td><?= h($questionScores->score) ?></td>
                <td><?= h($questionScores->created) ?></td>
                <td><?= h($questionScores->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'QuestionScores', 'action' => 'view', $questionScores->id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'QuestionScores', 'action' => 'edit', $questionScores->id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'QuestionScores', 'action' => 'delete', $questionScores->id], ['confirm' => __('Are you sure you want to delete # {0}?', $questionScores->id)]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Reports') ?></h4>
        <?php if (!empty($attempt->reports)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Attempt Id') ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Serialised') ?></th>
                <th><?= __('Revision Parent') ?></th>
                <th><?= __('Created') ?></th>
                <th><?= __('Modified') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($attempt->reports as $reports): ?>
            <tr>
                <td><?= h($reports->id) ?></td>
                <td><?= h($reports->attempt_id) ?></td>
                <td><?= h($reports->status) ?></td>
                <td><?= h($reports->serialised) ?></td>
                <td><?= h($reports->revision_parent) ?></td>
                <td><?= h($reports->created) ?></td>
                <td><?= h($reports->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Reports', 'action' => 'view', $reports->id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'Reports', 'action' => 'edit', $reports->id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Reports', 'action' => 'delete', $reports->id], ['confirm' => __('Are you sure you want to delete # {0}?', $reports->id)]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Standard Assays') ?></h4>
        <?php if (!empty($attempt->standard_assays)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Attempt Id') ?></th>
                <th><?= __('Technique Id') ?></th>
                <th><?= __('Standard Id') ?></th>
                <th><?= __('Created') ?></th>
                <th><?= __('Modified') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($attempt->standard_assays as $standardAssays): ?>
            <tr>
                <td><?= h($standardAssays->id) ?></td>
                <td><?= h($standardAssays->attempt_id) ?></td>
                <td><?= h($standardAssays->technique_id) ?></td>
                <td><?= h($standardAssays->standard_id) ?></td>
                <td><?= h($standardAssays->created) ?></td>
                <td><?= h($standardAssays->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'StandardAssays', 'action' => 'view', $standardAssays->id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'StandardAssays', 'action' => 'edit', $standardAssays->id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'StandardAssays', 'action' => 'delete', $standardAssays->id], ['confirm' => __('Are you sure you want to delete # {0}?', $standardAssays->id)]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Technique Usefulness') ?></h4>
        <?php if (!empty($attempt->technique_usefulness)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Attempt Id') ?></th>
                <th><?= __('Technique Id') ?></th>
                <th><?= __('Useful') ?></th>
                <th><?= __('Created') ?></th>
                <th><?= __('Modified') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($attempt->technique_usefulness as $techniqueUsefulness): ?>
            <tr>
                <td><?= h($techniqueUsefulness->id) ?></td>
                <td><?= h($techniqueUsefulness->attempt_id) ?></td>
                <td><?= h($techniqueUsefulness->technique_id) ?></td>
                <td><?= h($techniqueUsefulness->useful) ?></td>
                <td><?= h($techniqueUsefulness->created) ?></td>
                <td><?= h($techniqueUsefulness->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'TechniqueUsefulness', 'action' => 'view', $techniqueUsefulness->id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'TechniqueUsefulness', 'action' => 'edit', $techniqueUsefulness->id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'TechniqueUsefulness', 'action' => 'delete', $techniqueUsefulness->id], ['confirm' => __('Are you sure you want to delete # {0}?', $techniqueUsefulness->id)]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Schools') ?></h4>
        <?php if (!empty($attempt->schools)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Code') ?></th>
                <th><?= __('Name') ?></th>
                <th><?= __('Details') ?></th>
                <th><?= __('Acute') ?></th>
                <th><?= __('Convalescent') ?></th>
                <th><?= __('Created') ?></th>
                <th><?= __('Modified') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($attempt->schools as $schools): ?>
            <tr>
                <td><?= h($schools->id) ?></td>
                <td><?= h($schools->code) ?></td>
                <td><?= h($schools->name) ?></td>
                <td><?= h($schools->details) ?></td>
                <td><?= h($schools->acute) ?></td>
                <td><?= h($schools->convalescent) ?></td>
                <td><?= h($schools->created) ?></td>
                <td><?= h($schools->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Schools', 'action' => 'view', $schools->id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'Schools', 'action' => 'edit', $schools->id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Schools', 'action' => 'delete', $schools->id], ['confirm' => __('Are you sure you want to delete # {0}?', $schools->id)]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
</div>
