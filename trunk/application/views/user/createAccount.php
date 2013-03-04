<div id="createAccount">
<?php
	extract($this->vars);
	if(isset($userInfo['profilePicture']) && $userInfo['profilePicture'] != null) {
		echo '<div id="profilePicture">';
		echo HTML::fancyBoxImage($userInfo['profilePicture'], $userInfo['profilePictureThumb']);
		echo '</div>';
	}
	echo '<div id="form">' . $createAccount;
?>

<!-- This shit below needs some help to get risen from the lame -->
	<form method="post" action="theme.php" name="themes">
		<tr>
			<td align=left colspan=2 class=header>What theme?
				<select name="friendshow">
					<option value="default">Default</option>
					<option value="hellner">Hellner</option>
					<option value="northug">Northug</option>
				</select>
				<input type="submit"  name=show value="submit">
			</td>
		</tr>
	</form>
	</div>
</div>
