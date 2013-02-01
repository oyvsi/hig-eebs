	<div class="form" id="login">
<?php
	$form = new Form('login', 'user/loginDo', 'POST');
	$form->addInput('text', 'userName', 'Username');
	$form->addInput('password', 'password', 'Password');
	$form->addInput('submit', 'submit', '');
	echo $form->genForm();
?>
	</div>
