<br />
{WSTEP}

<form method="post" class="jqTransform">
	<input type="hidden" name="a" value="l">
	<input type="hidden" name="url" value="{URL}">
	<table style="margin: 20px auto; width: 300px;">
		<tr>
			<td style="vertical-align: middle;">{T_USERNAME}:</td>
			<td style="padding: 2px 0px !important;"><input class="txtm" type="text" name="lo_login" value="{USERNAME}" maxlength="100"/></td>
		</tr>
		<tr>
			<td style="vertical-align: middle;">{T_PASSWORD}:</td>
			<td style="padding: 2px 0px !important;"><input class="txtm" type="password" name="lo_haslo" value="{PASSWORD}" maxlength="100"/></td>
		</tr>
		<tr>
			<td></td>
			<td style="padding: 10px 0px !important;">
				<input type="submit" name="go" value="{T_LOGIN_SEND}" class="pushb" onClick="javascript:userminilogin();" />
			</td>
		</tr>
	</table>
</form>

<p style="text-align: center; margin: 20px 0px; font-size: 10px;">
	{T_CONTINUE_WITHOUT_LOGIN_TEXT} <a href="{URL}" target="_self" style="color:rgb(132, 132, 132);">{T_CONTINUE_WITHOUT_LOGIN}</a>	
</p>
