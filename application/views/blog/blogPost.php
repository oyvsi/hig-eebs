<?php

	extract($this->vars['blogPost']);
	if($deleted) {
		$postText = 'Post is deleted by the blogger or an administrator';
	}

	// changes background
	echo '<script> document.body.background = "' .$backgroundPicture. '"; </script>';

	echo '<div class="size1 ' . $theme . '">';
	echo '<div class="blogPostTitle"><h1>' . $postTitle . '</h1></div>';
	if ($this->vars['isOwner'] || Auth::checkAdmin()) { //logged in user can delete own posts
		echo '<div class="deletePost">' . HTML::appLink('blogpost/delete/' . $postID, 'Delete post') . '<br />';
			if($this->vars['isOwner']) {
				echo HTML::appLink('blogpost/update/' . $postID, 'Edit post');
			}
		echo	'</div>';
	}
	echo '<div class="blogPostText">' . $postText . '</div>';
	echo '<div class="blogPostFooter"><p class="commentsLink">';
	echo HTML::appLink('comments/view/' .$userName. '/' .$postURL, $noComments . ' comment' . ($noComments != 1 ? 's' : ''));	     echo '</p><p class="byLine">By ' . HTML::appLink('blog/view/' . $userName, $userName) . ' ' . date('d.m.Y H:i', $timestamp) . '</p>';

	echo '<div style="clear: both;"></div></div>';
	echo '<div>' . HTML::appLink('report/report/blogpost/' . $postID, 'Report post') . '</div>';	

?>
<div class="fb-like" data-send="false" data-width="450" data-show-faces="true"></div>
</div>
