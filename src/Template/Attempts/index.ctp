<div class="row attempts index content">
	<div class="col-xs-12">
		<h2 class="page-title">Viral Outbreak</h2>
		<p>Welcome to the Viral Outbreak iCase. Click the "Start New Attempt" button below to begin, or resume an attempt you have already started by clicking the "Resume" button for the appropriate attempt. </p>
		<p>You can have as many attempts as you wish. In each attempt you will have to work through some revision material and questions, before collecting and testing samples and then writing up your findings in a report. Your mark will be based on your report, with consideration given to the samples you collected and the tests you carried out. The revision questions are to aid your learning and understanding, so there is no need to restart just to improve your scores on those.</p>
		<p><strong>Please Note:</strong> Please ensure you do not have this iCase open in more than one window/tab at any one time. Having multiple instances open could lead to your progress and/or reports being overwritten and lost.</p>
		<p style="margin-bottom: 20px" class="align-center-full-width"><?= $this->Html->link('<i class="fa fa-play"></i>&nbsp; Start New Attempt', ['action' => 'add'], ['class' => 'btn btn-primary', 'role' => 'button', 'escape' => false]) ?></p>
		<h5 class="no-top-margin blue">Previous Attempts</h5>
		<table cellpadding="0" cellspacing="0" class="table attempts-table">
			<thead>
				<tr>
					<th><?= 'Progress' ?></th>
					<th><?= $this->Paginator->sort('created', 'Started') ?></th>
					<th><?= $this->Paginator->sort('modified') ?></th>
					<th class="actions"></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($attempts as $attempt): ?>
				<tr>
					<td><?= h($attempt->progress) ?></td>
					<!--td><?= h($attempt->created) ?></td-->
					<!--td><?= h($attempt->modified) ?></td-->
					<td><?php 
					$timeFormat = 'yyyy-MM-dd HH:mm:ss';
					echo $this->Time->format(
						  $attempt->created,
						  $timeFormat
						);
					?></td>
					<td><?php echo $this->Time->format(
						  $attempt->modified,
						  $timeFormat
						);
					?></td>
					<td class="actions">
						<!--?= $this->Html->link(__('Resume'), ['action' => 'view', $attempt->id]) ?-->
						<?= $this->Html->link('<i class="fa fa-play"></i>&nbsp; Resume', ['action' => 'view', $attempt->id], ['class' => 'btn btn-primary', 'role' => 'button', 'escape' => false]) ?>
						<!--?= //$this->Html->link(__('View'), ['action' => 'view', $attempt->id]) ?-->
						<!--?= //$this->Html->link(__('Edit'), ['action' => 'edit', $attempt->id]) ?-->
						<!--?= //$this->Form->postLink(__('Delete'), ['action' => 'delete', $attempt->id], ['confirm' => __('Are you sure you want to delete # {0}?', $attempt->id)]) ?-->
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="paginator align-center-full-width">
			<ul class="pagination">
				<?= $this->Paginator->prev('< ' . __('previous')) ?>
				<?= $this->Paginator->numbers() ?>
				<?= $this->Paginator->next(__('next') . ' >') ?>
			</ul>
			<!--p><?= $this->Paginator->counter() ?></p-->
		</div>
	</div>
</div>
