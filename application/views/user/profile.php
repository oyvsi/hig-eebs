<?php
extract($this->vars['userProfile']);

echo '<script> document.body.background = "' .$backgroundPicture. '"; </script>';

echo '
		<div class="size1 ' . $theme . ' ">
			<div class="profilePicture">
				'. HTML::fancyBoxImage($profilePicture, $profilePictureThumb) . ' 
			</div>
			<table class="leftAlign ' . $theme . '">
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
		</div>';