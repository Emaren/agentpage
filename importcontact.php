<?php
include('config.php');
if ($_SESSION['uid']=='')
  header('location:/login.php');

$id=$_GET['id'];
$ok_msg='';
if ($_SESSION['isadmin']==0 and $_SESSION['add_client']=='n' and $id!=$_SESSION['uid'])
  header('location:/login.php');
  
  if ($_POST['submitedit']!='')
  {
     foreach($_FILES as $key=>$file)
     {
        switch($file['error'])
        {
          case 0:
            if($file['name'] != NULL)
            {
              $handle = fopen($file['tmp_name'], "r");
			  $error_msg='';
			  $cnt=0;
			  $hdr=false;
			  while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
			  {
			    if ($hdr)
				{
				  if ($data[0]!='' and $data[1]!='' and $data[20]!='')
				  {
                    $sql='insert into tb_users (firstname,lastname,email,password,realtorid) values ("'.$data[0].'","'.$data[1].'","'.$data[20].'",aes_encrypt("'.$data[20].'","tonyb"),'.$_SESSION['uid'].')';
				    $res=mysql_query($sql);
				    if (!$res)
				      $error_msg='Error importing user '.$data[1].' '.$data[2].'-'.mysql_error();
					else
					{
					     $subject='Your Personal Invitation to MyAgentNow.ca';
				         $bodytxt='Hello '.$data[0].' '.$data[1].",<br> You are now signed up for http://www.MyAgentNow.ca/login.php";
						 $bodytxt.="<br>Your Password is ".$data[20]."<br>Enjoy";
				  	     $to      = $data[20];
				         $sql='select * from tb_users where id='.$id;
						 $res2=mysql_query($sql);
						 $rw=mysql_fetch_array($res2,MYSQL_ASSOC);
				         $email   = $rw['email'];
				         $headers = "From:" .$email."\n";
				         $headers.= "Cc: " . $rw['email'] ."\n";
						 $headers .= "\nMIME-Version: 1.0\r\n";
						 $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				         $ok = @mail($to, $subject, $bodytxt, $headers);																				
  				         $cnt++;
					}			  
                  }
				}
				$hdr=true;
              }
			}
        }
	  }
	  if ($error_msg=='' && $cnt>0)
	    $ok_msg='Successfully imported '.$cnt.' users';	    
  }  

session_write_close();  
include('template/header.php');
unset($_SESSION['ok_msg']);
unset($_SESSION['error_msg']);
?>
<script src="/js/jquery.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
  $.noConflict();
</script>

<div class="backend_wrap">
<div style="width: 155px; float:left;">


<table class="hint">
<tr>
<td align="right">
<img src="/images/maphint.png" />
</td>
<td align="left">
* Hint
</td>
</tr>

<tr>
<td colspan="2" align="center">
In the Google Map Window, drag this icon to anywhere on the Map to access the amazing Street View.
</td>
</tr>
</table>
</div>


</div>

<div class="wrapper" style="margin: 20px auto; float:left; background: none;">

<div class="pageheader">
<p><? echo $txt; ?></p>
</div>

<form method="POST" enctype="multipart/form-data" action="<? echo $_SERVER['PHP_SELF']; if ($id!='') echo '?id='.$id;?>">
<table class="table_lists2" cellpadding="0" cellspacing="0" style="padding: 20px; width: 100%; min-width: 590px;clear:both;">
<tr><td>Contact List</td><td valign="top"><input name="contact_list" type="file"/></td></tr>
<tr><td><input type="submit" name="submitedit" value="Import" class="sendinvite" />
<?
  if ($error_msg!='')
     echo '<img src="/images/failed.png" border=0><p style="display:inline" class="fgt_pass">'.$error_msg.'</p><br><br>';
  if ($ok_msg!='')
    echo '<img src="/images/success.png" border=0><p style="display:inline" class="fgt_pass">'.$ok_msg.'</p><br><br>';	 
?>
</td></tr>
</table>
  
  </form>

<? include('emarentwitter.php'); ?>
</div>