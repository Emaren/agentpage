<?
  include_once('config.php');
  $xml='';

  $sql=$_SESSION['listsql'];
  $idx=$_SESSION['sqlindex'];
  $result=mysql_query($sql);
  if (strpos($idx,'id: ')===false)
  {
    if ($idx>=(mysql_num_rows($result)-1))
	  $idx=0;
    mysql_data_seek($result,$idx);
  }
  else
  {
    $indx=0;
	$found=false;
	$cnt=mysql_num_rows($result);
	$match=trim(str_replace('id: ','',$idx))*1.0;
	while ($indx<$cnt and !$found)
	{
	  $row=mysql_fetch_array($result,MYSQL_ASSOC);
	  if ($row['id']==$match)
        $found=true;
	  else
	    $indx++;
	}
	if ($found)
	{
	  $indx++;
	  mysql_data_seek($result,$indx);
	  $_SESSION['sqlindex']=$indx;
	}
  }
  if (mysql_num_rows($result)>0)
  {
    $listing=mysql_fetch_array($result,MYSQL_ASSOC);
    $xml='<?xml version="1.0" encoding="ISO-8859-15"?>';
    $xml.='<nextinlist>';
    $xml.="<next id='".$listing['id']."' nr='".mysql_num_rows($result)."' idx='".$idx."' last='".$last."'></next>";
	$_SESSION['sqlindex']=$idx+1;
	$xml.='</nextinlist>';
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

