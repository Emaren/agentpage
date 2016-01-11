<?php
include('config.php');

if ($_SESSION['uid']=='')
  header('location:/rd_login.php');
$id=$_GET['id'];

  if ($_GET['idel']!='')
  {
    $sql='select * from tb_client_images where id='.$_GET['idel'];
	$res=mysql_query($sql);
	if (mysql_num_rows($res)>0)
	{
	  $row=mysql_fetch_array($res,MYSQL_ASSOC);
	  unlink(realpath('./').'/images/'.$row['imagename'].'.'.$row['ext']);
	  unlink(realpath('./').'/images/'.$row['imagename'].'_sm1.'.$row['ext']);
	  unlink(realpath('./').'/images/'.$row['imagename'].'_sm4.'.$row['ext']);
	  $sql='delete from tb_client_images where id='.$_GET['idel'];
	  $res=mysql_query($sql);
	  if ($res)
	    $msg='Image #'.$_GET['idel'].' removed successfully';
      else
	    $err='Failed to remove image #'.$_GET['idel'].'-'.mysql_error();
	}
  }

$err='';
if ($_POST['submitedit']!='')
{
  $bname=basename($_FILES['brokerage_image']['name']);
  $pname=basename($_FILES['personal_image']['name']);
  handle_upload($id);
  if ($id!='')
  {
    $sql='update tb_users set gender="'.$_POST['gender'].'", firstname="'.htmlentities($_POST['firstname'],ENT_QUOTES).'",lastname="'.htmlentities($_POST['lastname'],ENT_QUOTES);
	$sql.='",phone="'.htmlentities($_POST['phone'],ENT_QUOTES).'",email="'.htmlentities($_POST['email'],ENT_QUOTES).'",password=aes_encrypt("'.$_POST['password'].'","tonyb")';
	$sql.=', twitter_account="'.htmlentities($_POST['twitter_account'],ENT_QUOTES).'",website="'.htmlentities($_POST['website'],ENT_QUOTES).'",brokerage_name="'.htmlentities($_POST['brokerage_name'],ENT_QUOTES).'"';
	if ($bname!='')
	  $sql.=',brokerage_image="'.$bname.'"';
	if ($pname!='')
	  $sql.=',personal_image="'.$pname.'"';
	$sql.=' where id='.$id;
	$res=mysql_query($sql);
	if ($res)
	  $msg='Updated Successfully';
	else
	  $err='Updated failed '.mysql_error();
  }
  else
  {
     $city_choice=false;
     foreach ($_POST['muni_id'] as $k=>$v)
	 {
       $city_choice=true;
     }
     if ($_SESSION['isadmin']!=0)
	 {
	   $realtor='y';
	   $derr='The email address and password must be entered and a default city must be chosen';
	   $realtorid='NULL';
	 }
	 else
	 {
	   $realtor='n';
       $city_choice=true;
       $derr='The email address and password must be entered';
       $realtorid=$_SESSION['uid'];
	 }
     $city_choice=false;
     foreach ($_POST['muni_id'] as $k=>$v)
	 {
       $city_choice=true;
     }
	 if ($_POST['email']!='' && $city_choice && $_POST['password']!='')
	 {
	   $sql='insert into tb_users (gender,firstname,lastname,phone,email,password,realtor,realtorid,twitter_account,brokerage_name,website,cdate';
	   if ($bname!='')
	     $sql.=',brokerage_image';
	   if ($pname!='')
	     $sql.=',personal_image';
	   $sql.=') values ("'.$_POST['gender'].'","'.htmlentities($_POST['firstname'],ENT_QUOTES).'","'.htmlentities($_POST['lastname'],ENT_QUOTES);
	   $sql.='","'.htmlentities($_POST['phone'],ENT_QUOTES).'","'.htmlentities($_POST['email'],ENT_QUOTES).'",aes_encrypt("'.$_POST['password'].'","tonyb"),"'.$realtor.'",'.$realtorid.',"'.htmlentities($_POST['twitter_account'],ENT_QUOTES);
	   $sql.='","'.htmlentities($_POST['brokerage_name'],ENT_QUOTES).'","'.htmlentities($_POST['website'],ENT_QUOTES).'","'.date('Y-m-d').'"';
	   if ($bname!='')
	     $sql.=',"'.$bname.'"';
	   if ($pname!='')
	     $sql.=',"'.$pname.'"';
	   $sql.=')';
	   $res=mysql_query($sql);
	   $id=mysql_insert_id();	   
	   if ($res)
	   {
	     $subject='Your Personal Invitation to MyAgentNow.ca';
         $bodytxt='Hello '.$_POST['firstname'].' '.$_POST['lastname'].",<br> You are now signed up for http://www.MyAgentNow.ca/rd_login.php";
		 $bodytxt.="<br>Your Password is ".$_POST['password']."<br>Enjoy";
  	     $to      = $_POST['email'];
         $email   = 'postmaster@myagentnow.ca';
         $sql='select * from tb_users where id='.$_SESSION['uid'];
		 $res2=mysql_query($sql);
		 $rw=mysql_fetch_array($res2,MYSQL_ASSOC);
         $headers = "From:" .$email."\n";
         $headers.= "Cc: tonyblum@me.com\n";
		 if ($rw['email']!='tonyblum@me.com')
           $header.= "Cc: " . $rw['email'] ."\n";
		 $headers .= "\nMIME-Version: 1.0\r\n";
		 $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
         $ok = @mail($to, $subject, $bodytxt, $headers);
	     $msg='Created Successfully';
	   }
	   else
	    $err='Creation failed '.mysql_error();
     }
	 else
	   $err=$derr;
  }
  if ($err=='' && isset($_POST['muni_id']))
  {
    $sql='delete from tb_realtor_municipality where realtorid='.$id;
	$res=mysql_query($sql);
    foreach ($_POST['muni_id'] as $k=>$v)
	{
	    $sql='insert into tb_realtor_municipality (realtorid,municipalityid) values ('.$id.','.$v.')';
		$res=mysql_query($sql);
	}
  }
}

if ($id!='')
{
  $sql='select *,aes_decrypt(password,"tonyb") as passwrd from tb_users where id='.$id;
  $res=mysql_query($sql);
  $use=mysql_fetch_array($res,MYSQL_ASSOC);
  if ($_SESSION['realtor']=='y')
    $txt='Edit Client';
  else
    $txt='Edit Realtor';
}
else
  if ($_SESSION['realtor']=='y')
     $txt='Create Client';
  else
     $txt='Create Realtor';

if ($err!='')
  $error_msg=$err;
if ($msg!=''  && $_GET['idel']=='')
  {
    $_SESSION['ok_msg']=$msg;
    session_write_close();
	header('Location: /rd_users.php');
  }
  else
    if ($msg!='')
      $ok_msg=$msg;

include('template/rd_header.php');

?>
<script src="/js/jquery.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
  $.noConflict();
</script>
<script src="/js/jquery.simpletip-1.3.1.js" type="text/javascript" charset="utf-8"></script>
<script src="js/prototype.js" type="text/javascript"></script>
<script src="js/scriptaculous.js" type="text/javascript"></script>

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


<div class="wrapper" style="margin: 20px auto; float:left; background: none;">

<div class="pageheader">
<p><? echo $txt; ?></p>
</div>

<form method="POST" enctype="multipart/form-data" action="<? echo $_SERVER['PHP_SELF']; if ($id!='') echo '?id='.$id;?>">
<table class="table_lists2" cellpadding="0" cellspacing="0" style="padding: 20px; width: 100%; min-width: 590px;clear:both;">
<tr>
<th>First Name</th>
<th>Last Name</th>
</tr>

<tr>
<td><input name="firstname" type="text" class="rounded" value="<? echo post_or_row($use,'firstname');?>") /></td>
<td><input name="lastname" type="text" class="rounded" value="<? echo post_or_row($use,'lastname');?>" /></td>
    <td rowspan="7" align="center"><input type=hidden name=neworder id=neworder>
	<div id="loading" style="display:none;height:100px;width:100px;position:absolute;top:265px;left:954px;z-index:50;padding-left:25px;"><img style="padding-left:0px;padding-top:10px" src="/images/84.gif" border="0"></div>
	<img src="images/uploadicon.jpg" /><span id="buttonplaceholder"></span><div id="container"><ul id="upload_list" class="ui-sortable" style="padding-top:5px;list-style-type: none;">
	<?
	   $sql='select * from tb_client_images where clientid='.$id;
	   $res=mysql_query($sql);
	   $jscript='';
	   while ($row=mysql_fetch_array($res,MYSQL_ASSOC))
	   {
	     echo '<li style="float:left;width:130px;height:130px;" id="img_'.$row['id'].'">';
		 echo '<img src="images/'.$row['imagename'].'_sm4.'.$row['ext'].'" border="0" />';
		 echo "</li>\n";
	     $jscript.=' $("#img_'.$row['id'].'").simpletip({ showEffect: "none",fixed: true, position: [50,-15], content: "<div><span style=\'margin-left:5px;height:16px;font-size:12px;padding-right:5px;\'>#'.$row['id'].'</span><a href=\'/rd_edituser.php?idel='.$row['id'].'&id='.$id.'\'><img border=\'0\' src=\'/images/trash.gif\' /></a></div>"});'."\n";
	   }
	?>
	</ul></div>
	</td>
  </tr>
<tr>
<th>Email</th>
<th>Password</th>
</tr>

<tr>
<td><input name="email" type="text" class="rounded" value="<? echo post_or_row($use,'email');?>"/></td>
<td valign="top"><input name="password" type="text" class="rounded" value="<? echo post_or_row($use,'passwrd');?>" /></td>
</tr>

<tr>
<th><? if ($_SESSION['isadmin']!=0) echo 'Municipality';?></th>
<th>Gender</th>
</tr>

<tr>
<td><? if ($_SESSION['isadmin']!=0)
       {
	     echo '<select class="rounded" multiple name=muni_id[]>';
		 $muni=array();
	     $sql='select municipalityid from tb_realtor_municipality where realtorid='.$id;
		 $res=mysql_query($sql);
		 while ($row=mysql_fetch_array($res,MYSQL_ASSOC))
		   $muni[]=$row['municipalityid'];
		 $sql='select * from tb_municipality where county="n"';
		 $res=mysql_query($sql);
		 while ($row=mysql_fetch_array($res,MYSQL_ASSOC))
		 {
		   echo '<option value='.$row['id'];
		   if (array_search($row['id'],$muni)!==false)
		     echo ' selected ';
		   echo '>'.$row['name'].'</option>';
		 }
	   }
?>
</td>
<td style="font-size: 16px;">
<span style="width: 100px; float: left">
<img src="/images/man.png" width="40px;" /><br />
Male <input type="radio" name="gender" <? if ($use['gender']=='m') echo ' checked '; ?> value="m" />
</span>
<span style="width: 100px; float: left;">
<img src="/images/woman.png" width="40px;" /><br />
Female <input type="radio" name="gender" value="f"  <? if ($use['gender']=='f') echo ' checked '; ?>  />
</span>
<div style="clear: both;"></div>
</td>
</tr>
<?
  if ($use['realtor']=='y' or ($_SESSION['realtor']=='y' and $id==$_SESSION['uid']) or $_SESSION['isadmin']==1)
  {
?>
<tr>
  <th>Twitter Account</th>
  <th>&nbsp;</th>
</tr>
<tr>
  <td valign="top"><input name="twitter_account" type="text" class="rounded" value="<? echo post_or_row($use,'twitter_account');?>" /></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <th>Brokerage Name</th>
  <th>Brokerage Image</th>
  <th>&nbsp;</th>
</tr>
<tr>
  <td valign="top"><input name="brokerage_name" type="text" class="rounded" value="<? echo post_or_row($use,'brokerage_name');?>" /></td>
  <td valign="top"><input name="brokerage_image" type="file"/></td>
  <td><? if ($use['brokerage_image']!='') echo '<img src="/images/'.$use['brokerage_image'].'" border=0>'; else echo '&nbsp;' ?></td>
</tr>


<tr>
<th>Brokerage Website</th>
<th></th>
<th></th>
</tr>

<tr>
<td valign="top">http://<input name="brokerage_site" type="text" style="width: 230px;" class="rounded" value="" /></td>
<td></td>
<td></td>
</tr>


<tr>
  <th>Personal Website</th>
  <th>Personal Image</th>
  <th>&nbsp;</th>
</tr>
<tr>
  <td valign="top">http://<input name="website" type="text" style="width:230px;" class="rounded" value="<? echo post_or_row($use,'website');?>" /></td>
  <td valign="top"><input name="personal_image" type="file"/></td>
  <td><? if ($use['personal_image']!='') echo '<img src="/images/'.$use['personal_image'].'" border=0>'; else echo '&nbsp;' ?></td>
</tr>
<? } ?>
<tr>


<td colspan="3">
<input type="submit" name="submitedit" value="Send Invitation" class="sendinvite" /><input type="submit" name="editclient" value="Edit Client" class="sendinvite" /><input type="submit" name="importcontact" value="Import Contact List" class="import" />
<?
  if ($error_msg!='')
     echo '<img src="/images/failed.png" border=0><p style="display:inline" class="fgt_pass">'.$error_msg.'</p><br><br>';
?>
</td>

</tr>
</table>
</form>

<? include ('emarentwitter.php'); ?>


</div>
<script type="text/javascript" src="<?echo $web_app_path;?>swfupload/swfupload.js"></script>
<script type="text/javascript" src="<?echo $web_app_path;?>js/swfupload.swfobject.js"></script>
<script type="text/javascript" src="<?echo $web_app_path;?>js/swfupload.queue.js"></script>
<script type="text/javascript" src="<?echo $web_app_path;?>js/handlers.js"></script>
<script type="text/javascript">
tbpage='/rd_edituser.php';
jQuery(document).ready(function($) {
<? echo $jscript;
  if ($id=='')
    $iid=0;
  else
    $iid=$id;
?>
});
var swfu;

	var settings = {
		flash_url : "<?echo $web_app_path;?>swfupload/swfupload.swf",
		upload_url: "<?echo $web_app_path;?>upload.php?cid=<? echo $iid;?>",
        post_params: {"PHPSESSID": "<? echo session_id();?>"},
		file_size_limit : "100 MB",
		file_types : "*.jpg;*.png;*.gif;",
		file_types_description : "All Files",
		file_upload_limit : 0,
		file_queue_limit : 1,
		custom_settings : {
			upload_target : "upload_list",
			cancelButtonId : ""
		},
		debug: false,
		file_queue_error_handler : fileQueueError,
		file_dialog_complete_handler : fileDialogComplete,
		upload_error_handler : uploadError,
		upload_success_handler : uploadSuccess,
		upload_complete_handler : uploadComplete,

		// Button Settings
		button_placeholder_id : "buttonplaceholder",
        button_image_url : "<?echo $web_app_path;?>images/hyperlink_submit_bg2-2.jpg",
		button_width: 334,
		button_height: 22,

		button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
		button_cursor: SWFUpload.CURSOR.HAND

	};
	swfu = new SWFUpload(settings);

    Sortable.create('upload_list',{tag:'li',overlap:'horizontal',constraint:false,onUpdate:updateList});

    function updateList(container)
	{
	   var params = Sortable.serialize(container.id);
       document.getElementById('neworder').value=params;
    }

</script>
</body>
</html>
<?
  function post_or_row($rw,$field)
  {
    $val='';
	if ($_POST[$field]!='')
	  $val=$_POST[$field];
	else
	  if ($rw[$field]!='')
	    $val=$rw[$field];
	return $val;
  }

function handle_upload($id)
{
    global $path,$config,$newname,$filename;

    $pth=realpath('.').'\\images';
    if (!is_dir($pth))
      mkdir($pth);

   $err='';

   foreach($_FILES as $key=>$file)
   {
        switch($file['error'])
       {
        case 0:
           if($file['name'] != NULL)
             $err = processFile($file['name'],$file['tmp_name'],$key);
           break;
        case (1|2):
           $err = 'file upload is too large';
           break;
        case (6|7):
           $err = 'internal error – flog the webmaster';
            break;
       }
   }
   return $err;
}

    function processFile($filename,$tmp_name,$key)
    {
        $pth=realpath('.').'\\images';
        $filename = basename($filename);
        $ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
        if (($ext == 'jpg') || ($ext=='gif') || ($ext=='png') || ($ext=='jpeg'))
        {
           $newname=$pth.'\\'.strtolower($filename);
           if (!(move_uploaded_file($tmp_name,$newname)))
               $err='Error: A problem occurred during file upload!';
        }
        else
          $err='Error: Only .jpg,gif,png,jpeg files are accepted for upload';

        if ($key=='brokerage_image')
          save_resize_image($newname,120,145,$ext,'');
		else
          save_resize_image($newname,358,245,$ext,'');
  	  return $err;
    }

 function save_resize_image($file,$height,$width,$ext,$suffix)
 {
   $pth=realpath('.').'\\images';
   $file_tmp=$file;
   if($ext == "jpg")
     $new_img = imagecreatefromjpeg($file_tmp);
   elseif($ext == "png")
        $new_img = imagecreatefrompng($file_tmp);
      elseif($ext == "gif")
            $new_img = imagecreatefromgif($file_tmp);
   list($owidth, $oheight) = getimagesize($file_tmp);
   $source_aspect_ratio = $owidth / $oheight;
   $thumbnail_aspect_ratio = $width / $height;
   $newwidth=$width;
   $newheight=$height;
   if ( $owidth <= $width && $oheight <= $height )
   {
    $newwidth = $owidth;

    $newheight = $oheight;
   }
   elseif ( $thumbnail_aspect_ratio > $source_aspect_ratio )
     $newwidth = ( int ) ( $height * $source_aspect_ratio );
    else
     $newheight = ( int ) ( $width / $source_aspect_ratio );
   $resized_img = imagecreatetruecolor($newwidth,$newheight);
   imagecopyresampled($resized_img, $new_img, 0, 0, 0, 0, $newwidth, $newheight, $owidth, $oheight );
   $filename=basename($file);
   $filename=substr($filename, 0,strrpos($filename, '.')).$suffix.'.jpg';
   ImageJpeg ($resized_img,$pth.'\\'.$filename,100);
   ImageDestroy ($resized_img);
   ImageDestroy ($new_img);
}

?>