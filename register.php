<?
  include('config.php');

  if ($_POST['page']!='')
  {
    $error='';
    if ($_POST['first']=='')
      $error='first name';
    if ($_POST['last']=='')
    {
      if ($error!='')
        $error.=',';
      $error.='last name';
    }
    if ($_POST['emal']=='')
    {
      if ($error!='')
        $error.=',';
      $error.='email address';
    }
    $sql='select * from tb_users where email="'.htmlentities($_POST['emal'],ENT_QUOTES).'"';
    $res=mysql_query($sql);
    if (mysql_num_rows($res)>0)
    {
      $r=mysql_fetch_array($res,MYSQL_ASSOC);
      if ($r['payment_status']=='y' or $r['add_client']=='y')
        $error_msg='There is already an account with that email, please choose another';
      else
        $cid=$r['id'];
    }
    if ($error!='')
      $error_msg='The following fields are required '.$error;
    else
     if ($error_msg=='')
     {
       $upload1=$_FILES['upload1']['name'];
       $upload2=$_FILES['upload2']['name'];
       $sql='insert into tb_users (firstname,lastname,email';
       if ($upload1!='')
         $sql.=',personal_image';
       if ($upload2!='')
         $sql.=',brokerage_image';
       $sql.=') values ("'.htmlentities($_POST['first'],ENT_QUOTES).'","'.htmlentities($_POST['last'],ENT_QUOTES).'","'.htmlentities($_POST['emal'],ENT_QUOTES).'"';
       if ($upload1!='')
         $sql.=',"'.$upload1.'"';
       if ($upload2!='')
         $sql.=',"'.$upload2.'"';
       $sql.=')';
       $res=mysql_query($sql);
	   $cid=mysql_insert_id();
   	   $subject = 'Images upload';
       $to = 'tonyblum@me.com';    //  your email
       $email   = 'tonyblum@me.com';
       $headers = "From:" .$_POST['email'];
       $body .= "from ".$_POST['email']." on ".date('m/d/Y')."\n";
       if ($upload2!='')
         $body.='Brokerage Image: http://myagenotnow.ca/images/'.$upload2."\n";
       if ($upload1!='')
         $body.='Personal Image: http://myagenotnow.ca/images/'.$upload1;
       $body .= " at ".date('g:i A');
       mail($to, $subject, $body, $headers);

    }
  }

  $enc='enctype="multipart/form-data"';
  if ($cid=='')
    $form="/register.php";
  else
  {
    $enc='';
    $form="https://www.paypal.com/cgi-bin/webscr";
  }
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
<form name="sub" method="POST" action="<? echo $form;?>"  <? echo $enc;?>>
<?
  if ($cid!='')
  {
    $mth=date("m")+1;
    $d=date("d");
    $yr=date("y");
    if ($mth>12)
    {
      $mth=1;
      $yr++;
    }
    $tomorrow = date("Y/m/d",mktime(0, 0, 0, $mth, $d, $yr));
    if ((date("d")+1)>27)
      $unit="eom";
    else
      $unit="month";
?>
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
<INPUT TYPE="HIDDEN" NAME="first_name" VALUE="<? echo $_POST['first'];?>">
<INPUT TYPE="HIDDEN" NAME="last_name" VALUE="<? echo $_POST['last'];?>">
<INPUT TYPE="HIDDEN" NAME="email" VALUE="<? echo $_POST['emal'];?>">
<?
  }
if ($_POST['page']=='')
  echo '<input name="page" type="hidden" value="posted">';
?>
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
<td><input type="text" name="first" value="" /></td>
<td><input type="text" name="last" value="" /></td>
</tr>

<tr>
<td colspan="2"><img src="images/email.png" alt="E-mail Address" /></td>
</tr>

<tr>
<td colspan="2"><input type="text" name="emal" value="" /></td>
</tr>

<tr>
<td colspan="2"><img src="images/upload1.png" alt="Upload Your Image" /></td>
</tr>

<tr>
<td colspan="2"><? if ($_POST['page']=='') echo '<input type="file" name="upload1" value="" />';?></td>
</tr>

<tr>
<td colspan="2"><img src="images/upload2.png" alt="Upload Brokerage Image" /><p style="color: #fff; text-transform: uppercase; margin-top: -10px; margin-bottom: 10px;" class="fgt_pass">(optional)</p></td>
</tr>

<tr>
<td colspan="2"><? if ($_POST['page']=='') echo '<input type="file" name="upload2" value="" />';?></td>
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
<?
  if ($cid!='')
  {
    echo '<script type="text/javascript">';
	echo 'document.sub.submit();';
	echo '</script>';
    $sql='select * from tb_users where id='.$cid;
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result,MYSQL_ASSOC);
	$subject = 'Images uploaded ';
    $to = 'tonyblum@me.com';    //  your email
    $body =  "From ".$_POST['first'].' '.$_POST['last'].' email: '.$_POST['email'];
    $email   = 'tonyblum@me.com';
    $headers = "From:" .$_POST['email'];
    if ($row['brokerage_image']!='')
      $body.='Brokerage Image: http://myagenotnow.ca/images/'.$row['brokerage_image']."\n";
    if ($row['personal_image']!='')
      $body.='Personal Image: http://myagenotnow.ca/images/'.$row['personal_image'];
    mail($to, $subject, $body, $headers);
  }
?>
</div>
</form>
</body>

</html>

<?
    $nooutput=true;
    include('upload.php');
?>