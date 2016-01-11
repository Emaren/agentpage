<?

include('config.php');
$handle = fopen("invites.csv", "r");
$user=0;
while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
{
  $email=$data[0];
  $fname=$data[1];
  $lname=$data[2];
  $sex=$data[3];
  $sql='insert into tb_users (firstname,lastname,email,password,gender,cdate,add_client,realtor) values ("'.$fname.'","'.$lname.'","'.$email.'",aes_encrypt("'.$email.'","tonyb"),"'.$sex.'","'.date('Y-m-d').'","n","y")';
  $res=mysql_query($sql);
  if ($res)
  {
   $users++;
   $rid=mysql_insert_id();
   $sql2='insert into tb_realtor_municipality (realtorid,municipalityid) values ('.$rid.',1)';
   $res=mysql_query($sql2);
   echo mysql_error();
   $to = $email;
   $headers = "From: tonyblum@me.com\r\nReply-To: tonyblum@me.com";
   $headers .= "\nMIME-Version: 1.0\r\n";
   $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
   $bdytext='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
   $bdytext.='<title>Untitled Document</title></head><body><table width="710" height="946" cellpadding="0" cellspacing="0" style="background-image: url(http://www.myagentnow.ca/invite1.png); background-repeat: no-repeat; margin: 10px;">'."\n";
   $bdytext.='<tr><td><p style="font-family: arial, helvetica, sans-serif; font-size: 40px; color: #a8a9aa; margin-top: 415px; text-align:center;">'.$name.',</p></td></tr></table>'."\n";
   $bdytext.='<table width="710" height="946" cellpadding="0" cellspacing="0" style="background-image: url(http://www.myagentnow.ca/invite2.png); background-repeat: no-repeat; margin: 10px;"><tr>'."\n";
   $bdytext.='<td height="895" valign="bottom" align="right" style="padding-right: 10px;"><a href="http://myagentnow.ca/rsvp.php?name='.$name.'&email='.$email.'&attending=y" style="background-image: url(http://www.myagentnow.ca/button3.png); background-repeat:no-repeat; color: #660000; font-family: arial, helvetica, sans-serif; font-size: 18px; display: block; width: 149px; height: 36px; text-decoration: none; font-weight:bold; font-style: italic;">&nbsp;</a></td>'."\n";
   $bdytext.='<td height="895" valign="bottom" align="left" style="padding-left: 10px;"><a href="http://myagentnow.ca/rsvp.php?name='.$name.'&email='.$email.'&attending=n" style="background-image: url(http://www.myagentnow.ca/button2.png); background-repeat:no-repeat; color: #660000; font-family: arial, helvetica, sans-serif; font-size: 18px; display: block; width: 149px; height: 36px; text-decoration: none; font-weight:bold; font-style: italic;">&nbsp;</a></td>'."\n";
   $bdytext.='</tr><tr><td colspan="2"><p style="color: #fff; font-family: Arial, Helvetica, sans-serif; font-size: 16px; text-align: center;">Questions - <a href="mailto:info@myagentnow.ca" style="color: #3399ff;">info@myagentnow.ca</a></p></td>';
   $bdytext.='</tr></table></body></html>';
   $subject = "Trial membership for http://myagentnow.ca";
/*   $ok = @mail($to, $subject, $bdytext, $headers);
   if ($ok)
   {
      echo $to." sent\n";
      $ok_msg="<p>Your invitation was sent</p>";
   }
   else
       $bad[]=$row['email'];
    */
  }
}
$_SESSION['ok_msg']='Setup '.$users.' trial accounts';
session_write_close();
header('Location: /users.php');
?>