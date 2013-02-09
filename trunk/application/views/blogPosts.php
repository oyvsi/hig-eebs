<?php
//print_r($this->vars);
foreach($this->vars['blogPosts'] as $blogPost) {
	echo '<div class="blogPostSummary" id="' . $blogPost['postID'] . '">';
	echo '<div class="blogPostTitle"><h1>' . $blogPost['postTitle'] . '</h1></div>';
	echo '<div class="blogPostSummaryText">' . $blogPost['postSummary'] . '</div>';
	echo HTML::appLink('blog/view/' . $blogPost['userName'] . '/' . $blogPost['postURL'], 'Read more');
	echo '<div class="blogPostFooter">By ' . HTML::appLink('user/profile/' . $blogPost['userName'], $blogPost['userName']) . ' ' . date('d.m.Y H:i', $blogPost['timestamp']) . '</div>';
	echo '</div>';
	
}
