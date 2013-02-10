<?php
	echo '<p>No account yet? Don\'t worry, ' . HTML::appLink('user/createAccount', 'sign up!') . '</p>';
	echo '<div class="form" id="login">';

	$form = new Form('login', 'user/loginDo', 'POST');
	$form->addInput('text', 'userName', 'Username');
	$form->addInput('password', 'password', 'Password');
	$form->addInput('submit', 'submit', '');
	echo $form->genForm();
	echo '<p>Forgot password? Time to worry: ' . HTML::appLink('user/forgotPassword', 'Get new password') . '</p>';
?>
	</div>
