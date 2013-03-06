<?php
echo '<div class="size1 default">';
echo '<table class="table leftAlign">';
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
			<td>' .HTML::appLink('comments/view/' . $blogName . '/' . $postURL . '/comments/' . $commentID, 'comment'). '
		</tr>';



/*

	echo '<p>Report on: ';
	echo HTML::appLink('comments/view/' . $blogName . '/' . $postURL . '/comments/' . $commentID, 'comment');
	echo ' Report: ' . $reportText;
	echo ' Reported by ' . $name . ' at ' . date('d.m.y H:i', $timestamp) . '</p>';
	*/
}

echo '</table>';
echo '</div>';