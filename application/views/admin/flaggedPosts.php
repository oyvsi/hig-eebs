<?php

echo '<div class="size1 default">';
echo '<table class="table leftAlign">';

echo '	<tr>
			<th>Report text</th>
			<th>Reported by</th>
			<th>Post author</th>
			<th>Time</th>
			<th>link</th>
		</tr>';
foreach($this->vars['flagged'] as $flagged) {
		extract($flagged);
		echo '
		<tr>
			<td>' .$reportText. '</td>
			<td>' .$reportAuthor. '</td>
			<td>' .$postAuthor. '</td>
			<td>' .date('d.m.y H:i', $timestamp). '</td>
			<td>' .HTML::appLink('blogpost/view/' . $postAuthor . '/' . $PostURL . '/comments/', 'post'). '</td>
		</tr>';
}

echo '</table>';
echo '</div>';


/*
foreach($this->vars['flagged'] as $comment) {
	//print_r($comment);
	extract($comment);
	echo '<p>Report on: ';
	echo HTML::appLink('comments/view/' . $blogName . '/' . $postURL . '/comments/' . $commentID, 'comment');
	echo ' Report: ' . $reportText;
	echo ' Reported by ' . $name . ' at ' . date('d.m.y H:i', $timestamp) . '</p>';
}
 */
