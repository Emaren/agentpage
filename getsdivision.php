<?
  include_once('config.php');
  $xml='';
  $id=$_GET['id'];

  $sql='select * from tb_subdivision where id='.$id;
  $result=mysql_query($sql);
  if (mysql_num_rows($result)>0)
  {
    $sdivision=mysql_fetch_array($result,MYSQL_ASSOC);
	$sql='select min(listing_price),max(listing_price), min(tax_amount),max(tax_amount),min(building_age),max(building_age),count(*) as cn from tb_listings where subdivision='.$id;
	$res=mysql_query($sql);
	$minmax=mysql_fetch_array($res,MYSQL_NUM);

    $xml='<?xml version="1.0" encoding="ISO-8859-15"?>';
    $xml.='<subdivision>';
    $xml.="<info convenant='".$sdivision['convenant']."' nohomes='".$minmax[6]." Listings' nhomes='".$sdivision['no_of_homes']."' tpe='".$sdivision['type']."' min_price='$".number_format($minmax[0],2,'.',',')."' name='".str_replace("'","&#39;",$sdivision['name'])."' amenities='";
	$xml.=str_replace("'","&#39;",$sdivision['amenities']."\n".$sdivision['residences'])."' max_price='$".number_format($minmax[1],2,'.',',')."' type='".$sdivision['type']."' min_tax='$".number_format($minmax[2],2,'.',',')."' max_tax='$".number_format($minmax[3],2,'.',',')."' min_age='".number_format($minmax[4],0,'.',',')."' max_age='";
	$xml.=number_format($minmax[5],0,'.',',')."'>".str_replace("'","&#39;",$sdivision['desc'])."</info>";
 	$sql='select *  from tb_listings where subdivision='.$id.' order by listing_typeid desc, listing_price asc';
    $result=mysql_query($sql);
 	$sql='select *  from tb_listings where subdivision='.$id.' order by listing_typeid asc, listing_price desc';
	$_SESSION['listsql']=$sql;
	$_SESSION['sqlindex']=1;
    if ($result and mysql_num_rows($result)>0)
    {
	  while ($row=mysql_fetch_array($result,MYSQL_ASSOC))
	  {
	    $today=date('Y-m-d');
	    $diff=dateDiff($row['cdate'],$today);
	    $img='';
	    $sql='select * from tb_listing_images where listingid='.$row['id'].' order by ordr asc';
		$res=mysql_query($sql);
		if (mysql_num_rows($res)>0)
		{
		  $rw=mysql_fetch_array($res,MYSQL_ASSOC);
		  $img=$rw['imagename'].'_sm4.'.$rw['ext'];
		}
		else
		  $img='thumb.jpg';
	    $total_beds=$row['beds_up']+$row['beds_down'];
	    $total_baths=$row['full_baths']+$row['half_baths'];
	    $xml.="<listing id='".$row['id']."' daysold='".$diff."' acres='".number_format($row['acres'],2)."' sq='".$row['square_footage']."' listing_type='".$row['listing_typeid']."' img='".$img."' price='$".number_format($row['listing_price'],0,'.',',')."' age='".$row['building_age']."' no_beds='".$total_beds."' no_baths='".$total_baths."'>".$row['building_no'].' '.$row['street_name']."</listing>";
	  }
	}
	$sql='select * from tb_subdivision_images where subdivisionid='.$id.' order by ordr';
	$res=mysql_query($sql);
	if (mysql_num_rows($res)>0)
	{
	  while ($srow=mysql_fetch_array($res,MYSQL_ASSOC))
	    $xml.="<simages src='".htmlentities($srow['imagename'],ENT_QUOTES)."_sm1.jpg'></simages>";
	  if (mysql_num_rows($res)<2)
	    $xml.="<simages src='thumb.jpg'></simages>";
    }
    else
      $xml.="<simages src='thumb.jpg'></simages><simages src='thumb.jpg'></simages>";
    $sql='select * from tb_schools ts,tb_subdivision_school tss where ts.id=tss.schoolid and subdivisionid='.$id;
	$res=mysql_query($sql);
    while ($row=mysql_fetch_array($res,MYSQL_ASSOC))
      $xml.='<school id="'.$row['id'].'" link="'.$row['link'].'">'.$row['name'].'</school>';
	$xml.='</subdivision>';
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

function dateDiff($start, $end)
{
  $start_ts = strtotime($start);
  $end_ts = strtotime($end);
  $diff = $end_ts - $start_ts;
  return round($diff / 86400);
}
?>

