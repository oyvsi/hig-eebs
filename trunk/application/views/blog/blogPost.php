<?php
// TODO: This is a massive duplicate of blogPosts. Fix.
//print_r($this->vars);
foreach($this->vars['blogPosts'] as $blogPost) {
	extract($blogPost);
	echo '<div class="blogPostSummary" id="' . $postID . '">';
	echo '<div class="blogPostTitle"><h1>' . $postTitle . '</h1>';
	if (isset($_SESSION['userID']) && $_SESSION['userID'] == $userID) echo '<div class="deletePost"><a href="deletePost">Delete post</a></div></div>';
	echo '<div class="blogPostText">' . $postText . '</div>';
	echo '<div class="blogPostFooter"><p class="commentsLink">';
	echo '<a href="comments">' . $noComments . ' comment' . ($noComments != 1 ? 's' : '') . '</a>';
	echo '</p><p class="byLine">By ' . HTML::appLink('user/profile/' . $userName, $userName) . ' ' . date('d.m.Y H:i', $timestamp) . '</p>';
	echo '<div style="clear: both;"></div></div></div>';
	
}
//<p class="deletePost">
