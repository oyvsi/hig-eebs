<?php

echo '<br> 
		<div class="profile">
			<div class="info">
				<div class="inner">
					<table>
						<tr>
							<th>Username:</th>
							<td>&nbsp;'.$this->vars['userProfile']['userName'].'</td>
						</tr>
						<tr>
							<th>First Name:</th>
							<td>&nbsp;'.$this->vars['userProfile']['firstName'].'</td>
						</tr>
						<tr>
							<th>Last Name:</th>
							<td>&nbsp;'.$this->vars['userProfile']['lastName'].'</td>
						</tr>
						<tr>
							<th>Email:</th>
							<td>&nbsp;'.$this->vars['userProfile']['email'].'</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="picture">
				<img src="'.__URL_PATH.$this->vars['userProfile']['pictureUrl'].'" height="280" width="280"/>
			</div>
		</div>
		</p>
	<br>';

/*
print_r($this->vars['title']);
print_r($this->vars['userProfile']['userName']);

print_r($this->vars['userProfile']['firstName']);
print_r($this->vars['userProfile']['pictureID']);
*/