<?
  include('config.php');

  if ($_POST['submit']!='')
  {
      if ($_POST['username']=='')
        $err_msg='Username must be entered<br>';
      if ($_POST['password']=='')
        $err_msg='Password must be entered<br>';
      if ($err_msg=='')
      {
       $sql='select * from tb_users where email="'.$_POST['username'].'"';
       $sql.=' and password=aes_encrypt("'.$_POST['password'].'","tonyb")';
       $result=mysql_query($sql);
       if ($result and mysql_num_rows($result)>0)
       {
          $row=mysql_fetch_array($result, MYSQL_ASSOC);
          $_SESSION['uname']=$row['username'];
          $_SESSION['uid']=$row['id'];
          $_SESSION['password']=$_POST['password'];
          $_SESSION['firstname']=$row['firstname'];
          $_SESSION['lastname']=$row['lastname'];
          $_SESSION['isadmin']=$row['isadmin'];
		  $_SESSION['realtor']=$row['realtor'];		  
		  
          if ($row['isadmin']==1)
            header("Location: ".$web_app_path."users.php");
          else
		    if ($row['realtor']=='y')
              header("Location: ".$web_app_path."users.php");
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
         $error_msg='<div id=login_message_red>Invalid Login!</div>';
       }
      }
   }

include('template/header.php');
?>



<div id="login_div">
<form method="POST" action="<? echo $_SERVER['PHP_SELF'];?>">
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
<td class="label"><img src="images/email.jpg" alt="E-mail" /></td>
</tr>

<tr>
<td><input type="text" name="username" value="<? echo $_POST['username'];?>" /></td>
</tr>

<tr>
<td class="label"><img src="images/password.jpg" alt="Password" /></td>
</tr>

<tr>
<td><input type="password" name="password" value="" /></td>
</tr>

<tr>
<td><input type="submit" name="submit" value=" " /></td>
</tr>

</form>
</div>
</body>
</html>
