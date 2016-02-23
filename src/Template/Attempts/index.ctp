<?php 
/**
    Copyright 2016 Jon Mason
	
	This file is part of Oubreak.

    Oubreak is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Oubreak is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Oubreak.  If not, see <http://www.gnu.org/licenses/>.
*/
?>

<h2 class="page-title">
	<?php if($role === "Instructor") { ?>
		<div class="pull-right"><?= $this->Html->link('<i class="fa fa-check"></i>&nbsp; Marking', ['controller' => 'marks', 'action' => 'index'], ['class' => 'btn btn-success', 'role' => 'button', 'escape' => false]) ?></div>
	<?php } ?>
	Viral Outbreak
</h2>
<div class="row attempts index content">
	<div class="col-xs-12">
		<?php if(!empty($marks)): ?>
			<?php 
				$class = "alert";
				if($marks->mark == 'Fail'):
					$class .= " alert-warning";
				else:
					$class .= " alert-success";
				endif;
			?>
			<div class="<?php echo $class; ?>" role="alert">
				<h5 class="no-top-margin">Your Mark: <?php echo $marks->mark; ?></h5>
				<p><strong>Marker's comments:</strong> <?php echo $marks->comment; ?></p>
				<?php if($marks->mark == 'Fail'): ?>
					<p>You will need to do some further work in order to pass. Please refer to the comments above for guidance on what you need to do. Unless specifically requested to do so, do not start again, simply modify your current attempt. Once you have finished, please resubmit your report, and it will then be remarked.</p> 
				<?php else: ?>
					<p>If you have not already filled in the feedback survey on this iCase, we would be very grateful if you could do so here: <a href="https://learntech.imsu.ox.ac.uk/feedback/showsurvey.php?surveyInstanceID=501" target="_blank">https://learntech.imsu.ox.ac.uk/feedback/showsurvey.php?surveyInstanceID=501</a></p>
					<p>You can come back to the iCase at any point to review the revision material, etc. If you have not yet looked at the additional information in the Grant Funded Research section, we recommend that you do so.</p>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		
		<p>Click the "Start New Attempt" button below to begin<?php if(!$attempts->isEmpty()): ?>, or resume an attempt you have already started by clicking the "Resume" button for the appropriate attempt<?php endif; ?>. </p>
		<p>You can have as many attempts as you wish. In each attempt you will have to work through some revision material and questions, before collecting and testing samples and then writing up your findings in a report. Your mark will be based on your report, with consideration given to the samples you collected and the tests you carried out. The revision questions are to aid your learning and understanding, and do not affect your mark, so there is no need to restart just to improve your scores on those.</p>
		<p style="margin-bottom: 20px" class="align-center-full-width"><?= $this->Html->link('<i class="fa fa-play"></i>&nbsp; Start New Attempt', ['action' => 'add'], ['class' => 'btn btn-primary', 'role' => 'button', 'escape' => false]) ?></p>
		
		<?php if(!$attempts->isEmpty()): ?>
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
		<?php endif; ?>
	</div>
</div>
