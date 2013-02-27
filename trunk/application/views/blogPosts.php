<?php
//print_r($this->vars);
foreach($this->vars['blogPosts'] as $blogPost) {
	extract($blogPost);
	$postURL = 'blogpost/view/' . $blogPost['userName'] . '/' . $blogPost['postURL'];
	echo '<div class="blogPostSummary" id="' . $blogPost['postID'] . '">';
	echo '<div class="blogPostTitle"><h1>' . $blogPost['postTitle'] . '</h1></div>';
	//echo '<div class="deletePost"><a href="deletePost">Delete post</a></div>';
	echo '<div class="blogPostSummaryText"><p>' . $blogPost['postIngress'] . '</p></div>';
	echo HTML::appLink($postURL,'Read more');
	echo '<div class="blogPostFooter"><p class="commentsLink">';
	echo HTML::appLink('comments/view/' .  $blogPost['userName'] . '/' . $blogPost['postURL'], $noComments . ' comment' . ($noComments != 1 ? 's' : ''));
	echo '</p><p class="byLine">By ' . HTML::appLink('user/profile/' . $userName, $userName) . ' ' . date('d.m.Y H:i', $timestamp) . '</p>';
	echo '<div style="clear: both;"></div></div></div>';
	
}

//if ($this->user->model->userID == $blogPost['UserID'])
