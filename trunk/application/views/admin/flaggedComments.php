<?php
echo '<div class="size default centered">';
echo '<table class="table leftAlign centered">';
echo '	<tr>
			<th>Report</th>
			<th>Reported by</th>
			<th>Time</th>
			<th>link</th>
		</tr>';

foreach($this->vars['flagged'] as $comment) {
	extract($comment);
	echo '
		<tr>
			<td>' .$reportText. '</td>
			<td>' .$name. '</td>
			<td>' .date('d.m.y H:i', $timestamp). '</td>
			<td>' .HTML::appLink('comments/view/' . $blogName . '/' . $postURL . '/comments/' . $commentID, 'comment'). '</td>
		</tr>';
}

echo '</table>';
echo '</div>';