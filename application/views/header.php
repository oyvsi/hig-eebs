<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<title><?php echo $this->vars['title'] ?></title>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<?php
			echo HTML::jsLink('tiny_mce/tiny_mce');
			echo HTML::jsLink('hig-eebs');
			echo HTML::cssLink('blog'); ?>
	</head>
	<body>
	<div id="navigation">
		<ul>
			<?php
			echo '<li>' . HTML::appLink('mostRead', 'Most read') . '</li>';
			echo '<li>' . HTML::appLink('mostCommented', 'Most commented') . '</li>';
			if(Auth::checkLogin()) { 
				echo '<li>' . HTML::appLink('blog/post', 'New post') . '</li>';
				echo '<li>' . HTML::appLink('user/profile', 'Profile') . '</li>';
				echo '<li>' . HTML::appLink('user/logOut', 'Log out') . '</li>';
			} else {
				echo '<li>' . HTML::appLink('user/login', 'Log in') . '</li>';
			}
			?>
		</ul>
	</div>
	<div id="contents">
