<?
  include('config.php');
  if ($_SESSION['uid']=='')
    header('Location: /register.php');


  $sql='select * from tb_users where id='.$_SESSION['uid'];
  $res=mysql_query($sql);
  $use=mysql_fetch_array($res,MYSQL_ASSOC);
  $form="https://www.paypal.com/cgi-bin/webscr";
  $cid=$use['id'];

?>
<html>
<head>
<title></title>
<style type="text/css">

#login_wrap {
width: 960px;
text-align: center;
margin: 40px auto;
}

#login_wrap img {
clear: both;
}

#login_wrap input[type='text'], #login_wrap input[type='password'] {
background: transparent;
border-top: 1px solid #808080;
border-bottom: 1px solid #fff;
border-right: 1px solid #fff;
border-left: 1px solid #fff;
padding: 0px;
color: #fff;
font-size: 18px;
font-family: "Century Gothic", helvetica, arial, sans-serif;
text-transform: uppercase;
width: 280px;
text-align: center;
-webkit-border-radius: 25px;
-moz-border-radius: 25px;
border-radius: 25px;
margin-bottom: 20px;
}

#login_wrap .fgt_pass {
font-family: "Century Gothic", helvetica, arial, sans-serif;
font-size: 14px;
color: #A6A6A6;
text-decoration: none;
}

#login_wrap .login_btn {
background: url(images/new_login.png) no-repeat;
width: 104px;
height: 46px;
border: none;
margin-top: 16px;
}

#login_wrap .login_btn:hover {
cursor: pointer;
cursor: hand;
}

#login_wrap input[type="file"] {
margin-bottom: 10px;
}

</style>
</head>

<body style="background:url(images/loginbg.jpg) no-repeat scroll 50% 77px #020101;">
<form name="sub" method="POST" action="<? echo $form;?>">
 <input type="hidden" name="business" value="tonyblum@me.com ">
 <input type="hidden" name="cmd" value="_xclick-subscriptions">
 <input type="hidden" name="item_name" value="Subscription to http://www.myagentnow.ca">
 <input type="hidden" name="item_number" value="1">
 <input type="hidden" name="currency_code" value="CAD">
 <input type="hidden" name="a3" value="103.25">
 <input type="hidden" name="p3" value="1">
 <input type="hidden" name="t3" value="M">
 <input type="hidden" name="src" value="1">
 <input type="hidden" name="srt" value="30">
 <INPUT TYPE="HIDDEN" NAME="invoice" VALUE="<? echo $cid;?>">
<INPUT TYPE="HIDDEN" NAME="first_name" VALUE="<? echo $use['firstname'];?>">
<INPUT TYPE="HIDDEN" NAME="last_name" VALUE="<? echo $use['lastname'];?>">
<INPUT TYPE="HIDDEN" NAME="email" VALUE="<? echo $use['email'];?>">
<input name="page" type="hidden" value="posted">
<div id="login_wrap">

<img src="images/loginblurb.png" alt="Searching for real estate has never looked this good..." />
<br>
<img src="images/register_title.png" alt="MYAGENTNOW.CA" style="margin-top: 30px;" />

<img src="images/features.png" style="margin: 20px 0px 12px 50px;" />

<a href="mailto:info@myagentnow.ca"><img src="images/support.png" style="margin-bottom: 20px;" /></a>
<br>
<img src="images/agentpage_reg.png" style="margin-bottom: 10px;" />

<table cellpadding="0" cellspacing="0" style="margin: 0 auto; width: 700px; text-align: center;">

<tr>
<td><img src="images/first.png" alt="First Name" /></td>
<td><img src="images/last.png" alt="Last Name" /></td>
</tr>

<tr>
<td class="fgt_pass" style="font-size: 20px; padding-bottom: 10px; color: #fff;"><? echo $use['firstname'];?></td>
<td class="fgt_pass" style="font-size: 20px; padding-bottom: 10px; color: #fff;"><? echo $use['lastname'];?></td>
</tr>

<tr>
<td colspan="2"><img src="images/email.png" alt="E-mail Address" /></td>
</tr>

<tr>
<td colspan="2" class="fgt_pass" style="font-size: 20px; padding-bottom: 10px; color: #fff;"><? echo $use['email'];?></td>
</tr>

<tr>
<td colspan="2"><img src="images/regblurb.png" alt="Only $99/Month" /></td>
</tr>

<tr>
<td colspan="2"><input type="submit" style="background: url(images/register_btn.png) no-repeat; border: none; height: 36px; width: 161px; margin-top: 20px; cursor: pointer; cursor: hand;" name="register" value="" />
<?
  if ($error_msg!='')
     echo '<br><img src="/images/failed.png" border=0><p style="display:inline;color: #fff; text-transform: uppercase; margin-top: -10px; margin-bottom: 10px;" class="fgt_pass">'.$error_msg.'</p><br><br>';
?>

</td>
</tr>

</table>

<a style="color: #fff;" href="mailto:info@myagentnow.ca" class="fgt_pass">info@MyAgentNow.ca</a>
<p class="fgt_pass">#102 9525-98 Avenue<br>Grande Prairie, AB<br>T8V 0N8</p>
</div>
</form>
</body>

</html>

