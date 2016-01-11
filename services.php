<?
  include('config.php');
  if ($_SESSION['uid']=='')
    header('Location: login.php');
  if ($_SESSION['isadmin']==0)
    header('Location: login.php');

  include('template/header.php');
  $type=array('Lender','Inspector','Lawyers','Appraisers','Builders');
   // The following 3 arrays are for the sorting on the first page
   $sort_field=array('name','typeid','company');
   $sort_img=array('sort_asc.gif','sort_asc.gif','sort_asc.gif');
   $sort_dir=array('asc','asc','asc');

   $sort='';
   $dir='';

   if ($_GET['ddel']!='')
   {
     $sql='delete from tb_services where id='.$_GET['ddel'];
	 $res=mysql_query($sql);
	 if ($res)
	   $ok_msg='User successfully removed ';
	 else
	   $error_msg='Error deleting service '.mysql_error();
   }

   if ($_GET['sort']=='')
   {
     $_GET['sort']=1;
	 $_GET['dir']='name';
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

  $sql='select * from tb_services ';
  if ($sort<>'')
   $sql.=" order by $sort_field[$sort] $sort_dir[$sort]";
  $res=mysql_query($sql);

  $cnt=mysql_num_rows($res);
  if ($cnt=='')
    $cnt=0;
?>
<div style="width: 135px; float:left;">
<a href="/editservice.php" style="float:left;margin-top:20px;" class="brown_btn addrealtor">Add Service</a>

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
<p></p>
</div>
<table class="table_lists" cellpadding="0" cellspacing="0" style="padding: 20px; width: 100%; min-width: 590px;">
<tr>
<th>&nbsp;</th>
<th><a href='<? echo $web_app_path; ?>services.php?sort=0&dir=<? echo $sort_dir[0]?>'>Name&nbsp;<img src=<? echo $web_app_path.'images/'.$sort_img[0].' border=0>'; ?></a></th>
<th><a href='<? echo $web_app_path; ?>services.php?sort=1&dir=<? echo $sort_dir[1]?>'>Type&nbsp;<img src=<? echo $web_app_path.'images/'.$sort_img[1].' border=0>'; ?></a></th>
<th><a href='<? echo $web_app_path; ?>services.php?sort=2&dir=<? echo $sort_dir[2]?>'>Company&nbsp;<img src=<? echo $web_app_path.'images/'.$sort_img[2].' border=0>'; ?></a></th>
</tr>

<?
   while ($row=mysql_fetch_array($res,MYSQL_ASSOC))
   {
     if ($row['personimage']!='')
	    $imagename=$row['personimage'];
	  else
	    $imagename='thumb.jpg';
	  echo '<tr><td><img src="/images/'.$imagename.'" width="50" /></td>';
      echo '<td>'.$row['name'].'</td><td>'.$type[$row['typeid']-1].'</td><td>'.$row['company'].'</td><td><a class="default_link" href='.$web_app_path.'editservice.php?id='.$row['id'];
	  echo '>Edit</a></td><td><a class="default_link del_link" href="/services.php?ddel='.$row['id'].'" onclick="return confirm(\'Are you sure you want to delete this service?\')">';
	  if ($row['isadmin']==0)
	     echo '<img width="30" src="images/failed.png" />';
	  echo '</a></td></tr>';
   }
?>

</table>
<? include ('emarentwitter.php'); ?>

</div>

</body>
</html>
