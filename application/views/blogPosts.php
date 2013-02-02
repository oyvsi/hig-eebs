<?php
//print_r($this->vars);
foreach($this->vars['blogPosts'] as $blogPost) {
	echo '<div class="blogPostSummary" id="' . $blogPost['postID'] . '">';
	echo '<div class="blogPostSummaryText">' . $blogPost['postText'] . '</div>';
	echo HTML::appLink('$username', 'Read more');
	echo '<div class="blogPostFooter">By ' . $blogPost['userName'] . ' ' . date('d.m.Y H:i', $blogPost['timestamp']) . '</div>';
	echo '</div>';
	
}
