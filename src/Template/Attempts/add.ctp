<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Attempts'), ['action' => 'index']) ?></li>
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
<div class="attempts form large-9 medium-8 columns content">
    <?= $this->Form->create($attempt) ?>
    <fieldset>
        <legend><?= __('Add Attempt') ?></legend>
        <?php
            echo $this->Form->input('lti_user_id', ['options' => $ltiUsers]);
            echo $this->Form->input('start');
            echo $this->Form->input('alert');
            echo $this->Form->input('revision');
            echo $this->Form->input('questions');
            echo $this->Form->input('samples');
            echo $this->Form->input('lab');
            echo $this->Form->input('hidentified');
            echo $this->Form->input('nidentified');
            echo $this->Form->input('report');
            echo $this->Form->input('research');
            echo $this->Form->input('time');
            echo $this->Form->input('money');
            echo $this->Form->input('happiness');
            echo $this->Form->input('schools._ids', ['options' => $schools]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
