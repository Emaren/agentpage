<?
  include('config.php');
  include('template/header.php');
  
  if ($_SESSION['isadmin']==0)
    header('Location: /login.php');
	
if ($_POST['submitedit']!='')
{

  $email=$_POST['email'];
  $fname=$_POST['fname'];
  $lname=$_POST['lname'];

  $name=$fname.' '.$lname;
  $sendemail='postmaster@myagentnow.ca';

  $bdytext='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
  $bdytext.='<title>Untitled Document</title></head><body><table width="710" height="946" cellpadding="0" cellspacing="0" style="background-image: url(http://www.myagentnow.ca/invite1.png); background-repeat: no-repeat; margin: 10px;">'."\n";
  $bdytext.='<tr><td><p style="font-family: arial, helvetica, sans-serif; font-size: 40px; color: #a8a9aa; margin-top: 415px; text-align:center;">'.$name.',</p></td></tr></table>'."\n";
  $bdytext.='<table width="710" height="946" cellpadding="0" cellspacing="0" style="background-image: url(http://www.myagentnow.ca/invite2.png); background-repeat: no-repeat; margin: 10px;"><tr>'."\n";
  $bdytext.='<td height="895" valign="bottom" align="right" style="padding-right: 10px;"><a href="http://myagentnow.ca/rsvp.php?name='.$name.'&email='.$email.'&attending=y" style="background-image: url(http://www.myagentnow.ca/button3.png); background-repeat:no-repeat; color: #660000; font-family: arial, helvetica, sans-serif; font-size: 18px; display: block; width: 149px; height: 36px; text-decoration: none; font-weight:bold; font-style: italic;">&nbsp;</a></td>'."\n";
  $bdytext.='<td height="895" valign="bottom" align="left" style="padding-left: 10px;"><a href="http://myagentnow.ca/rsvp.php?name='.$name.'&email='.$email.'&attending=n" style="background-image: url(http://www.myagentnow.ca/button2.png); background-repeat:no-repeat; color: #660000; font-family: arial, helvetica, sans-serif; font-size: 18px; display: block; width: 149px; height: 36px; text-decoration: none; font-weight:bold; font-style: italic;">&nbsp;</a></td>'."\n";
  $bdytext.='</tr><tr><td colspan="2"><p style="color: #fff; font-family: Arial, Helvetica, sans-serif; font-size: 16px; text-align: center;">Questions - <a href="mailto:info@myagentnow.ca" style="color: #3399ff;">info@myagentnow.ca</a></p></td>';
  $bdytext.='</tr></table></body></html>';  
  $headers = "From:" .$sendemail;
  $headers .= "\nMIME-Version: 1.0\r\n";
  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

  $to      = $email;
  $subject = "  Tuesday, September 27 @ 7pm - Grande Prairie Inn";
  $ok = @mail($to, $subject, $bdytext, $headers);
}

?>

<div class="backend_wrap">
<div style="width: 155px; float:left;">

<table class="hint">
<tr>
<td align="right">
</td>
<td align="left">
</td>
</tr>

<tr>
<td colspan="2" align="center"></td>
</tr>
</table>	
</div>


</div>


<div class="wrapper" style="margin: 20px auto; float:left; background: none;">
<div class="pageheader">
<?
  if ($ok)
    echo "<p>Your invitation was sent</p>";
?>
<form method="POST" action="<? echo $_SERVER['PHP_SELF']; echo $query; ?>">
<table class="edit_table" cellpadding="0" cellspacing="0" style="padding: 25px; clear: both; width: 100%;">
<tr><td>First Name</td><td><input type=text name="fname"></td></tr>
<tr><td>Last Name</td><td><input type=text name="lname"></td></tr>
<tr><td>Email</td><td><input type=text name="email"></td></tr>
<tr><td colspan="2"><input type="submit" name="submitedit" value="Save" class="savebtn brown_btn" /></td></tr>
</table></form>
</div></div>