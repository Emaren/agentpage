<?
  include('config.php');
  if ($_SESSION['uid']=='')
    header('Location: login.php');
  if ($_SESSION['realtor']=='n' && $_SESSION['isadmin']==0)
    header('Location: login.php');

  include('template/rd_header.php');

   // The following 3 arrays are for the sorting on the first page
   $sort_field=array('firstname','lastname','phone','email');
   $sort_img=array('sort_asc.gif','sort_asc.gif','sort_asc.gif','sort_asc.gif');
   $sort_dir=array('asc','asc','asc','asc');

   $sort='';
   $dir='';

   if ($_GET['ddel']!='')
   {
     $sql='delete from tb_users where id='.$_GET['ddel'];
	 $res=mysql_query($sql);
	 if ($res)
	   $ok_msg='User successfully removed ';
	 else
	   $error_msg='Error deleting user '.mysql_error();
   }

   if ($_GET['sort']=='')
   {
     $_GET['sort']=1;
	 $_GET['dir']='desc';
   }

   // check if the user is trying to sort the fields on the dashboard
   if (isset($_GET['sort']))
   {
      $sort=$_GET['sort'];
      $dir=$_GET['dir'];       // dir is always set if sort if set
      if ($dir=='asc')
      {
        $sort_dir[$sort]='desc';
        $sort_img[$sort]='sort_desc.gif';
      }
      else
      {
        $sort_dir[$sort]='asc';
        $sort_img[$sort]='sort_asc.gif';
      }
   }

  $sql='select * from tb_users ';
  if ($_SESSION['isadmin']==0 && $_SESSION['realtor']=='y')
    $sql.=' where (realtorid='.$_SESSION['uid'].' or id='.$_SESSION['uid'].')';
  if ($_SESSION['isadmin']!=0)
    $sql.=' where realtorid is null';
  if ($sort<>'')
   $sql.=" order by cdate asc,$sort_field[$sort] $sort_dir[$sort]";
  else
   $sql.=' order by cdate asc ';
  $res=mysql_query($sql);

  $cnt=mysql_num_rows($res);
  if ($cnt=='')
    $cnt=0;
?>
<div class="backend_wrap">
<div style="width: 135px; float:left; padding-top: 20px;">
<a href="/rd_edituser.php" style="float:left;" <? if ($_SESSION['realtor']=='y') echo ' class="brown_btn addclient"'; else echo ' class="brown_btn addrealtor"'; ?> ><? if ($_SESSION['realtor']=='y') echo 'Add Client'; else echo 'Add Realtor'; ?></a>
<a style="float:left;margin-top:0px;max-width: 133px; display: block; min-width: 133px;" href="#" class="brown_btn viewaccount" >View Traffic</a>

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
<div class="wrapper" style="background: none;">
<div class="pageheader">
<p>My <? if ($_SESSION['realtor']=='y') echo 'Clients'; else echo 'Realtors'; ?>, <? echo $cnt; ?>
<? if ($_SESSION['realtor']=='y') { ?>
<p style="float:right;">Page Popularity, 3<span class="superscript">rd</span></p>
<? } ?>
</div>
<table class="table_lists" cellpadding="0" cellspacing="0" style="padding: 20px; width: 100%; min-width: 590px;">
<tr>
<th>&nbsp;</th>
<th><a href='<? echo $web_app_path; ?>rd_users.php?sort=0&dir=<? echo $sort_dir[0]?>'>First Name&nbsp;<img src=<? echo $web_app_path.'images/'.$sort_img[0].' border=0>'; ?></a></th>
<th><a href='<? echo $web_app_path; ?>rd_users.php?sort=1&dir=<? echo $sort_dir[1]?>'>Last Name&nbsp;<img src=<? echo $web_app_path.'images/'.$sort_img[1].' border=0>'; ?></a></th>
<th colspan="3"><a href='<? echo $web_app_path; ?>rd_users.php?sort=3&dir=<? echo $sort_dir[3]?>'>Email&nbsp;<img src=<? echo $web_app_path.'images/'.$sort_img[3].' border=0>'; ?></a></th>
</tr>

<?
   while ($row=mysql_fetch_array($res,MYSQL_ASSOC))
   {
      echo '<tr>';
	  $sql='select * from tb_client_images where clientid='.$row['id'];
	  $result=mysql_query($sql);
	  if (mysql_num_rows($result)>0)
      {
  	    $rw=mysql_fetch_array($result,MYSQL_ASSOC);
	    $imagename=$rw['imagename'].'.'.$rw['ext'];
      }
	  else
	    $imagename='noman.jpg';
	  echo '<td><img src="/images/'.$imagename.'" width="50" /></td>';
      echo '<td>'.$row['firstname'].'</td><td>'.$row['lastname'].'</td><td>'.$row['email'].'</td><td><a class="default_link" href='.$web_app_path.'rd_edituser.php?id='.$row['id'];
	  echo '>Edit</a></td><td><a class="default_link del_link" href="/rd_users.php?ddel='.$row['id'].'" onclick="return confirm(\'Are you sure you want to delete this user?\')">';
	  if ($row['isadmin']==0)
	     echo '<img width="30" src="images/failed.png" />';
	  echo '</a></td></tr>';
   }
?>

</table>
<? include ('emarentwitter.php'); ?>

</div>
<div style="clear: both;">&nbsp;</div>
</div>

</body>
</html>
