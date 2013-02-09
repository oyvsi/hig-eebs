<div class="comments">
<?php
//print_r($this->vars);
/*foreach($this->vars['comments'] as $comment) {
	echo '<div class="comment" id="' . $comment['commentID'] . '">';
	echo HTML::appLink('user/profile/' . $comment['userName'], $comment['userName']);
	echo '<div class="commentText">' . $comment['commentText'] . '</div>';
	echo '</div>';
}*/
?>
<div class="makeComment">
<h1>Make a comment</h1>
<?php
echo $this->vars['commentForm'];
?>
</div>
</div>
