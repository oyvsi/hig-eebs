<?php
extract($this->vars['userProfile']);
echo '
		<div class="profile">
			<div class="picture">
				'. HTML::fancyBoxImage($this->vars['userProfile']['profilePicture'], $this->vars['userProfile']['profilePictureThumb']) . ' 
			</div>
			<div class="info">
				<div class="inner">
					<table>
						<tr>
							<th>Username:</th>
							<td>&nbsp;'.$userName.'</td>
						</tr>
						<tr>
							<th>First Name:</th>
							<td>&nbsp;'.$firstName.'</td>
						</tr>
						<tr>
							<th>Last Name:</th>
							<td>&nbsp;'.$lastName.'</td>
						</tr>
						<tr>
							<th>Email:</th>
							<td>&nbsp;'.$email.'</td>
						</tr>
					</table>
				</div>
			</div>

			</div>
		</div>';