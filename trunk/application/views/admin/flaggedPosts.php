<?php
echo '<pre>';
print_r($this->vars);
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
