<?
  include_once('config.php');
  $xml='';
  $id=$_GET['id'];

  $sql='select * from tb_listings where days_on_market<=1 order by listing_typeid desc, listing_price asc ';
  $result=mysql_query($sql);
  if (mysql_num_rows($result)>0)
  {
    $xml='<?xml version="1.0" encoding="ISO-8859-15"?>';
	$nr=mysql_num_rows($result);
    $xml.='<new rows="'.$nr.'" ddate="'.date("F j, Y").'">';
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
        $sql2='select * from tb_subdivision where id='.$row['subdivision'];
        $res2=mysql_query($sql2);
        $row2=mysql_fetch_array($res2,MYSQL_ASSOC);
	    $xml.="<listing id='".$row['id']."' daysold='".$diff."' acres='".$row['acres']."' sq='".$row['square_footage']."' listing_type='".$row['listing_typeid']."' img='".$img."' price='$".number_format($row['listing_price'],0,'.',',')."' age='".$row['building_age']."' sname='".htmlentities($row2['name'],ENT_QUOTES)."' no_beds='".$total_beds."' no_baths='".$total_baths."'>".$row['building_no'].' '.$row['street_name']."</listing>";
	  }
	}
	$xml.='</new>';
    header('Content-Type: text/xml');
    echo $xml;
  }
  else
  {
    $xml='<?xml version="1.0" encoding="ISO-8859-15"?>';
    $xml.='<new rows="0" ddate="'.date("F d, Y").'">';
    $xml.='<listing id="0">None</listing>';
    $xml.='</new>';
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

