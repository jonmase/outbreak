<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Mark'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Lti Resources'), ['controller' => 'LtiResources', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Lti Resource'), ['controller' => 'LtiResources', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Lti Users'), ['controller' => 'LtiUsers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Lti User'), ['controller' => 'LtiUsers', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="marks index large-9 medium-8 columns content">
    <h3><?= __('Marks') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('lti_resource_id') ?></th>
                <th><?= $this->Paginator->sort('lti_user_id') ?></th>
                <th><?= $this->Paginator->sort('mark') ?></th>
                <th><?= $this->Paginator->sort('marker_id') ?></th>
                <th><?= $this->Paginator->sort('reivison') ?></th>
                <th><?= $this->Paginator->sort('created') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($marks as $mark): ?>
            <tr>
                <td><?= $this->Number->format($mark->id) ?></td>
                <td><?= $mark->has('lti_resource') ? $this->Html->link($mark->lti_resource->lti_resource_link_title, ['controller' => 'LtiResources', 'action' => 'view', $mark->lti_resource->id]) : '' ?></td>
                <td><?= $this->Number->format($mark->lti_user_id) ?></td>
                <td><?= h($mark->mark) ?></td>
                <td><?= $mark->has('lti_user') ? $this->Html->link($mark->lti_user->lti_displayid, ['controller' => 'LtiUsers', 'action' => 'view', $mark->lti_user->id]) : '' ?></td>
                <td><?= h($mark->reivison) ?></td>
                <td><?= h($mark->created) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $mark->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $mark->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $mark->id], ['confirm' => __('Are you sure you want to delete # {0}?', $mark->id)]) ?>
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
