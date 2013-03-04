<?php
extract($this->vars['userProfile']);

// The following html code is for the wrapper for userdefined background:
//echo ' <div style="background-image:url(' . $variable . ')>';

// The following html is for profile information.
echo '
		<div class="profile ' . $theme . ' ">
			<div class="picture">
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
// Html for profile ends above.