<?
  include('config.php');
  include('template/header.php');

  $id=$_GET['id'];
  if ($id=='')
    $txt='Add ';
  else
    $txt='Edit ';
  
  if ($_POST['submitedit']!='')
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
	
	if ($id=='') // insert mode
	{	  
	   if ($err=='')
	   {
		  $sql='insert into tb_listings (listing_price,tax_amount,tax_year,subdivision,postalcode,building_no,street_name,public_overview,square_footage,garage_typeid,beds_up,beds_down';
	  	  $sql.=',building_age,full_baths,half_baths,realtor1id) values ('.$listing_price.','.$tax_amount.','.$tax_year.','.$_POST['subdivision'].',"'.htmlentities($_POST['postalcode'],ENT_QUOTES);
		  $sql.='","'.htmlentities($_POST['building_no'],ENT_QUOTES).'","'.htmlentities($_POST['street_name'],ENT_QUOTES).'","'.htmlentities($_POST['public_overview'],ENT_QUOTES);
		  $sql.='",'.$square_footage.','.$_POST['garage_typeid'].','.$beds_up.','.$beds_down.','.$building_age.','.$full_baths.','.$half_baths.','.$_SESSION['uid'].')';
		  echo $sql;
		  $res=mysql_query($sql);
		  if ($res)
		    $msg='Successfully created new listing';
		  else
		    $err='Failure creating new listing '.mysql_error();  
	   }		  			
	}
	else
	{
	
	
	
	}
  }
  
  if ($id!='')
  {
    $sql='select * from tb_listings where id='.$id;
	$res=mysql_query($sql);
	$list=mysql_fetch_array($res,MYSQL_ASSOC);
  }
  
?>
<div class="wrapper" style="margin: 20px auto; float:none;">

<div class="pageheader">
<p><? echo $txt;?>Realtor Information</p>
</div>

<form method="POST" action="<? echo $_SERVER['PHP_SELF'];?>">
<table class="edit_table" cellpadding="0" cellspacing="0" style="margin: 25px; width: 100%;">
  <tr>
    <th width="160">List Price</th>
    <th width="160">Tax Amount</th>
    <th width="160">Tax Year</th>
    <th width="270">Subdivision</th>
  </tr>
  <tr>
    <td><input name="listing_price" type="text" class="rounded" value="<? echo post_or_row($list,'listing_price');?>"/></td>
    <td><input name="tax_amount" type="text" class="rounded" value="<? echo post_or_row($list,'tax_amount');?>" /></td>
    <td><input name="tax_year" type="text" class="rounded" value="<? echo post_or_row($list,'tax_year');?>" /></td>
	<? $sd=post_or_row($list,'subdivision'); ?>
    <td><select name="subdivision" class="rounded"><option <? if ($sd==1) echo 'checked '; ?> value="1">Grande Prairie</option></select></td>
  </tr>
  <tr>
    <th>Postal Code</th>  
    <th colspan="2">House Number</th>
    <th colspan="2">Overview</th>
  </tr>
  <tr>
    <td><input name="postalcode" type="text" class="rounded" value="<? echo post_or_row($list,'postalcode');?>" /></td>  
    <td colspan="2"><input name="building_no" type="text" class="rounded" value="<? echo post_or_row($list,'building_no');?>" /></td>
    <td colspan="2" rowspan="5"><textarea name="public_overview" class="rounded" style="width: 395px; height:180px;"  value="<? echo post_or_row($list,'public_overview');?>"></textarea></td>
  </tr>
  <tr>
    <th>Street</th>
    <th>Sq. Footage</th>
  </tr>
  <tr>
    <td><input name="street_name" type="text" class="rounded" value="<? echo post_or_row($list,'street_name');?>" /></td>
    <td><input name="square_footage" type="text" class="rounded" value="<? echo post_or_row($list,'square_footage');?>" /></td>
	<? $gtid=post_or_row($list,'garage_typeid'); ?>
  </tr>
  <tr>
    <th colspan="2">Garage Type</th>  
    <th>Bedrooms Up</th>
    <th>Bedrooms Down</th>
  </tr>
  <tr>
    <td colspan="2"><select name="garage_typeid" class="rounded"><option <? if ($gtid==1) echo 'checked '; ?> value="1">Attached</option><option <? if ($gtid==2) echo 'checked '; ?> value="2">Detatched</option>
	<option <? if ($gtid==3) echo 'checked '; ?> value="3">Single</option><option <? if ($gtid==4) echo 'checked '; ?> value="2">Double</option>
	<option <? if ($gtid==5) echo 'checked '; ?> value="1">1.5</option>	
	</select></td>
    <td><input name="beds_up" type="text" class="rounded" value="<? echo post_or_row($list,'beds_up');?>" /></td>
    <td><input name="beds_down" type="text" class="rounded" value="<? echo post_or_row($list,'beds_down');?>" /></td>
  </tr>
  <tr>
    <th>Building Age</th>  
    <th>Full Baths</th>
    <th>Half Baths</th>
    <th colspan="2">Upload Image</th>
  </tr>
  <tr>
    <td><input name="building_age" type="text" class="rounded" value="<? echo post_or_row($list,'building_age');?>" /></td>
    <td><input name="full_baths" type="text" class="rounded" value="<? echo post_or_row($list,'full_baths');?>" /></td>
    <td><input name="half_baths" type="text" class="rounded" value="<? echo post_or_row($list,'half_baths');?>" /></td>
    <td colspan="2"><img src="images/uploadicon.jpg" /></td>
  </tr>
  <tr>
    <td colspan="3"><input type="submit" name="submitedit" value=" " class="savebtn" /></td>
    <td colspan="2">&nbsp;</td>
  </tr>
</table>

</form>

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
  
  function checknumeric($field,$label)
  {
    global $err;
	
	$rt=$_POST[$field];
	if (!is_numeric($rt) and $rt!='')
	  $err.=$label.' if present must be a numeric<br>';
	if ($rt=='')
	  $rt=0;
	
	return $rt;
   }
?>