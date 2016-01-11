<?
  include('config.php');
  if ($_SESSION['uid']=='')
    header('Location: rd_login.php');
  if ($_SESSION['isadmin']==0)
    header('Location: rd_login.php');
	
  include('template/rd_header.php');

   // The following 3 arrays are for the sorting on the first page
   $sort_field=array('mname','sname','no_of_homes');
   $sort_img=array('sort_asc.gif','sort_asc.gif','sort_asc.gif');
   $sort_dir=array('asc','asc','asc');
   
   $sort='';
   $dir='';
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
     
	 
  $sql='select *,tbm.name as mname,tbs.id as sid,tbs.name as sname from tb_subdivision tbs,tb_municipality tbm where tbs.municipalityid=tbm.id ';
  if ($sort<>'')
    $sql.=" order by $sort_field[$sort] $sort_dir[$sort]";	
  $res=mysql_query($sql);
?>
<div class="backend_wrap">
<div style="width: 135px; float:left;">

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
<p>My Listings</p>
</div>
<table class="table_lists" cellpadding="0" cellspacing="0" style="padding: 20px; width: 100%; min-width: 590px;">
<tr>
<th><a href='<? echo $web_app_path; ?>rd_subdivisions.php?sort=0&dir=<? echo $sort_dir[0]?>'>Municipality&nbsp;<img src=<? echo $web_app_path.'images/'.$sort_img[0].' border=0>'; ?></a></th>
<th><a href='<? echo $web_app_path; ?>rd_subdivisions.php?sort=1&dir=<? echo $sort_dir[1]?>'>Name&nbsp;<img src=<? echo $web_app_path.'images/'.$sort_img[1].' border=0>'; ?></a></td>
<th><a href='<? echo $web_app_path; ?>rd_subdivisions.php?sort=2&dir=<? echo $sort_dir[2]?>'>No of Homes&nbsp;<img src=<? echo $web_app_path.'images/'.$sort_img[2].' border=0>'; ?></a></th>
</tr>

<?
   while ($row=mysql_fetch_array($res,MYSQL_ASSOC))
      echo '<tr><td>'.$row['mname'].'</td><td>'.$row['sname'].'</td><td>'.$row['no_of_homes'].'</td><td><a href='.$web_app_path.'rd_editsubdivision.php?id='.$row['sid'].'>Edit</a></td></tr>';
?>

</table>

<? include ('emarentwitter.php'); ?>

</div>

</body>
</html>
