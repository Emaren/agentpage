<?
  include_once('config.php');
  $xml='';
  $id=$_GET['id'];

  $ok='';
  if ($_SESSION['uid']!='')
  {
    $sql='select * from tb_user_listings where userid='.$_SESSION['uid'].' and listingid='.$id;
	$res=mysql_query($sql);
	if (mysql_num_rows($res)==0)
	{
	  $sql='insert into tb_user_listings (userid,listingid) values ('.$_SESSION['uid'].','.$id.')';
	  $res=mysql_query($sql);
	  $fid=mysql_insert_id();
	  if ($res)
	  {	    
	    $sql='select *,ts.name as sname,tb_listings.id as lid from tb_listings,tb_subdivision ts where ts.id=tb_listings.subdivision and tb_listings.id='.$id;
		$res=mysql_query($sql);
		$row=mysql_fetch_array($res,MYSQL_ASSOC);
     	$sql2='select * from tb_listing_images where listingid='.$row['lid'].' and ordr=0';
	    $res2=mysql_query($sql2);
	    $row2=mysql_fetch_array($res2,MYSQL_ASSOC);		
	    $ok='200';
	    $err='Added successfully';
	  }
	  else
	  {
	    $ok='500';
		$err=mysql_error();
	  }	
	 }
	 else
	 {
	   $ok='300';
	   $err='Already in favorites list';
	 }	
    $xml='<?xml version="1.0" encoding="ISO-8859-15"?>';
    $xml.='<fav>';
    $xml.="<action id='".$ok."' fid='".$fid."' image='".$row2['imagename'].'.'.$row2['ext']."' sname='".$row['name']."' price='".number_format($row['listing_price'],2,'.',',')."'>".$err."</action>";
	$xml.='</fav>';
    header('Content-Type: text/xml');
    echo $xml;
  }
  else
  {
    $xml='<?xml version="1.0" encoding="ISO-8859-15"?>';
    $xml.='<fav>';
    $xml.='<action id="0">You need to login</action>';
    $xml.='</fav>';
    header('Content-Type: text/xml');
    echo $xml;  
  }
?>

