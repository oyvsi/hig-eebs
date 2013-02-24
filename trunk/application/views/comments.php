<div class="comments">
<?php
//print_r($this->vars);
foreach($this->vars['comments'] as $comment) {
	extract($comment);
	echo '<div class="comment" id="' . $commentID . '">';
	echo '<div class="commentHeader"><div class="commentName">' . $name . '</div>';
	if($this->vars['isOwner'] || Auth::checkAdmin()) 
		echo '<div class="deleteLink">' . HTML::appLink('comments/delete/' . $commentID, 'Delete comment') . '</div>';
		echo '<div style="clear: both;"></div>';
	//	echo HTML::appLink('user/profile/' . $userName, $comment['userName']);
	echo '</div><hr><div class="commentText">' . $comment . '</div><hr>';
	echo '<div class="commentFooter"><div class="reportLink">' . HTML::appLink('comments/flag/'. $commentID, 'Report comment') . '</div><div class="timestamp">' . date('d.m.Y H:i', $timestamp) . '</div></div>';
	echo '<div style="clear: both;"></div></div>';
}
if(isset($this->vars['loginError'])) {
	echo '<p>No anonymous comments allowed. Either login through <a href="' . $this->vars['fbLoginURL'] . '">Facebook</a>, or '. HTML::appLink('user/login', 'us') . '</p>';
} else {
	echo '<div class="makeComment"><h1>Make a comment</h1>';
	echo 'Commenting as ' . $this->vars['userName'];
	if(isset($this->vars['fbLogoutURL'])) {
		echo '<p>' . HTML::appLink('user/logOut', 'Log out from facebook') . '</p>';
	}
	echo $this->vars['commentForm'];
}
?>
</div>
</div>
