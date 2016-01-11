<?
  include_once('config.php');
  $xml='';

     $beds=$_GET['beds'];
     $baths=$_GET['baths'];
     $garage=$_GET['garage'];
     $sdivision=$_GET['sdivision'];
     $price_from=$_GET['price_from'];
     $price_to=$_GET['price_to'];
     $age=$_GET['age'];
	 $sizefrom=$_GET['sizefrom'];
	 $sizeto=$_GET['sizeto'];
	 $acresfrom=$_GET['acresfrom'];
	 $acresto=$_GET['acresto'];
     $leaseprice_from=$_GET['leaseprice_from'];
     $leaseprice_to=$_GET['leaseprice_to'];
     $listingno=$_GET['listingno'];

	 $purchase_type=$_GET['ptype'];
	 $listing_type=$_GET['ltype'];

     $sqlend='';
     $sql='select *,ts.name as sname,tb_listings.id as lid from tb_listings,tb_subdivision ts where ';

    if ($listingno!='' && $listingno!='undefined')
      $sqlend=' listing_no="'.$listingno.'"';
    else
    {
      if ($listing_type=='Residential')
      {
	    $sqlend=appendSQL($sqlend,'listing_typeid=1');
        if ($baths!='any')
          $sqlend=appendSQL($sqlend,' (full_baths+half_baths)>='.$baths);
	    if ($beds!='any')
   	      $sqlend=appendSQL($sqlend,' (beds_up+beds_down)>='.$beds);
	    if ($garage=='yes')
           $sqlend=appendSQL($sqlend,' garage_typeid>0');
        else
	      $sqlend=appendSQL($sqlend,'garage_typeid=0');
	    if ($age!='any')
	    {
	      $age=breakup($age);
          if ($age[0]=='')
	        $sqlend=appendSQL($sqlend,'building_age>='.$age[1]);
	      else
	      $sqlend=appendSQL($sqlend,'building_age between '.$age[0].' and '.$age[1]);
        }
        $pcompare="listing_price";
        $pricef=$price_from*1000;
	    $pricet=$price_to*1000;
      }
      if ($sdivision!='' && $sdivision!='0')
      {
         $div=explode('-',$sdivision);
	     if (count($div)>0)
 	       $sqlend=appendSQL($sqlend,'subdivision in ('.implode(',',$div).')');
      }
      if ($listing_type=='Commercial')
      {
	    $sqlend=appendSQL($sqlend,'listing_typeid=2');
        if ($acresfrom!='' && $acresto!='')
	      $sqlend=appendSQL($sqlend,' acres between '.$acresfrom.' and '.$acresto);
        if ($sizefrom!='' && $sizeto!='')
	      $sqlend=appendSQL($sqlend,' square_footage between '.$sizefrom.' and '.$sizeto);
	    if ($garage=='yes')
           $sqlend=appendSQL($sqlend,' garage_typeid>0');
        else
	      $sqlend=appendSQL($sqlend,'garage_typeid=0');
	    if ($purchase_type=='Lease')
	    {
	      $pcompare='(monthly_fee*square_footage)';
	      $pricef=$leaseprice_from;
	      $pricet=$leaseprice_to;
	    }
	    else
	    {
	      $pcompare='listing_price';
          $pricef=$price_from*1000;
	      $pricet=$price_to*1000;
	    }
      }
      else
      {
	      $pcompare='listing_price';
          $pricef=$price_from*1000;
	      $pricet=$price_to*1000;
      }
      if ($pricef==1000000)
      {
	      $sqlend=appendSQL($sqlend,' and '.$pcompare.'>=1000000');
      }
      else
        if ($pricet==1000000)
	    {
	      $sqlend=appendSQL($sqlend,$pcompare.'>='.$pricef);
	    }
	    else
           $sqlend=appendSQL($sqlend,'('.$pcompare.' between '.$pricef.' and '.$pricet.')');
     $sqlend=appendSQL($sqlend,'ts.municipalityid='.$_SESSION['municipalityid']);
   }
   $sqlend=appendSQL($sqlend,'subdivision=ts.id');
   $sql.=$sqlend.' order by listing_price desc';
   $result=mysql_query($sql);
   $xml='<?xml version="1.0" encoding="ISO-8859-15"?>';
   $xml.='<search>';
   if ($result and mysql_num_rows($result)>0)
   {
	  while ($row=mysql_fetch_array($result,MYSQL_ASSOC))
	  {
	    $img='';
	    $sql2='select * from tb_listing_images where listingid='.$row['lid'].' order by ordr asc';
		$res=mysql_query($sql2);
		if (mysql_num_rows($res)>0)
		{
		  $rw=mysql_fetch_array($res,MYSQL_ASSOC);
		  $img=$rw['imagename'].'_sm4.'.$rw['ext'];
		}
		else
		  $img='thumb.jpg';
	    $total_beds=$row['beds_up']+$row['beds_down'];
	    $total_baths=$row['full_baths']+$row['half_baths'];
	    $xml.="<listing id='".$row['lid']."' sq='".$row['square_footage']."' img='".$img."' price='$".number_format($row['listing_price'],0,'.',',')."' age='".$row['building_age']."' no_beds='".$total_beds."' no_baths='".$total_baths."'>".$row['building_no'].' '.$row['street_name']."</listing>";
	  }
	}
	$xml.='</search>';
    header('Content-Type: text/xml');
    echo $xml;



  function breakup($str)
  {
     $ps=strpos($str,'-');
	 $frst=substr($str,0,$ps);
	 $ps++;
	 $scnd=substr($str,$ps);
	 return array($frst,$scnd);
  }

  function appendSQL($sql,$str)
  {
    if ($sql!='')
	  $sql.=' and ';
	$sql.=$str;

	return $sql;
  }
?>

