<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Mark'), ['action' => 'edit', $mark->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Mark'), ['action' => 'delete', $mark->id], ['confirm' => __('Are you sure you want to delete # {0}?', $mark->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Marks'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Mark'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Lti Resources'), ['controller' => 'LtiResources', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Lti Resource'), ['controller' => 'LtiResources', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Lti Users'), ['controller' => 'LtiUsers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Lti User'), ['controller' => 'LtiUsers', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="marks view large-9 medium-8 columns content">
    <h3><?= h($mark->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Lti Resource') ?></th>
            <td><?= $mark->has('lti_resource') ? $this->Html->link($mark->lti_resource->lti_resource_link_title, ['controller' => 'LtiResources', 'action' => 'view', $mark->lti_resource->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Mark') ?></th>
            <td><?= h($mark->mark) ?></td>
        </tr>
        <tr>
            <th><?= __('Lti User') ?></th>
            <td><?= $mark->has('lti_user') ? $this->Html->link($mark->lti_user->lti_displayid, ['controller' => 'LtiUsers', 'action' => 'view', $mark->lti_user->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($mark->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Lti User Id') ?></th>
            <td><?= $this->Number->format($mark->lti_user_id) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= h($mark->created) ?></td>
        </tr>
        <tr>
            <th><?= __('Modified') ?></th>
            <td><?= h($mark->modified) ?></td>
        </tr>
        <tr>
            <th><?= __('Reivison') ?></th>
            <td><?= $mark->reivison ? __('Yes') : __('No'); ?></td>
         </tr>
    </table>
    <div class="row">
        <h4><?= __('Mark Comment') ?></h4>
        <?= $this->Text->autoParagraph(h($mark->mark_comment)); ?>
    </div>
</div>
