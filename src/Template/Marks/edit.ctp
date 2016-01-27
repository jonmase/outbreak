<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $mark->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $mark->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Marks'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Lti Resources'), ['controller' => 'LtiResources', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Lti Resource'), ['controller' => 'LtiResources', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Lti Users'), ['controller' => 'LtiUsers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Lti User'), ['controller' => 'LtiUsers', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="marks form large-9 medium-8 columns content">
    <?= $this->Form->create($mark) ?>
    <fieldset>
        <legend><?= __('Edit Mark') ?></legend>
        <?php
            echo $this->Form->input('lti_resource_id', ['options' => $ltiResources]);
            echo $this->Form->input('lti_user_id');
            echo $this->Form->input('mark');
            echo $this->Form->input('mark_comment');
            echo $this->Form->input('marker_id', ['options' => $ltiUsers]);
            echo $this->Form->input('reivison');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
