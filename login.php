<?
  include('config.php');

  if ($_POST['username']!='')
  {
      if ($_POST['username']=='')
        $error_msg='Username must be entered<br>';
      if ($_POST['password']=='')
        $error_msg='Password must be entered<br>';
      if ($error_msg=='')
      {
       $sql='select * from tb_users where email="'.$_POST['username'].'"';
       $sql.=' and lower("'.$_POST['password'].'")=lower(convert(aes_decrypt(password,"tonyb") using latin1))';
       $result=mysql_query($sql);
       if ($result and mysql_num_rows($result)>0)
       {
          $row=mysql_fetch_array($result, MYSQL_ASSOC);
          $_SESSION['uname']=$row['username'];
          $_SESSION['uid']=$row['id'];
          $_SESSION['email']=$row['email'];
          $_SESSION['password']=$_POST['password'];
          $_SESSION['firstname']=$row['firstname'];
          $_SESSION['lastname']=$row['lastname'];
          $_SESSION['isadmin']=$row['isadmin'];
		  $_SESSION['realtor']=$row['realtor'];
		  $_SESSION['add_client']=$row['add_client'];
		  $_SESSION['mlsid']=$row['mlsid'];
		  $_SESSION['cdate']=$row['cdate'];
		  if ($row['payment_status']=='y' and $row['add_client']=='y')
		    $_SESSION['paid']='y';
		  else
		    $_SESSION['paid']='n';
          $sql='insert into tb_client_traffic (userid,listingid,cdate) values ('.$_SESSION['uid'].',0,"'.date("Y-m-d H:m:s").'")';
	 	  $res=mysql_query($sql);
		  if ($_POST['redirect']!='')
		  {
		    if ($_POST['redirect']=='city')
			{
			  header('Location: /gp_city7/map20a.php');
			  die();
		    }
			if ($_POST['redirect']=='county')
			{
			  header('Location: /gp_county2/map2.php');
		      die();
			}
		  }

          if ($row['isadmin']==1)
            header("Location: ".$web_app_path."users.php");
          else
		    if ($row['realtor']=='y')
              header("Location: ".$web_app_path."traffic.php");
		    else  // they are a realtor client, get the city they should peruse
			{
			  $sql='select * from tb_realtor_municipality trm,tb_municipality tm where realtorid='.$row['realtorid'].' and tm.id=trm.municipalityid';
			  $res=mysql_query($sql);
			  $lrow=mysql_fetch_array($res,MYSQL_ASSOC);
			  if ($res)
			    header("Location: ".$lrow['location']);
			}
       }
       else
       {
         $error_msg='Invalid Login!';
       }
      }
   }

    if ($_POST['username']!='' && $_POST['recover']=='y')
   {
      $error_msg='';
      if ($_POST['username']=='')
        $error_msg='Username must be entered<br>';
	  else
	  {
       $sql='select * from tb_users where email="'.$_POST['username'].'"';
       $result=mysql_query($sql);
       if ($result and mysql_num_rows($result)>0)
       {
          $row=mysql_fetch_array($result, MYSQL_ASSOC);
          $newpwd=generatePassword(8);
          $sql='update tb_users set password=aes_encrypt("'.$newpwd.'","tonyb") where id='.$row['id'];
          $result=mysql_query($sql);
	     $bdytext='<html><body><table width="550"><tr><td><h2 style="font-family: "Lucida Grande", "Lucida Sans", Helvetica, Arial, sans-serif; font-size: 24px; font-weight: bold; letter-spacing: -1px;">Your password has been reset to  '.$newpwd.' for the site myrealtornow.ca';
		 $bdytext.='!</h2></td></tr><tr><td style="font-family: "Lucida Grande", "Lucida Sans", Helvetica, Arial, sans-serif; font-size: 14px; font-weight: normal;"><p>Hi '.$row['firstname'].' '.$row['lastname'].',</p><p>Your password has been reset to '.$newpwd.' for myrealtornow.ca</p>';
		 $bdytext.='<br><table width="100%"><tr><td style="border: 1px solid #b4d8ea; background-color: #ebf3fe; padding: 20px;"><p><span style="font-weight: bold;">You may login here:</span><br> <a style="color: #4699d0;" ';
		 $bdytext.='href="http://www.myrealtornow.ca/login.php">http://www.myrealtornow.ca/login.php</a></p></td></tr></table><br></td></tr></table></body></html>';
		 $to      = $row['email'];
         $email   = 'postmaster@myrealtornow.ca';
         $subject = "Password recovery for myrealtornow.ca";
         $headers = "From:" .$email;
		 $headers .= "\nMIME-Version: 1.0\r\n";
		 $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
         $ok = @mail($to, $subject, $bdytext, $headers);
         if ($ok)
           $ok_msg="<p>An email has been sent to the account on file with a new password</p>";

	   }
	  }
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
}

#login_wrap .county {
cursor: pointer;
cursor: hand;
width: 306px;
height: 256px;
border: none;
z-index: 1;
left: 50px;
position: relative;
}

#login_wrap .city {
cursor: pointer;
cursor: hand;
width: 302px;
height: 295px;
border: none;
position: relative;
left: -46px;
top: -17px;
z-index: 5;
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

</style>
<script language="javascript" type="text/javascript">
<!--

function checkEnter(e)
{
  var characterCode; // literal character code will be stored in this variable
  var e=e||window.event;
  if(e && e.which)
  { //if which property of event object is supported (NN4)
    e = e;
    characterCode = e.which; //character code is contained in NN4's which property
  }
  else
  {
    e = event;
    characterCode = e.keyCode //character code is contained in IE's keyCode property
  }

  if(characterCode == 13)
  { //if generated character code is equal to ascii 13 (if enter key)
     document.forms[0].submit() //submit the form
     return false
  }
  else
  {
   return true
  }
}

function recoverpassword()
{
  document.getElementById('recover').value='y';
  document.getElementById('passwordrow').style.display='none';
  document.getElementById('email').style.display='none';
  document.getElementById('recoveryrow').style.display='block';
}

-->
</script>
</head>

<body style="background:url(images/loginbg.jpg) no-repeat scroll 50% 55px #020101; text-align: center;">
<div id="login_wrap">

<img src="images/loginblurb.png" alt="SEARCHING FOR REAL ESTATE HAS NEVER LOOKED THIS GOOD..." style="margin-bottom: 60px;" />
<br>
<img src="images/myrealtornow_gp_title.png" alt="MYREALTORNOW.CA/GP" />
<br>
<img src="images/county_btn.png" class="county" name="login" onClick="document.getElementById('redirect').value='county'; document.forms[0].submit();" />
<img src="images/city_btn.png" class="city" name="login" onClick="document.getElementById('redirect').value='city'; document.forms[0].submit();" />
<br>
<?
   if ($error_msg!='')
     echo '<img src="/images/failed.png" border=0><p style="display:inline" class="fgt_pass">'.$error_msg.'</p><br><br>';
?>
<form method="POST" action="login.php" style="margin-bottom: 0;">
<div id="email" style="display:block"><input id="redirect" type=hidden name="redirect">
<img src="images/emailimg.png" alt="E-MAIL" style="margin-bottom: 5px;" />
</div>
<div class="fgt_pass" id="recoveryrow" style="display:none">
Enter the email address of the account you wish to<br> recover the password for:
</div>
<input type="text" name="username" value="<? echo $_POST['username'];?>" onKeyPress="checkEnter(event)" />
<input type="hidden" name="recover" id="recover" value="n" />
<br>
<br>
<div id="passwordrow" style="display:block">
<img src="images/passwordimg.png" alt="PASSWORD" style="margin-bottom: 5px;" />
<br>
<input type="password" name="password" value="" onKeyPress="checkEnter(event)" />
</div>
<input type="submit" value="" name="login" class="login_btn" />
</form>
<a class="fgt_pass" href="javascript:recoverpassword();">Forgotten Password?</a>
<br>
<br>
<img src="images/login_blurb.png" alt="PRESENTED TO THE GRANDE PRAIRIE MARKET EXCLUSIVELY BY EMAREN TECHNOLOGIES" />
<br>
<br>
<input style="display: inline; background: none;" type="checkbox" name="agree" value="yes" /> <p style="display: inline;" class="fgt_pass">I agree to the <a style="color: #fff;" href="Emaren_Licensing_Agreement.pdf" class="fgt_pass">Terms &amp; Conditions</a></p>
<br>
<p style="display: inline;" class="fgt_pass"><a style="color: #fff;" href="register.php" class="fgt_pass">Sign Up Now</a></p>

</div>

</body>

</html>

<?

function generatePassword ($length = 8)
{

  // start with a blank password
  $password = "";

  // define possible characters
  $possible = "0123456789bcdfghjkmnpqrstvwxyz";

  // set up a counter
  $i = 0;

  // add random characters to $password until $length is reached
  while ($i < $length) {

    // pick a random character from the possible ones
    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);

    // we don't want this character if it's already in the password
    if (!strstr($password, $char)) {
      $password .= $char;
      $i++;
    }

  }

  // done!
  return $password;

}

?>