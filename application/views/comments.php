<div class="size default centered">
<?php
//print_r($this->vars);
foreach($this->vars['comments'] as $comment) {
	extract($comment);

	echo '<div class="layout" id="' . $commentID . '">';

	if($deleted == 1) {
		$comment = 'Comment deleted by an administrator';
	}

	echo '<div class="commentName">' . $name . '</div>';

	if($deleted == 0 && ($this->vars['isOwner'] || Auth::checkAdmin())) 
	echo '<div class="deleteLink">' . HTML::appLink('comments/delete/' . $commentID, 'Delete comment') . '</div>';
	echo '<div style="clear: both;"></div>';
	//	echo HTML::appLink('user/profile/' . $userName, $comment['userName']);
	echo '<hr><div class="commentText">' . $comment . '</div><hr>';

	if($deleted == 0) {
		echo '<div class="commentFooter"><div class="reportLink">' . HTML::appLink('report/report/comments/'. $commentID, 'Report comment').'</div></div>';
	}

		echo '<div class="timestamp">' . date('d.m.Y H:i', $timestamp) . '</div>';
		echo '<div style="clear: both;"></div></div>';

}

//comment field:
if(isset($this->vars['loginError'])) {
	echo '<p>No anonymous comments allowed. Either login through <a href="' . $this->vars['fbLoginURL'] . '">Facebook</a>, or '. HTML::appLink('user/login', 'us') . '</p>';
} else {
	echo '<div class="makeComment"><h1>Make a comment</h1>';
	echo 'Commenting as ' . $this->vars['userName'];
	if(isset($this->vars['fbLogoutURL'])) {
		echo '<p>' . HTML::appLink('user/logOut', 'Log out from facebook') . '</p>';
	}
echo $this->vars['commentForm'];
echo '</div>';
}

?>
</div>