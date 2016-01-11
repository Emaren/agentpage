<?
  include('config.php');
  define("MAPS_HOST", "maps.google.com");
  define("KEY", "ABQIAAAAKqtGN-YZ4Tub2F6E3fBBCRQRTUoqVQQGf9yQ4jm03QO5KdSH0BQcDKf8HsNq8YAWPpvAMhNhxghGWQ");

  $base_url = "http://" . MAPS_HOST . "/maps/geo?output=xml" . "&key=" . KEY;

  $id=$_GET['id'];
  if (!is_numeric($id) && $id!='')
    die('Invalid Id');
  if ($id=='')
  {
    $txt='Add ';
    $iid=0;
  }
  else
  {
    $txt='Edit ';
    $iid=$id;
  }
  $lt=$_POST['listing_typeid'];
  if ($lt=='')
    $lt=1;

  if ($_GET['idel']!='')
  {
    $sql='select * from tb_listing_images where id='.$_GET['idel'];
	$res=mysql_query($sql);
	if (mysql_num_rows($res)>0)
	{
	  $row=mysql_fetch_array($res,MYSQL_ASSOC);
	  unlink(realpath('./').'/images/'.$row['imagename'].'.'.$row['ext']);
	  unlink(realpath('./').'/images/'.$row['imagename'].'_sm1.'.$row['ext']);
	  unlink(realpath('./').'/images/'.$row['imagename'].'_sm4.'.$row['ext']);
	  $sql='delete from tb_listing_images where id='.$_GET['idel'];
	  $res=mysql_query($sql);
	  if ($res)
	    $msg='Image #'.$_GET['idel'].' removed successfully';
      else
	    $err='Failed to remove image #'.$_GET['idel'].'-'.mysql_error();
	}
  }

  $sql='select * from tb_realtor_municipality trm,tb_municipality tm where realtorid='.$_SESSION['uid'].' and tm.id=trm.municipalityid';
  $res=mysql_query($sql);
  $rw=mysql_fetch_array($res,MYSQL_ASSOC);
  if ($_POST['post42']!='')
  {
	$err='';
	$listing_price=checknumeric('listing_price','Listing price');
	$tax_amount=checknumeric('tax_amount','Tax Amount');
	$beds_up=checknumeric('beds_up','Beds up');
	$beds_down=checknumeric('beds_down','Beds down');
	$full_baths=checknumeric('full_baths','Full baths');
	$half_baths=checknumeric('half_baths','Half baths');
	$building_age=checknumeric('building_age','Building age');
	$square_footage=checknumeric('square_footage','Square footage');
	$tax_year=checknumeric('tax_year','Tax year');
	$monthly_fee=checknumeric('monthly_fee','Monthly Fee');
	$acres=checknumeric('acres','Acres');
	$address=$_POST['building_no'].' '.$_POST['street_name'].' '.$rw['name'].' '.$rw['prov'];
    $request_url = $base_url . "&q=" . urlencode($address);
    $xml = simplexml_load_file($request_url) or die("url not loading");
    $status = $xml->Response->Status->code;
    if (strcmp($status, "200") == 0)
	{
      $coordinates = $xml->Response->Placemark->Point->coordinates;
      $coordinatesSplit = split(",", $coordinates);
      $lat = $coordinatesSplit[1];
      $lng = $coordinatesSplit[0];
	}
	else
	{
	  $lat='';
	  $lng='';
	}
	if ($id=='') // insert mode
	{
	   if ($err=='')
	   {
	      $sql='select * from tb_realtor_municipality where realtorid='.$_SESSION['uid'];
		  $res=mysql_query($sql);
		  $rw=mysql_fetch_array($res,MYSQL_ASSOC);

		  $sql='insert into tb_listings (listing_no,listing_typeid,listing_price,tax_amount,tax_year,subdivision,postalcode,unit_no,building_no,street_name,ats,public_overview,lat,`long`,square_footage,garage_typeid,beds_up,beds_down';
	  	  $sql.=',building_age,full_baths,half_baths,monthly_fee,acres,realtor1id,municipalityid,house_plan,builder) values ("'.$_POST['listing_no'].'",'.$_POST['listing_typeid'].','.$listing_price.','.$tax_amount.','.$tax_year.','.$_POST['subdivision'].',"'.htmlentities($_POST['postalcode'],ENT_QUOTES);
		  $sql.='","'.htmlentities($_POST['unit_no'],ENT_QUOTES).'","'.htmlentities($_POST['building_no'],ENT_QUOTES).'","'.htmlentities($_POST['street_name'],ENT_QUOTES).'","'.htmlentities($_POST['ats'],ENT_QUOTES).'","'.htmlentities($_POST['public_overview'],ENT_QUOTES).'","'.$lat.'","'.$lng;
		  $sql.='",'.$square_footage.','.$_POST['garage_typeid'].','.$beds_up.','.$beds_down.','.$building_age.','.$full_baths.','.$half_baths.','.$monthly_fee.','.$acres.','.$_SESSION['uid'].','.$rw['municipalityid'].'","'.htmlentities($_POST['house_plan'],ENT_QUOTES).'","'.htmlentities($_POST['builder'],ENT_QUOTES).'")';
		  $res=mysql_query($sql);
		  if ($res)
		  {
		    $newid=mysql_insert_id();
		    $msg='Successfully created new listing';
			$sql='update tb_listing_images set listingid='.$newid.' where listingid=0';
			$res=mysql_query($sql);
		  }
		  else
		    $err='Failure creating new listing '.mysql_error();
	   }
	}
	else
	{
	  $sql=' update tb_listings set listing_typeid='.$_POST['listing_typeid'].', unit_no="'.htmlentities($_POST['unit_no'],ENT_QUOTES).'", lat="'.$lat.'",`long`='.$lng.', listing_no="'.$_POST['listing_no'].'", listing_price='.$listing_price.', acres='.$acres.', monthly_fee='.$monthly_fee.', tax_amount='.$tax_amount.', tax_year='.$tax_year.', subdivision='.$_POST['subdivision'].', postalcode="';
	  $sql.=htmlentities($_POST['postalcode'],ENT_QUOTES).'",building_no="'.htmlentities($_POST['building_no'],ENT_QUOTES).'", street_name="'.htmlentities($_POST['street_name'],ENT_QUOTES).'"';
	  $sql.=',public_overview="'.htmlentities($_POST['public_overview'],ENT_QUOTES).'", ats="'.htmlentities($_POST['ats'],ENT_QUOTES).'", square_footage='.$square_footage.',garage_typeid='.$_POST['garage_typeid'].',beds_up='.$beds_up;
	  $sql.=',beds_down='.$beds_down.',building_age='.$building_age.',full_baths='.$full_baths.',half_baths='.$half_baths.',house_plan="'.htmlentities($_POST['house_plan'],ENT_QUOTES).'", builder="'.htmlentities($_POST['builder'],ENT_QUOTES).'" where id='.$id;
	  $res=mysql_query($sql);
	  if ($res)
		$msg='Successfully updated listing';
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
		  $sql='update tb_listing_images set ordr='.$j.' where id='.$m;
		  $res=mysql_query($sql);
		  $j++;
		}
	 }
  }

  if ($id!='')
  {
    $sql='select * from tb_listings where id='.$id;
	$res=mysql_query($sql);
	$list=mysql_fetch_array($res,MYSQL_ASSOC);
	if (trim($list['realtor1id'])!=trim($_SESSION['mlsid']) and $_SESSION['isadmin']==0)
	  header('Location: listings.php');
	$lt=$list['listing_typeid'];
  }

  if ($err!='')
    $error_msg=$err;
  if ($msg!='' && $_GET['idel']=='')
  {
    $_SESSION['ok_msg']=$msg;
    session_write_close();
	header('Location: /listings.php');
  }
  else
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
<p><? echo $txt;?>Listing</p>
<?
  $query='';
  if ($id!='')
    $query='?id='.$id;
?>

</div>
<script type="text/javascript">

function changetype(id)
{
  if (id==1)
  {
   document.getElementById('monthly_fee').style.display='table-row-group';
   document.getElementById('mf').style.display='table-row-group';
   document.getElementById('ac').style.display='none';
   document.getElementById('acres').style.display='none';
   document.getElementById('street3').style.display='table-row-group';
   document.getElementById('street1').style.display='table-row-group';
   document.getElementById('street4').innerHTML='Street';
   document.getElementById('street2').colSpan=2;
   document.getElementById('street4').colSpan=2;
   document.getElementById('street_name').style.width="275px";
   document.getElementById('atsrow').style.display='none';
   document.getElementById('atsrow2').style.display='none';
   document.getElementById('bedsup').style.display='table-cell';
   document.getElementById('bedsup2').style.display='table-cell';
   document.getElementById('bedsdown').style.display='table-cell';
   document.getElementById('bedsdown2').style.display='table-cell';
   document.getElementById('gtid').style.display='table-cell';
   document.getElementById('gtid2').style.display='table-cell';
   document.getElementById('fullb').style.display='table-cell';
   document.getElementById('fullb2').style.display='table-cell';
   document.getElementById('halfb').style.display='table-cell';
   document.getElementById('halfb2').style.display='table-cell';

  }

  if (id==2)
  {
   document.getElementById('monthly_fee').style.display='none';
   document.getElementById('mf').style.display='none';
   document.getElementById('ac').style.display='table-row-group';
   document.getElementById('acres').style.display='table-row-group';
   document.getElementById('street3').style.display='table-row-group';
   document.getElementById('street1').style.display='table-row-group';
   document.getElementById('street4').innerHTML='Street';
   document.getElementById('street2').colSpan=2;
//   document.getElementById('street4').colSpan=2;
//   document.getElementById('street_name').style.width="275px";
   document.getElementById('atsrow').style.display='table-row';
   document.getElementById('atsrow2').style.display='table-row';
   document.getElementById('bedsup').style.display='none';
   document.getElementById('bedsup2').style.display='none';
   document.getElementById('bedsdown').style.display='none';
   document.getElementById('bedsdown2').style.display='none';
   document.getElementById('gtid').style.display='none';
   document.getElementById('gtid2').style.display='none';
   document.getElementById('fullb').style.display='none';
   document.getElementById('fullb2').style.display='none';
   document.getElementById('halfb').style.display='none';
   document.getElementById('halfb2').style.display='none';

  }

  if (id==3)
  {
   document.getElementById('monthly_fee').style.display='none';
   document.getElementById('mf').style.display='none';
//   document.getElementById('street3').style.display='none';
//   document.getElementById('street1').style.display='none';
//   document.getElementById('street2').colSpan=3;
//   document.getElementById('street4').colSpan=3;
//   document.getElementById('street4').innerHTML='Land Description (ATS)';
   document.getElementById('ac').style.display='table-row-group';
   document.getElementById('acres').style.display='table-row-group';
//   document.getElementById('street_name').style.width="400px";
   document.getElementById('atsrow').style.display='table-row';
   document.getElementById('atsrow2').style.display='table-row';
   document.getElementById('bedsup').style.display='none';
   document.getElementById('bedsup2').style.display='none';
   document.getElementById('bedsdown').style.display='none';
   document.getElementById('bedsdown2').style.display='none';
   document.getElementById('gtid').style.display='none';
   document.getElementById('gtid2').style.display='none';
   document.getElementById('fullb').style.display='none';
   document.getElementById('fullb2').style.display='none';
   document.getElementById('halfb').style.display='none';
   document.getElementById('halfb2').style.display='none';

  }

}

</script>


<form method="POST" action="<? echo $_SERVER['PHP_SELF']; echo $query; ?>">
<table class="edit_table" cellpadding="0" cellspacing="0" style="padding: 25px; clear: both; width: 100%;">
  <tr>
	<th width="160">Type</th>
    <th width="160">List Price</th>
    <th width="160">Tax Amount</th>
    <th width="160">Tax Year</th>
    <th width="270">Subdivision</th>
    <th>Postal Code</th>
  </tr>
  <tr>
    <td><select mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" class="rounded"  onchange="changetype(this.value);" style="width:125px;" name=listing_typeid><option <? if (post_or_row($list,'listing_typeid')==1) echo ' selected '; ?> value=1>Residential</option><option  <? if (post_or_row($list,'listing_typeid')==2) echo ' selected '; ?> value=2>Commercial/Industrial</option><option  <? if (post_or_row($list,'listing_typeid')==3) echo ' selected '; ?> value=3>Rural</option></select></td>
    <td><input name="listing_price" type="text" class="rounded" value="<? echo post_or_row($list,'listing_price');?>"/></td>
    <td><input name="tax_amount" type="text" class="rounded" value="<? echo post_or_row($list,'tax_amount');?>" /></td>
    <td><input name="tax_year" type="text" class="rounded" value="<? echo post_or_row($list,'tax_year');?>" /></td>
	<? $sd=post_or_row($list,'subdivision'); ?>
    <td><select mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22"  name=subdivision class="rounded">
<?
   $muni=array();
   $sql='select * from tb_realtor_municipality where realtorid='.$_SESSION['uid'];
   $result=mysql_query($sql);
   while ($row=mysql_fetch_array($result,MYSQL_ASSOC))
     $muni[]=$row['municipalityid'];
   $mlist=implode(',',$muni);
   $sql='select * from tb_municipality where county_parent in ('.$mlist.')';
   $res=mysql_query($sql);
   while ($row=mysql_fetch_array($res,MYSQL_ASSOC))
     $muni[]=$row['id'];
   $mlist=implode(',',$muni);
   $sql='select * from tb_subdivision where show_in_list="y" and municipalityid in ('.$mlist.')';
   $result=mysql_query($sql);
   while ($row=mysql_fetch_array($result,MYSQL_ASSOC))
   {
     echo '<option value='.$row['id'];
	 if ($row['id']==$sd)
	   echo ' selected ';
	 echo '>'.$row['name'].'</option>';
   }
?>
	</select><input type=hidden name=post42 value="42"></td>
    <td><input name="postalcode" value="<? echo post_or_row($list,'postalcode');?>" type="text" class="rounded" /></td>
  </tr>
  <tr>
    <th id="street5" style="display:<? if ($lt==1) echo 'table-cell'; else echo 'none';?>">Unit No</td>
    <th id="street3" style="display:<? if ($lt==1 or $lt==2) echo 'table-cell'; else echo 'none';?>">House Number</th>
    <th id="street4" colspan="<? if ($lt==1 or $lt==2) echo '2'; else echo '3';?>" style="display:<? if ($lt==1 or $lt==2) echo 'table-cell'; else echo 'none';?>">Street</th>
    <th colspan="2">Overview</th>
  </tr>
  <tr>
    <td id="street6" style="display:<? if ($lt==1) echo 'table-cell'; else echo 'none';?>"><input id="unit_no" style="display:<? if ($lt==1 or $lt==2) echo 'table-cell'; else echo 'none';?> " name="unit_no" type="text" class="rounded" value="<? echo post_or_row($list,'unit_no');?>"/></td>
    <td id="street1" style="display:<? if ($lt==1 or $lt==2) echo 'table-cell'; else echo 'none';?>"><input id="building_no" style="display:<? if ($lt==1 or $lt==2) echo 'table-cell'; else echo 'none';?> " name="building_no" type="text" class="rounded" value="<? echo post_or_row($list,'building_no');?>"/></td>
    <td id="street2" colspan="<? if ($lt==1) echo '1'; else if ($lt==2) echo '2'; else echo '3';?>"><input id="street_name" name="street_name" type="text" class="rounded" value="<? echo post_or_row($list,'street_name');?>" style="width: <? if ($lt==1) echo '125px'; else if ($lt==2) echo '275px;'; else echo '400px;';?>" /></td>
    <td colspan="2" rowspan="5"><textarea mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22"  name="public_overview" class="rounded" style="width: 395px; height:180px;"  value="<? echo post_or_row($list,'public_overview');?>"><? echo post_or_row($list,'public_overview');?></textarea></td>
  </tr>
  <tr id="atsrow" style="display:<? if ($lt==2 or $lt==3) echo 'table-row'; else echo 'none';?>">
    <th>Land Description (ATS)</th>
  </tr>
  <tr id="atsrow2" style="display:<? if ($lt==2 or $lt==3) echo 'table-row'; else echo 'none';?>">
    <td colspan="3"><input id="ats" style="width:400px;" class="rounded" name="ats" value="<? echo post_or_row($list,'ats');?>"></td>
  <tr>
    <th>Sq. Footage</th>
    <th id="gtid" style="display:<? if ($lt==1) echo 'table-cell'; else echo 'none';?>" colspan="2">Garage Type</th>
  </tr>
  <tr>
    <td><input name="square_footage" type="text" class="rounded" value="<? echo post_or_row($list,'square_footage');?>" /></td>
	<? $gtid=post_or_row($list,'garage_typeid'); ?>
    <td id="gtid2" style="display:<? if ($lt==1) echo 'table-cell'; else echo 'none';?>" colspan="2"><select mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22"   name="garage_typeid" class="rounded"><option <? if ($gtid==0) echo 'selected '; ?> value="0">None</option><option <? if ($gtid==1) echo 'selected '; ?> value="1">Attached</option><option <? if ($gtid==2) echo 'selected '; ?> value="2">Detatched</option>
	</select></td>
  </tr>
  <tr>
    <th>Building Age</th>
    <th id="bedsup" style="display:<? if ($lt==1) echo 'table-cell'; else echo 'none';?>" >Bedrooms Up</th>
    <th id="bedsdown" style="display:<? if ($lt==1) echo 'table-cell'; else echo 'none';?>" >Bedrooms Down</th>
  </tr>
  <tr>
    <td><input name="building_age" type="text" class="rounded" value="<? echo post_or_row($list,'building_age');?>" /></td>
    <td id="bedsup2" style="display:<? if ($lt==1) echo 'table-cell'; else echo 'none';?>" ><input name="beds_up" type="text" class="rounded" value="<? echo post_or_row($list,'beds_up');?>" /></td>
    <td id="bedsdown2" style="display:<? if ($lt==1) echo 'table-cell'; else echo 'none';?>" ><input name="beds_down" type="text" class="rounded" value="<? echo post_or_row($list,'beds_down');?>" /></td>
  </tr>
  <tr>
<?
  if ($lt=='1')
    echo '<th><p id="mf" style="display:table-row-group;color:#000;padding:0px;">Monthly Fee</p><p id="ac" style="display:none;color:#000;padding:0px;">Acres</p></th>';
  else
    echo '<th><p id="mf" style="display:none;color:#000;padding:0px;">Monthly Fee</p><p id="ac" style="display:table-row-group;color:#000;padding:0px;">Acres</p></td>';
?>
    <th id="fullb" style="display:<? if ($lt==1) echo 'table-cell'; else echo 'none';?>" >Full Baths</th>
    <th id="halfb" style="display:<? if ($lt==1) echo 'table-cell'; else echo 'none';?>" >Half Baths</th>
    <th colspan="2">MLS Listing #</th>
  </tr>
  <tr>
  <td>
<?
  if ($lt=='1')
  {
    echo '<input name="monthly_fee" id="monthly_fee" style="display:table-row-group" type="text" class="rounded" value="'.post_or_row($list,'monthly_fee').'">';
    echo '<input name="acres" id="acres" style="display:none" type="text" class="rounded" value="'.post_or_row($list,'acres').'">';
  }
  else
  {
    echo '<input name="acres" id="acres" type="text" style="display:table-row-group" class="rounded" value="'.post_or_row($list,'acres').'">';
    echo '<input name="monthly_fee" id="monthly_fee" style="display:none" type="text" class="rounded" value="'.post_or_row($list,'monthly_fee').'">';
  }
?>
	<td id="fullb2" style="display:<? if ($lt==1) echo 'table-cell'; else echo 'none';?>" ><input name="full_baths" type="text" class="rounded" value="<? echo post_or_row($list,'full_baths');?>" /></td>
    <td id="halfb2" style="display:<? if ($lt==1) echo 'table-cell'; else echo 'none';?>"  ><input name="half_baths" type="text" class="rounded" value="<? echo post_or_row($list,'half_baths');?>" /></td>
    <td colspan="3">
	<input name="listing_no" type="text" class="rounded" value="<? echo post_or_row($list,'listing_no');?>" />
    </td>
  </tr>
  <tr>
    <th>House plan</th>
	<th>Builder</th>
  </tr>
  <tr>
    <td><input name="house_plan" type="text" class="rounded" value="<? echo post_or_row($list,'house_plan');?>" /></td>
    <td><input name="builder" type="text" class="rounded" value="<? echo post_or_row($list,'builder');?>" /></td>	
  </tr>
  <tr>
  <th colspan="5">
  Upload Image
  </th>
  </tr>
  <tr>
  <td colspan="5">
  <input type=hidden name=neworder id=neworder>
	<div id="loading" style="display:none;height:100px;width:100px;position:absolute;top:549px;left:819px;z-index:50;padding-left:25px;"><img style="padding-left:0px;padding-top:10px" src="/images/84.gif" border="0"></div>
	<img src="images/uploadicon.jpg" /><span id="buttonplaceholder"></span><div id="container"><ul id="upload_list" class="ui-sortable" style="padding-top:5px;list-style-type: none;">
	<?
	   $sql='select * from tb_listing_images where listingid='.$id.' order by ordr asc';
	   $res=mysql_query($sql);
	   $jscript='';
	   while ($row=mysql_fetch_array($res,MYSQL_ASSOC))
	   {
	     echo '<li style="float:left;width:130px;height:130px;" id="img_'.$row['id'].'">';
		 echo '<img src="images/'.$row['imagename'].'_sm4.'.$row['ext'].'" border="0" />';
		 echo "</li>\n";
	     $jscript.=' $("#img_'.$row['id'].'").simpletip({ showEffect: "none",fixed: true, position: [50,-15], content: "<div><span style=\'margin-left:5px;height:16px;font-size:12px;padding-right:5px;\'>#'.$row['id'].'</span><a href=\'/editlisting.php?idel='.$row['id'].'&id='.$id.'\'><img border=\'0\' src=\'/images/trash.gif\' /></a></div>"});'."\n";
	   }
	?>
	</ul></div>
  </td>
  </tr>
  <tr>
    <td colspan="5"><input type="submit" name="submitedit" value="Save" class="savebtn brown_btn" />
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
<script type="text/javascript" src="<?echo $web_app_path;?>swfupload/swfupload.js"></script>
<script type="text/javascript" src="<?echo $web_app_path;?>js/swfupload.swfobject.js"></script>
<script type="text/javascript" src="<?echo $web_app_path;?>js/swfupload.queue.js"></script>
<script type="text/javascript" src="<?echo $web_app_path;?>js/handlers.js"></script>
<script type="text/javascript">
tbpage="/editlisting.php";
jQuery(document).ready(function($) {
<?
  echo $jscript;
?>
});
var swfu;

	var settings = {
		flash_url : "<?echo $web_app_path;?>swfupload/swfupload.swf",
		upload_url: "<?echo $web_app_path;?>upload.php?id=<? echo $iid;?>",
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

  function checknumeric($field,$label)
  {
    global $err;

	$rt=$_POST[$field];
	$rt=str_replace(',','',$rt);
	$rt=str_replace('$','',$rt);
	if (!is_numeric($rt) and $rt!='')
	  $err.=$label.' if present must be a numeric<br>';
	if ($rt=='')
	  $rt=0;

	return $rt;
   }
?>