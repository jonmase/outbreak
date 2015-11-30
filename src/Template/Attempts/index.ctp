<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Attempt'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Lti Users'), ['controller' => 'LtiUsers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Lti User'), ['controller' => 'LtiUsers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Assays'), ['controller' => 'Assays', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Assay'), ['controller' => 'Assays', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Notes'), ['controller' => 'Notes', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Note'), ['controller' => 'Notes', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Question Answers'), ['controller' => 'QuestionAnswers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Question Answer'), ['controller' => 'QuestionAnswers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Question Scores'), ['controller' => 'QuestionScores', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Question Score'), ['controller' => 'QuestionScores', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Reports'), ['controller' => 'Reports', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Report'), ['controller' => 'Reports', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Standard Assays'), ['controller' => 'StandardAssays', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Standard Assay'), ['controller' => 'StandardAssays', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Technique Usefulness'), ['controller' => 'TechniqueUsefulness', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Technique Usefulnes'), ['controller' => 'TechniqueUsefulness', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Schools'), ['controller' => 'Schools', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New School'), ['controller' => 'Schools', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="attempts index large-9 medium-8 columns content">
    <h3><?= __('Attempts') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('lti_user_id') ?></th>
                <th><?= $this->Paginator->sort('start') ?></th>
                <th><?= $this->Paginator->sort('alert') ?></th>
                <th><?= $this->Paginator->sort('revision') ?></th>
                <th><?= $this->Paginator->sort('questions') ?></th>
                <th><?= $this->Paginator->sort('samples') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($attempts as $attempt): ?>
            <tr>
                <td><?= $this->Number->format($attempt->id) ?></td>
                <td><?= $attempt->has('lti_user') ? $this->Html->link($attempt->lti_user->id, ['controller' => 'LtiUsers', 'action' => 'view', $attempt->lti_user->id]) : '' ?></td>
                <td><?= h($attempt->start) ?></td>
                <td><?= h($attempt->alert) ?></td>
                <td><?= h($attempt->revision) ?></td>
                <td><?= h($attempt->questions) ?></td>
                <td><?= h($attempt->samples) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $attempt->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $attempt->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $attempt->id], ['confirm' => __('Are you sure you want to delete # {0}?', $attempt->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
