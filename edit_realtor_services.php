<?php
include('config.php');
$type=array('','Lender','Inspector','Lawyers','Appraisers','Builders');
if ($_SESSION['uid']=='')
  header('Location: /login.php');

if ($_GET['ddel']!='')
{
  $id=$_GET['ddel'];
  $sql='delete from tb_realtor_services where userid='.$_SESSION['uid'].' and id='.$id;
  $res=mysql_query($sql);
}

if ($_POST['submt']!='')
{
  $sql='delete from tb_realtor_services where userid='.$_SESSION['uid'];
  $res=mysql_query($sql);
  foreach ($_POST['active'] as $k=>$v)
  {
    if ($v=='y')
    {
      $sql='insert into tb_realtor_services (userid,serviceid) values ('.$_SESSION['uid'].','.$k.')';
      $res=mysql_query($sql);
    }
  }
}

include('template/header.php');

?>

<div class="backend_wrap">
<div style="width: 135px; float:left;">
<form method="POST" action="<? echo $_SERVER['PHP_SELF'];?>">

<input type=submit value="Add Service" name="submt" class="brown_btn addclient" style="padding: 8px 16px; margin-top: 20px;">
<a href="/view-acct.php" style="float:left;margin-top:0px;" class="brown_btn viewaccount">View Account</a>
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
<p>Services</p>
</div>

<table class="table_lists2" id="edit_services" cellpadding="0" cellspacing="0" style="padding: 20px; width: 100%; min-width: 590px;clear:both;">
<tr>
<td>
<?
  $sql='select *,tb_realtor_services.id as tid ,tb_services.id as tid2 from tb_services left join tb_realtor_services on tb_services.id=serviceid and userid='.$_SESSION['uid'].' order by typeid,company,name';
  $res=mysql_query($sql);
  while ($row=mysql_fetch_array($res,MYSQL_ASSOC))
  {
     if ($row['personimage']!='')
	    $imagename=$row['personimage'];
	  else
	    $imagename='noman.jpg';

		if ($row['companylogo']!='')
	    $imagename2=$row['companylogo'];
	  else
	    $imagename2='noman.jpg';
	  $company_name=$row['company'];
	  $act="y";
	  if ($row['userid']!='')
	    echo '<table id="row'.$row['tid2'].'" onclick="toggleActive('.$row['tid2'].');" class="newlist" width="100%">';
	  else
	  {
	    echo '<table id="row'.$row['tid2'].'" onclick="toggleActive('.$row['tid2'].');" width="100%" >';
	    $act='n';
	  }
	  echo '<tr>';
	  echo '<td width="200"><img src="/images/'.$imagename.'" width="50" /></td>';
	  echo '<td width="300">'.$row['name'].'</td>';
	  echo '<td width="200"><img src="/images/'.$imagename2.'" width="50" /></td>';
	  echo '<td width="530">'.$company_name.'</td>';
      echo '<td>'.$type[$row['typeid']].'<input type="hidden" id="active'.$row['tid2'].'" name=active['.$row['tid2'].'] value="'.$act.'"></td>';
	  //echo '<td><a class="default_link del_link" href="/edit_realtor_services.php?ddel='.$row['tid'].'" onclick="return confirm(\'Are you sure you want to delete this user?\')">Delete</a></td>';
	  echo '</tr>';
	  echo '</table>';
  }
?>
</td>
</tr>
</table></form>
<script type="text/javascript">

function toggleActive(id)
{
  temp='active'+id;
  if (document.getElementById(temp).value=='y')
  {
    document.getElementById(temp).value='n';
    document.getElementById('row'+id).className='';
  }
  else
  {
    document.getElementById(temp).value='y';
    document.getElementById('row'+id).className='newlist';
  }
}
</script>

<? include('emarentwitter.php'); ?>
</div>
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