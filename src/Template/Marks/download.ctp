<?php 
/**
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

<?php
header("Content-Disposition: attachment; filename=\"ViralOutbreakMarks_" . date('Y-m-d') . ".xls\"");
header("Content-Type: application/vnd.ms-excel");

$dateTimeFormat = 'd LLL yyyy HH:mm:ss';
?>

<h3>Viral Outbreak iCase Marks</h3>
<h4>
Date downloaded: <?php echo date('d M Y'); ?><br />
Users who have started: <?php echo $userStartedCount; ?><br />
Users who have submitted: <?php echo $usersSubmittedCount; ?><br />
Users who have been marked: <?php echo $usersMarkedCount; ?>
</h4>
<table cellpadding="3" cellspacing="0">
	<tr>
		<th>Username</th>
		<th>Name</th>
		<th>Email</th>
		<th>Role</th>
		<th>Starts</th>
		<th>Submissions</th>
		<th>Last Submission</th>
		<th>Mark</th>
		<th>Marked By</th>
		<th>Marked On</th>
		<th>Marker's Comment</th>
		<th>Resubmitted since Marking?</th>
	</tr>
	<?php foreach($users as $user): ?>
		<tr>
			<td><?php echo $user->lti_displayid; ?></td>
			<td><?php echo $user->lti_lis_person_name_full; ?></td>
			<td><?php echo $user->lti_lis_person_contact_email_primary; ?></td>
			<td><?php echo $user->most_recent_role; ?></td>
			<td><?php echo $user->attempts_count; ?></td>
			<td><?php echo $user->submissions; ?></td>
			<td><?php echo $this->Time->format($user->last_submit, $dateTimeFormat);//echo date('d M Y', $user->last_submit); ?></td>
			<?php if(!empty($user->marks) && $user->marks->mark): ?>
				<td><?php echo $user->marks->mark; ?></td>
				<td><?php echo $user->marks->marker->lti_lis_person_name_full; ?></td>
				<td><?php echo $this->Time->format($user->marks->created, $dateTimeFormat); ?></td>
				<td><?php echo $user->marks->comment; ?></td>
				<td><?php echo $user->resubmitted?"Yes":"No"; ?></td>
			<?php endif; ?>
		</tr>
	<?php endforeach; ?>
</table>
