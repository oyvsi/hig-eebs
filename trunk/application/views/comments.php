<div class="comments">
<?php
//print_r($this->vars);
foreach($this->vars['comments'] as $comment) {
	extract($comment);
	echo '<div class="comment" id="' . $commentID . '">';
	echo '<div class="commentName">' . $name . '</div><hr>';
//	echo HTML::appLink('user/profile/' . $userName, $comment['userName']);
	echo '<div class="commentText">' . $comment . '</div><hr>';
	echo '<div class="commentFooter">FLAG! ' . date('d.m.Y H:i', $timestamp) . '</div>';
	echo '</div>';
}
?>
<div class="makeComment">
<h1>Make a comment</h1>
<?php
echo $this->vars['commentForm'];
?>
</div>
</div>
