<?
  include_once('config.php');
  $xml='';
  $id=$_GET['id'];

  $sql='select *,tm.name as mname,ts.name as sname from tb_listings tl,tb_subdivision ts,tb_municipality tm where tl.id='.$id.' and tl.subdivision=ts.id and tm.id=ts.municipalityid';
  $result=mysql_query($sql);
  if (mysql_num_rows($result)>0)
  {
	$row=mysql_fetch_array($result,MYSQL_ASSOC);
	$beds=$row['beds_up']+$row['beds_down'];
	$baths=$row['full_baths']+$row['half_baths'];
	$_SESSION['sqlindex']='id: '.$id;
	if ($row['garage_typeid']==0)
	  $garage='No';
	else
	if ($row['garage_typeid']==1)
	  $garage='Yes, Attached';
	else
	  $garage='Yes, Detached';
	if ($row['tax_amount']>0)
	  $taxes='$'.number_format($row['tax_amount'],2,'.',',').' ('.$row['tax_year'].')';
	$mname=$row['mname'];
	if (strpos($mname,'County'))
	  $mname=$row['sname'];
	$addr=$row['unit_no'].' '.$row['building_no'].' '.$row['street_name'].'\n'.$mname.' '.$row['prov'].'\n'.$row['postalcode'];
    $xml='<?xml version="1.0" encoding="ISO-8859-15"?>';
    $xml.='<listing>';
    $xml.="<info price='$".number_format($row['listing_price'],0,'.',',')."' address='".$addr."' subdivision='".htmlentities($row['sname'],ENT_QUOTES)."' desc='".$row['public_overview']."' beds='".$beds."' baths='".$baths."'";
	$xml.=" size='".$row['square_footage']." sqft' mls='".$row['listing_no']."' age='".$row['building_age']." years' garage='".$garage."' taxes='".$taxes."' lat='".$row['lat']."' long='".$row['long']."'";
    $sql='select * from tb_users where id="'.$_SESSION['uid'].'"';
	$res=mysql_query($sql);
	$r=mysql_fetch_array($res,MYSQL_ASSOC);
	if ($r['realtor']=='y')
	  mysql_data_seek($res,0);// we want the realtor to see his own info refetch the same record
	else
	{
      $sql='select * from tb_users where id="'.$r['realtorid'].'"';
	  $res=mysql_query($sql);
    }

	if (mysql_num_rows($res)>0)
	{
	  $rw=mysql_fetch_array($res,MYSQL_ASSOC);
	  $xml.=" realtorname='".$rw['firstname'].' '.$rw['lastname']."' realtoremail='".$rw['email']."' personal_website='".$rw['website']."' brokerage_website='".$rw['brokerage_website']."'";
  	  $sql='select * from tb_client_images where clientid='.$rw['id'];
	  $res2=mysql_query($sql);
	  if (mysql_num_rows($res2)>0)
	  {
	    $r=mysql_fetch_array($res2,MYSQL_ASSOC);
	    $xml.=" realtorimage='/images/".$r['imagename'].'.'.$r['ext']."'";
	  }
	  else
	    $xml.=" realtorimage='/images/noman.jpg'";
	}
	if ($rw['brokerage_image']!='')
      $xml.=" brokerage_image='/images/".$rw['brokerage_image']."' ";
    else
      $xml.=" brokerage_image='/images/blank.gif'";
    $sql='select * from tb_other_realtors where realtorid=lower("'.$row['realtor1id'].'")';
	$res=mysql_query($sql);
    $r=mysql_fetch_array($res,MYSQL_ASSOC);
    $xml.=" brokerage_name='".$r['office']."'";
	$xml.="></info>";
	$sql='select * from tb_listing_images where listingid='.$id.' order by ordr';
	$result=mysql_query($sql);
	if (mysql_num_rows($result)>0)
	{
	  while ($row=mysql_fetch_array($result,MYSQL_ASSOC))
	   $xml.='<gallery img="'.$row['imagename'].'_sm1.'.$row['ext'].'"></gallery>';
	}
	$xml.='</listing>';
    header('Content-Type: text/xml');
    echo $xml;
  }
  else
  {
    $xml='<?xml version="1.0" encoding="ISO-8859-15"?>';
    $xml.='<appt>';
    $xml.='<loggedin id="0">nope</loggedin>';
    $xml.='</appt>';
    header('Content-Type: text/xml');
    echo $xml;
  }
?>

