	<div class="form" id="login">
<?php
	$form = new Form('login', 'user/loginDo', 'POST');
	$form->addInput('text', 'firstName', 'First Name');
	$form->addInput('text', 'lastName', 'Last Name');
	$form->addInput('text', 'email', 'Email');
	$form->addInput('text', 'userName', 'Username');
	$form->addInput('password', 'password', 'Password');
	$form->addInput('password', 'password2', 'Confirm password');
	$form->addInput('submit', 'submit', '');
	echo $form->genForm();
?>
	</div>
