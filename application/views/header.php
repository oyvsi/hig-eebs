<html>
	<head>
	<title><?php echo $this->vars['title'] ?></title>
	<?php echo HTML::cssLink('blog'); ?>
	</head>
	<body>
	<div id="navigation">
		<ul>
			<?php
			echo '<li>' . HTML::appLink('index/mostRead', 'Most read posts') . '</li>';
			echo '<li>' . HTML::appLink('index/mostCommented', 'Most commented posts') . '</li>';
			if(Auth::checkLogin()): 
				echo '<li>' . HTML::appLink('blog/post', 'Create new post') . '</li>';
				echo '<li>' . HTML::appLink('user/profile/usernamevar', 'Profile') . '</li>';
				echo '<li>' . HTML::appLink('user/logOut', 'Logg out') . '</li>';
			endif;
			?>
		</ul>
	</div>
	<div id="contents">
