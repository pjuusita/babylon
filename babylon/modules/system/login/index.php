<?php


echo "	<table class=logintable align=\"center\" style='width:100%'>";
echo "	 			<form name=\"loginform\" method=\"post\" action=\"" . getUrl('system/login/login') . "\">";
echo "	   				<input type=\"hidden\" name=\"x\" value=\"\">";
echo "	   				<input type=\"hidden\" name=\"y\" value=\"\">";

echo "	    			<tr>";
echo "	 					<td style='width:25%;'></td>";
echo "	  					<td></td>";
echo "	 					<td></td>";
echo "	  					<td style='width:25%;'></td>";
echo "	 				</tr>";

echo "	 				<tr>";
echo "						<td></td>";
echo "	 					<td class=title colspan=2>Login</td>";
echo "	  					<td></td>";
echo "	    			</tr>";
echo "	    			<tr>";
echo "	 					<td></td>";
echo "	  					<td>Email</td>";
echo "	  					<td style='padding: 2px 4px;'><input class=logininput name=\"username\" type=\"text\" id=\"email\" autofocus></td>";
echo "	  					<td></td>";
echo "	 				</tr>";
echo "	 				<tr>";
echo "						<td></td>";
echo "	 					<td>Password</td>";
echo "	  					<td style='padding: 2px 4px;'><input class=logininput name=\"password\" type=\"password\" id=\"password\" style='width:100%;'></td>";
echo "	  					<td></td>";
echo "					</tr>";
echo "					<tr>";
echo "						<td></td>";
echo "	 					<td colspan=2 style='padding: 6px 4px;text-align:right;'><input  class=loginbutton  type=\"submit\" value='Login'></td>";
echo "	 					<td></td>";
echo "	 				</tr>";
echo "	 				<tr>";
echo "	 					<td></td>";
echo "						<td colspan=2><a href=\"system/login/lost\">Forgot your password?</a></td>";
echo "						<td></td>";
echo "					</tr>";
echo "					<tr>";
echo "						<td style='height:10px'></td>";
echo "						<td></td>";
echo "						<td></td>";
echo "						<td></td>";
echo "					</tr>";
echo "				</form>";
echo "	</table>";


?>