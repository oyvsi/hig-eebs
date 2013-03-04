<?php

// Html for each post:
//PS! minor cleaning needed to appear similar to profile.php.

// failsafe for when loading blogcontroller without profile (which sets theme).
if(!isset($theme)) {
	$theme = 'default';
}

echo '<div class=posts>';
foreach($this->vars['blogPosts'] as $blogPost) {
	extract($blogPost);
	$postURL = 'blogpost/view/' . $userName . '/' . $postURL;

	echo '<div class="blogPostSummary ' . $theme . '">';// id="' . $blogPost['postID'] . '">';
	echo '<div class="blogPostTitle"><h1>' . $blogPost['postTitle'] . '</h1></div>';
	//echo '<div class="deletePost"><a href="deletePost">Delete post</a></div>';
	echo '<div class="blogPostSummaryText"><p>' . $blogPost['postIngress'] . '</p></div>';

	echo HTML::appLink($postURL,'Read more');
	
	echo '<div class="blogPostFooter"><p class="commentsLink">';
	echo HTML::appLink('comments/view/' .  $blogPost['userName'] . '/' . $blogPost['postURL'], $noComments . ' comment' . ($noComments != 1 ? 's' : ''));
	echo '</p><p class="byLine">By ' . HTML::appLink('blog/view/' . $userName, $userName) . ' ' . date('d.m.Y H:i', $timestamp) . '</p>';
	echo '<div style="clear: both;"></div></div></div>';
	
}
echo '</div>';

// wrapper for background image ends:
//echo '</div>';

//if ($this->user->model->userID == $blogPost['UserID'])
