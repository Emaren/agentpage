<?
  include('config.php');
  if ($_SESSION['isadmin']==0)
    header('Location: login.php');

  $id=$_GET['id'];
  if ($id=='')
    header('Location: subdivisions.php');
  else
    $txt='Edit ';

  if ($_GET['idel']!='')
  {
    $sql='select * from tb_subdivision_images where id='.$_GET['idel'];
	$res=mysql_query($sql);
	if (mysql_num_rows($res)>0)
	{
	  $row=mysql_fetch_array($res,MYSQL_ASSOC);
	  unlink(realpath('./').'/images/'.$row['imagename'].'.'.$row['ext']);
	  unlink(realpath('./').'/images/'.$row['imagename'].'_sm1.'.$row['ext']);
	  unlink(realpath('./').'/images/'.$row['imagename'].'_sm4.'.$row['ext']);
	  $sql='delete from tb_subdivision_images where id='.$_GET['idel'];
	  $res=mysql_query($sql);
	  if ($res)
	    $msg='Image #'.$_GET['idel'].' removed successfully';
      else
	    $err='Failed to remove image #'.$_GET['idel'].'-'.mysql_error();
	}
  }

  if ($_POST['post42']!='')
  {
	$err='';

    $sql=' update tb_subdivision set convenant="'.htmlentities($_POST['convenant'],ENT_QUOTES).'", `desc`="'.htmlentities($_POST['desc'],ENT_QUOTES).'", no_of_homes="'.htmlentities($_POST['no_of_homes'],ENT_QUOTES).'", amenities="'.htmlentities($_POST['amenities'],ENT_QUOTES);
	$sql.='", links="'.htmlentities($_POST['links'],ENT_QUOTES).'",residences="'.htmlentities($_POST['residences'],ENT_QUOTES).'" where id='.$id;
	$res=mysql_query($sql);
	if ($res)
	{
      $sql='delete from tb_subdivision_school where subdivisionid='.$id;
	  $res=mysql_query($sql);
      foreach ($_POST['schools'] as $k=>$v)
	  {
	    $sql='insert into tb_subdivision_school (subdivisionid,schoolid) values ('.$id.','.$v.')';
		$res=mysql_query($sql);
	  }
	  $msg='Successfully updated subdivision';
	}
	else
      $err='Failure updating listing '.mysql_error();

  }

	if ($_POST['neworder']!='')
	{
	    $list=explode('&',$_POST['neworder']);
		$j=0;
		for ($i=0;$i<count($list);$i++)
		{
		  $k=strpos($list[$i],'=')+1;
		  $m=substr($list[$i],$k);
		  $sql='update tb_subdivision_images set ordr='.$j.' where id='.$m;
		  $res=mysql_query($sql);
		  $j++;
		}
	}

  if ($id!='')
  {
    $sql='select * from tb_subdivision where id='.$id;
	$res=mysql_query($sql);
	$list=mysql_fetch_array($res,MYSQL_ASSOC);
  }

  if ($err!='')
    $error_msg=$err;
  if ($msg!='')
    $_SESSION['ok_msg']=$msg;
  include('template/header.php');
?>
<script src="/js/jquery.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
  $.noConflict();
</script>
<script src="/js/jquery.simpletip-1.3.1.js" type="text/javascript" charset="utf-8"></script>
<script src="js/prototype.js" type="text/javascript"></script>
<script src="js/scriptaculous.js" type="text/javascript"></script>

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
<p>Edit Subdivision</p>
</div>

<form method="POST" action="<? echo $_SERVER['PHP_SELF']; if ($id!='') echo '?id='.$id; ?>">
  <input type=hidden name=neworder id=neworder>
<table class="edit_table" cellpadding="0" cellspacing="0" style="padding: 25px; width: 100%;clear:both;">
  <tr>
    <th width="160">Name</th>
    <th width="160">No of Homes</th>
  </tr>
  <tr>
    <td><? echo $list['name']; ?></td>
    <td><input name="no_of_homes" type="text" class="rounded" value="<? echo post_or_row($list,'no_of_homes');?>" /></select><input type=hidden name=post42 value="42"></td>
  </tr>
  <tr>
    <th colspan="2">Desc</th>
    <th colspan="2">Amenities</th>
  </tr>
  <tr rowspan="5">
    <td colspan="2"><textarea  name="desc" type="text" class="rounded" value="<? echo post_or_row($list,'desc');?>" style="width: 390px;height:180px;margin-right:15px;" /><? echo post_or_row($list,'desc');?></textarea></td>
    <td colspan="2"><textarea name="amenities" class="rounded" style="width: 390px; height:180px;"  value="<? echo post_or_row($list,'amenities');?>"><? echo post_or_row($list,'amenities');?></textarea></td>
  </tr>
  <tr>
    <th colspan="2">Residences</th>
    <th colspan="2">Links</th>
  </tr>
  <tr>
    <td colspan="2"><textarea  name="residences" type="text" class="rounded" value="<? echo post_or_row($list,'residences');?>" style="width: 390px;height:180px;margin-right:15px;" /><? echo post_or_row($list,'residences');?></textarea></td>
    <td colspan="2"><textarea name="links" class="rounded" style="width: 390px; height:180px;"  value="<? echo post_or_row($list,'links');?>"><? echo post_or_row($list,'links');?></textarea></td>

  </tr>
  <tr>
    <th colspan="2">Schools</th>
    <th colspan="2">Restrictive Convenants</th>
  </tr>
  <tr>
    <td colspan="2"><select class="rounded" multiple style="height:auto;" name=schools[]>
<?
		 $schools=array();
	     $sql='select schoolid from tb_subdivision_school where subdivisionid='.$id;
		 $res=mysql_query($sql);
		 while ($row=mysql_fetch_array($res,MYSQL_ASSOC))
		   $schools[]=$row['schoolid'];
		 $sql='select * from tb_schools';
		 $res=mysql_query($sql);
		 while ($row=mysql_fetch_array($res,MYSQL_ASSOC))
		 {
		   echo '<option value='.$row['id'];
		   if (array_search($row['id'],$schools)!==false)
		     echo ' selected ';
		   echo '>'.$row['name'].'</option>';
		 }
?>
    </select>
	</td>
	<td colspan="2">
	  <input name="convenant" style="width:388px;" class="rounded" type="text" value="<? echo post_or_row($list,'convenant');?>">
	</td>
	</tr>
	<tr>
    <td colspan="3">
	<div id="loading" style="display:none;height:100px;width:100px;position:absolute;top:776px;left:688px;z-index:50;padding-left:25px;"><img style="padding-left:0px;padding-top:10px" src="/images/84.gif" border="0"></div>
	<? if ($id!='') { ?>
	<img src="images/uploadicon.jpg" /><span id="buttonplaceholder"></span><div id="container"><ul id="upload_list" class="ui-sortable" style="padding-top:5px;list-style-type: none;">
	<?
	   $sql='select * from tb_subdivision_images where subdivisionid='.$id.' order by ordr asc';
	   $res=mysql_query($sql);
	   while ($row=mysql_fetch_array($res,MYSQL_ASSOC))
	   {
	     echo '<li style="float:left;width:130px;height:130px;" id="img_'.$row['id'].'">';
		 echo '<img src="images/'.$row['imagename'].'_sm4.'.$row['ext'].'" border="0" />';
		 echo "</li>\n";
	     $jscript.=' $("#img_'.$row['id'].'").simpletip({ showEffect: "none",fixed: true, position: [50,-15], content: "<div><span style=\'margin-left:5px;height:16px;font-size:12px;padding-right:5px;\'>#'.$row['id'].'</span><a href=\'/editsubdivision.php?idel='.$row['id'].'&id='.$id.'\'><img border=\'0\' src=\'/images/trash.gif\' /></a></div>"});'."\n";
	   }
	?>
	</ul></div>
	<? } ?>
	</td>
  </tr>
  <tr>
    <td colspan="3"><input type="submit" name="submitedit" value="Save" class="brown_btn savebtn" />
<?
  if ($error_msg!='')
     echo '<img src="/images/failed.png" border=0><p style="display:inline;color:#000;float:none;">'.$error_msg.'</p><br><br>';
?>
	</td>
    <td colspan="2">&nbsp;</td>
  </tr>
</table>

<? include('emarentwitter.php'); ?>
</div>
<? if ($id!='') { ?>
<script type="text/javascript" src="<?echo $web_app_path;?>swfupload/swfupload.js"></script>
<script type="text/javascript" src="<?echo $web_app_path;?>js/swfupload.swfobject.js"></script>
<script type="text/javascript" src="<?echo $web_app_path;?>js/swfupload.queue.js"></script>
<script type="text/javascript" src="<?echo $web_app_path;?>js/handlers.js"></script>
<script type="text/javascript">
var swfu;
var tbpage="/editsubdivision.php";
jQuery(document).ready(function($) {
<?
  echo $jscript;
?>
});

	var settings = {
		flash_url : "<?echo $web_app_path;?>swfupload/swfupload.swf",
		upload_url: "<?echo $web_app_path;?>upload.php?sid=<? echo $id;?>",
        post_params: {"PHPSESSID": "<? echo session_id();?>"},
		file_size_limit : "100 MB",
		file_types : "*.jpg;*.png;*.gif;",
		file_types_description : "All Files",
		file_upload_limit : 0,
		file_queue_limit : 25,
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
<? } ?>
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

?>