<?
  include('config.php');
  if ($_SESSION['uid']=='')
    header('Location: login.php');
  if ($_SESSION['realtor']=='n' && $_SESSION['isadmin']==0)
    header('Location: login.php');	

   // The following 3 arrays are for the sorting on the first page
   $sort_field=array('building_no,street_name','postalcode','sname','building_age','listing_price','mname');
   $sort_img=array('sort_asc.gif','sort_asc.gif','sort_asc.gif','sort_asc.gif','sort_asc.gif','sort_asc.gif');
   $sort_dir=array('asc','asc','asc','asc','asc','asc');
   
   $sort='';
   $dir='';
   // check if the user is trying to sort the fields on the dashboard
   if ($_GET['sort']=='')
   {
     $_GET['sort']=4;
	 $_GET['dir']='asc'; 
   }
   
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

  if ($_GET['did']!='')
  {
    $sql='select * from tb_listings where id='.$_GET['did'];
	$res=mysql_query($sql);
	if ($res)
	{
	  $sql='select * from tb_listings images where listingid='.$_GET['did'];
	  $res=mysql_querY($sql);
  	  if (mysql_num_rows($res)>0)
	  {
	    while ($row=mysql_fetch_array($res,MYSQL_ASSOC))
		{
	      unlink(realpath('./').'/images/'.$row['imagename'].'.'.$row['ext']);
	      unlink(realpath('./').'/images/'.$row['imagename'].'_sm1.'.$row['ext']);	  
	      unlink(realpath('./').'/images/'.$row['imagename'].'_sm4.'.$row['ext']);	  	  
	      $sql2='delete from tb_listing_images where id='.$row['id'];
	      $res2=mysql_query($sql);
	    }
	  }
	  $sql='delete from tb_user_listings where listingid='.$_GET['did'];
	  $res=mysql_query($sql);
	  $sql='delete from tb_listings where id='.$_GET['did'];
	  $res=mysql_query($sql);
	  $msg='Success deleted listing No '.$_GET['did'];
	}
	else
	  $err='Listing No. '.$_GET['did'].' not found'; 
  }   

  if ($err!='')
    $_SESSION['error_msg']=$err;
  if ($msg!='')
    $_SESSION['ok_msg']=$msg;
  include('template/rd_header.php');
  unset($_SESSION['error_msg']);
  unset($_SESSION['ok_msg']);
  
  $sql='select *,tbm.name as mname,tbs.name as sname,tbl.id as lid from tb_listings tbl,tb_municipality tbm,tb_subdivision tbs where tbl.municipalityid=tbm.id and tbs.id=tbl.subdivision';
  if ($_SESSION['isadmin']==0 && $_SESSION['realtor']=='y')
    $sql.=' and (realtor1id='.$_SESSION['uid'].' or realtor2id='.$_SESSION['uid'].')';
  if ($sort<>'')
    $sql.=" order by $sort_field[$sort] $sort_dir[$sort]";	
  $res=mysql_query($sql);
  $cnt=mysql_num_rows($res);
  if ($cnt=='')
    $cnt=0;
?>
<div class="backend_wrap">
<div style="width: 135px; float:left;">
<a href="/editlisting.php" style="float:left;margin-top:20px;" class="brown_btn addlisting">Add Listing</a>

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

<div class="wrapper" style="background: none;">
<div class="pageheader">
<p>My Listings, <? echo $cnt; ?></p>
</div>
<table class="table_lists" cellpadding="0" cellspacing="0" style="padding: 20px; width: 100%; min-width: 590px;">
<tr>
<th>&nbsp;</th>
<th><a href='<? echo $web_app_path; ?>listings.php?sort=0&dir=<? echo $sort_dir[0]?>'>Address&nbsp;<img src=<? echo $web_app_path.'images/'.$sort_img[0].' border=0>'; ?></a></th>
<!--<th><a href='<? echo $web_app_path; ?>listings.php?sort=1&dir=<? echo $sort_dir[1]?>'>Postal Code&nbsp;<img src=<? echo $web_app_path.'images/'.$sort_img[1].' border=0>'; ?></a></th>-->
<th><a href='<? echo $web_app_path; ?>listings.php?sort=2&dir=<? echo $sort_dir[2]?>'>Subdivision&nbsp;<img src=<? echo $web_app_path.'images/'.$sort_img[2].' border=0>'; ?></a></th>
<th><a href='<? echo $web_app_path; ?>listings.php?sort=3&dir=<? echo $sort_dir[3]?>'>Age&nbsp;<img src=<? echo $web_app_path.'images/'.$sort_img[3].' border=0>'; ?></a></th>
<th><a href='<? echo $web_app_path; ?>listings.php?sort=4&dir=<? echo $sort_dir[4]?>'>Price&nbsp;<img src=<? echo $web_app_path.'images/'.$sort_img[4].' border=0>'; ?></a></th>
<th><a href='<? echo $web_app_path; ?>listings.php?sort=5&dir=<? echo $sort_dir[5]?>'>Municipality&nbsp;<img src=<? echo $web_app_path.'images/'.$sort_img[5].' border=0>'; ?></a></th>
</tr>

<?
   while ($row=mysql_fetch_array($res,MYSQL_ASSOC))
   {
      echo '<tr>';
	  $sql='select * from tb_listing_images where ordr=0 and listingid='.$row['lid'];
	  $result=mysql_query($sql);
	  if (mysql_num_rows($result)>0)
      {
  	    $rw=mysql_fetch_array($result,MYSQL_ASSOC);
	    $imagename=$rw['imagename'].'.'.$rw['ext'];
      }
	  else
	    $imagename='thumb.jpg';	  
	  echo '<td><img src="/images/'.$imagename.'" width="50" /></td>';
	  echo '<td>'.$row['building_no'].' '.$row['street_name'].'</td><?php /*?><td>'.$row['postalcode'].'</td><?php */?><td>'.$row['sname'].'</td><td>'.$row['building_age'].'</td><td> $'.number_format($row['listing_price'],2,'.',',');
	  echo '<td>'.$row['mname'].'</td>';
	  echo '<td><a href='.$web_app_path.'editlisting.php?id='.$row['lid'].'>Edit</a></td>';
	  echo '<td> <a href="'.$web_app_path.'listings.php?did='.$row['lid'].'" onclick="return confirm(\'Are you sure you want to delete this listing?\')">Delete Listing</a></tr>';
   }
?>

</table>
<? include ('emarentwitter.php'); ?>
</div>

</body>
</html>
