<?php
echo '<div class="sideBar default">';
echo' Ti på Topp
	 <table>
		<tr>
			<th>1.</th>
			<td>&nbsp;'. $this->vars['topTenKeys'][0] . '   ' . $this->vars['topTen'][$this->vars['topTenKeys'][0]] . '</td>
		</tr>
		<tr>
			<th>2.</th>
			<td>&nbsp;'. $this->vars['topTenKeys'][1] . '   ' . $this->vars['topTen'][$this->vars['topTenKeys'][1]].'</td>
		</tr>
		<tr>
			<th>3.</th>
			<td>&nbsp;'. $this->vars['topTenKeys'][2] . '   ' . $this->vars['topTen'][$this->vars['topTenKeys'][2]].'</td>
		</tr>
	</table>
</div>';