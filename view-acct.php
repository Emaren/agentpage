<?
  include('config.php');
  if ($_SESSION['uid']=='')
    header('Location: login.php');
  if ($_SESSION['realtor']=='n' && $_SESSION['isadmin']==0)
    header('Location: login.php');

   // The following 3 arrays are for the sorting on the first page
   $sort_field=array('building_no,street_name','listing_no','sname,listing_price','building_age','listing_price','mname');
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
  include('template/header.php');
  unset($_SESSION['error_msg']);
  unset($_SESSION['ok_msg']);

  $sql='select *,tbm.name as mname,tbs.name as sname,tbl.id as lid from tb_listings tbl,tb_municipality tbm,tb_subdivision tbs';
  if ($_SESSION['isadmin']==0 && $_SESSION['realtor']=='y')
    $sql.=',tb_users ';
  $sql.=' where tbl.municipalityid=tbm.id and tbs.id=tbl.subdivision ';
  if ($_SESSION['isadmin']==0 && $_SESSION['realtor']=='y')
    $sql.=' and (realtor1id=tb_users.mlsid or realtor2id=tb_users.mlsid) and tb_users.id='.$_SESSION['uid'];
  if ($sort<>'')
    $sql.=" order by $sort_field[$sort] $sort_dir[$sort]";
  $res=mysql_query($sql);
  $cnt=mysql_num_rows($res);
  if ($cnt=='')
    $cnt=0;
?>

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

<div class="wrapper" style="background: none;">
<div class="pageheader">
<p style="font-style: normal; font-size: 28px; padding: 6px 20px; font-family: Georgia, 'Times New Roman', Times, serif;">Account</p>

</div>
<table class="table_lists" cellpadding="0" cellspacing="0" style="padding: 20px; width: 100%; min-width: 590px;">

<tr>
<td align="left">
<? if (stripos($_SERVER['HTTP_REFERER'],'paypal')) { ?>
Your payment has been recieved and the transaction is now complete. Your transaction details will be sent to you by e-mail.
<? } ?>
</td>
</tr>

<tr>
<td style="border-bottom: none; height: 200px; vertical-align: bottom;"><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_subscr-find&alias=<? echo $_SESSION['email'];?>" style="float:left;margin-top:0px;" class="brown_btn2">Cancel Subscription</a></td>
</tr>

</table>
<? include('emarentwitter.php'); ?>
</div>

</body>
</html>
<?

function dateDiff($start, $end)
{
  $start_ts = strtotime($start);
  $end_ts = strtotime($end);
  $diff = $end_ts - $start_ts;
  return round($diff / 86400);
}

?>