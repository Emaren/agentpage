<?
  include('config.php');
  set_time_limit(16700);
  define("MAPS_HOST", "maps.google.com");
  define("KEY", "ABQIAAAAKqtGN-YZ4Tub2F6E3fBBCRQRTUoqVQQGf9yQ4jm03QO5KdSH0BQcDKf8HsNq8YAWPpvAMhNhxghGWQ");
  $base_url = "http://" . MAPS_HOST . "/maps/geo?output=xml" . "&key=" . KEY;
  $sql='select * from tb_realtor_municipality trm,tb_municipality tm where realtorid=1 and tm.id=trm.municipalityid';
  $res=mysql_query($sql);
  $rw=mysql_fetch_array($res,MYSQL_ASSOC);

$sql='select *,tm.name as mname,tm.prov as mprov, ts.name as sname,tl.id as tid from tb_listings tl,tb_subdivision ts,tb_municipality tm where lat is null and `long` is null and tl.subdivision=ts.id and tm.id=ts.municipalityid';
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result,MYSQL_ASSOC))
{
  $mname=$row['mname'];
  if (strpos($mname,'County'))
	$mname=$row['sname'];
  $address=$row['building_no'].' '.$row['street_name'].' '.$mname.' '.$row['mprov'].' Canada '.$row['postalcode'];
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
    if (strcmp($status,"602")==0)
    {
	  $address=$row['building_no'].' '.$row['street_name'].' '.$mname.' '.$row['mprov'].' Canada ';
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
	      echo $request_url.'-'.$status."\n";
		  $lat='';
		  $lng='';
	  }
    }
    else
    {
      echo $request_url.'-'.$status."\n";
	  $lat='';
	  $lng='';
    }
  }
  $sql2='update tb_listings set lat='.$lat.', `long`='.$lng.' where id='.$row['tid'];
  $res2=mysql_query($sql2);
  usleep(1000000);
}
?>