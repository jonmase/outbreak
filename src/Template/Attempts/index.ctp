<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Start New Attempt'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="attempts index large-9 medium-8 columns content">
    <h3><?= __('Attempts') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('created', 'Started') ?></th>
                <th><?= $this->Paginator->sort('modified') ?></th>
                 <th><?= 'Progress' ?></th>
               <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($attempts as $attempt): ?>
            <tr>
                <td><?= h($attempt->created) ?></td>
                <td><?= h($attempt->modified) ?></td>
                <td><?= h($attempt->progress) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('Resume'), ['action' => 'run', $attempt->id]) ?>
                    <!--?= //$this->Html->link(__('View'), ['action' => 'view', $attempt->id]) ?-->
                    <!--?= //$this->Html->link(__('Edit'), ['action' => 'edit', $attempt->id]) ?-->
                    <!--?= //$this->Form->postLink(__('Delete'), ['action' => 'delete', $attempt->id], ['confirm' => __('Are you sure you want to delete # {0}?', $attempt->id)]) ?-->
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
