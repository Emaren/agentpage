<?
//ini_set("max_execution_time",7200);
//error_reporting(E_ERROR);
//include "config.php";
define("MAPSHOST", "maps.google.com");
define("KEY2", "ABQIAAAAKqtGN-YZ4Tub2F6E3fBBCRQRTUoqVQQGf9yQ4jm03QO5KdSH0BQcDKf8HsNq8YAWPpvAMhNhxghGWQ");
$base_url = "http://" . MAPSHOST . "/maps/geo?output=xml" . "&key=" . KEY2;

ob_end_flush();
$city='Red Deer';

$sql='update tb_listings set updated="n" where municipalityid=4 or municipalityid=2';
$res=mysql_query($sql);

if (date('D')=='Sat' or date('D')=='Sun') // dont run Saturday or Sunday
  die();


$sql='select * from tb_municipality where name="'.$city.'"';
$res=mysql_query($sql);
$cityrow=mysql_fetch_array($res,MYSQL_ASSOC);

$cid=$cityrow['id'];
$sql='update tb_municipality set feed_dated=now() where id='.$cid;
$res=mysql_query($sql);

$docode=true;
if ($docode){
$dsid=$cityrow['default_subdivision'];
$fields_needed = array('zz','address','postal code','bedrooms','bathrooms','total sqft','age of dwelling','remarks','current price','taxes','tax year','listing associate id','colisting associate unique id','mls #','lot acres','# of enclosed parking spaces','date entered','sub area','area','range','section','township','meridian','q','listing photo count','unique id');
$data_field = array('zz','building_no','postalcode','beds_up','full_baths','square_footage','building_age','public_overview','listing_price','tax_amount','tax_year','realtor1id','realtor2id','listing_no','acres','garage_typeid','','','','','','','','','','');
$found = array();
$fname='res'.date('Ymd').'.csv';
$handle = fopen("d:\\Websites\\myrealtornow.ca\\century21\\".$fname, "r");

if ($handle)
{
  $col=strtolower(fgets($handle,4096));
  $columns=explode(",",$col);
  $mlsno=-1;
  $statusno=-1;
  $majorarea=-1;
  $real1=-1;
  $real2=-1;
  $uid=-1;
  for($i=0;$i<count($columns);$i++)
  {
    $columns[$i]=str_replace('"','',$columns[$i]);
  }
  for ($i=0;$i<count($fields_needed);$i++)
  {
    $match=array_search($fields_needed[$i],$columns);
    if ($match>0)
    {
      $found[$i]=$match;
      if ($fields_needed[$i]=='mls #')
        $mlsno=$match;
      if ($fields_needed[$i]=='status')
        $statusno=$match;
      if ($fields_needed[$i]=='area')
        $majorarea=$match;
	  if ($fields_needed[$i]=='listing associated id')
 	    $real1=$match;
  	  if ($fields_needed[$i]=='colisting associate unique id')
	    $real2=$match;
  	  if ($fields_needed[$i]=='unique id')
	    $uid=$match;
    }
    else
      $found[$i]=-1;
  }
  for ($i=1;$i<count($found);$i++)
  {
   if ($found[$i]==-1)
     echo $i.'-'.$fields_needed[$i]." was not found\n";
   else
    echo $i.'-'.$fields_needed[$i].'-'.$found[$i]."\n";
  }
  while (!feof($handle))
  {
    $data=fgets($handle,4096);
    $data=str_replace("'","",$data);
    $col=csv_explode(',', $data, '"');
    for($i=0;$i<count($col);$i++)
      $col[$i]=str_replace('"','',$col[$i]);
    for ($i = 0; $i < count($col); $i++)
    {
       $dismiss=false;
       $update=false;
       $col[$majorarea]=strtolower($col[$majorarea]);
       {
          $sql='select * from tb_listings where listing_no="'.$col[$mlsno].'"';
          $res=mysql_query($sql);
          if (mysql_num_rows($res)>0)
          {
            $update=true;
	        $list=mysql_fetch_array($res,MYSQL_ASSOC);
            $isql='update tb_listings set updated="y", ';
          }
          else
          {
             $isql='insert into tb_listings (';
             $k=count($fields_needed)-1;
             for ($j=1;$j<$k;$j++)
             {
               if ($data_field[$j]!='')
               {
                 if ($found[$j]>0)
		         {
                   if ($j<count($data_field)-2 && $j>1)
                     $isql.=',';
                   $isql.=$data_field[$j];
                 }
		      }
            }
            $isql.=',subdivision,municipalityid,listing_typeid,cdate,updated) values (';
         }
         $street_type='';
         $matrixid=-1;
         $beds=0;
         $baths=0;
         $listing_type=1;
         for ($j=1;$j<count($fields_needed);$j++)
         {
           if ($data_field[$j]=='full_baths'  and $found[$j]>0 && $col[$found[$j]]!='')
             $baths+=$col[$found[$j]];
           if ($data_field[$j]=='beds_up' and $found[$j]>0 && $col[$found[$j]]!='')
             $beds+=$col[$found[$j]];
           if ($isql!='' && $data_field[$j]!='' && $j>1 and $found[$j]>0 )
             $isql.=',';
           if ($data_field[$j]=='daysonmarket' and $found[$j]>0 && $col[$found[$j]]=='')
			 checkupdate();
           else
           if ($data_field[$j]=='garage_typeid' and $found[$j]>0 && $col[$found[$j]]=='')
			 checkupdate();
           else
           if ($data_field[$j]=='acres' and $found[$j]>0 && $col[$found[$j]]=='')
			 checkupdate();
  	       else
           if ($data_field[$j]=='full_baths'  and $found[$j]>0 && $col[$found[$j]]=='')
             checkupdate();
	       else
	       if ($data_field[$j]=='square_footage'  and $found[$j]>0 && $col[$found[$j]]=='')
			 checkupdate();
	       else
          if ($data_field[$j]=='beds_up'  and $found[$j]>0 && $col[$found[$j]]=='')
			checkupdate();
	      else
          if ($data_field[$j]=='building_age'  and $found[$j]>0 && $col[$found[$j]]=='')
			checkupdate();
          else
          if ($data_field[$j]=='tax_amount'  and $found[$j]>0 && $col[$found[$j]]=='')
			checkupdate();
		  else
          if ($data_field[$j]!='' and $found[$j]>0)
          {
	        if ($update)
	         $isql.=$data_field[$j].'=';
	        if ($data_field[$j]!='garage_typeid')
              $isql.="'".htmlentities($col[$found[$j]],ENT_QUOTES)."'";
            else
            {
              if ($col[$found[$j]]=='No')
                $isql.='0';
              else
                $isql.='1';
            }
          }
	      else
          {
           if ($fields_needed[$j]=='area' and $found[$j]>0)
	       {
	         if ($update)
		       $isql.=',municipalityid='.$cid;
		     else
              $isql.=','.$cid;
           }
	       if ($fields_needed[$j]=='sub area' and $found[$j]>0)
           {
            $sql='select * from tb_subdivision where name="'.$col[$found[$j]].'"';
            $res=mysql_query($sql);
            if (mysql_num_rows($res)>0)
            {
	           $rw=mysql_fetch_array($res,MYSQL_ASSOC);
	  	       if (!$update)
		         $isql.=','.$rw['id'];
            }
            else
            {
              $sql='select * from tb_subdivision where instr(name,"'.$col[$found[$j]].'")>0 or instr("'.$col[$found[$j]].'",name)>0';
              $res=mysql_query($sql);
              if (mysql_num_rows($res)==0)
              {
                 $sql='select * from tb_subdivision where `desc`!="" and locate("'.$col[$found[$j]].'",`desc`)>0';
		         $res=mysql_query($sql);
		         if (mysql_num_rows($res)==0)
		         {
		    	   if (!$update)
                   $isql.=','.$dsid;
                 }
		         else
		         {
		           $rw=mysql_fetch_array($res,MYSQL_ASSOC);
		  	       if (!$update)
			         $isql.=','.$rw['id'];
		         }
		      }
              else
              {
                $rw=mysql_fetch_array($res,MYSQL_ASSOC);
	 	        if (!$update)
                  $isql.=','.$rw['id'];
              }
            }
        }
	  }
      if ($fields_needed[$j]=='range' and $found[$j]>0)
        $range=$col[$found[$j]];
      if ($fields_needed[$j]=='section' and $found[$j]>0)
        $section=$col[$found[$j]];
      if ($fields_needed[$j]=='location' and $found[$j]>0)
        $location=$col[$found[$j]];
      if ($fields_needed[$j]=='township' and $found[$j]>0)
        $township=$col[$found[$j]];
      if ($fields_needed[$j]=='meridian' and $found[$j]>0)
        $westofmeridian=$col[$found[$j]];
      if ($fields_needed[$j]=='street' and $found[$j]>0)
        $street=$col[$found[$j]];
      if ($fields_needed[$j]=='listing photo count' and $found[$j]>0)
        $photocount=$col[$found[$j]];
//      if ($fields_needed[$j]=='unique id' and $found[$j]>0)
//        $uid=$col[$found[$j]];

      $street_type='';
      $lsd=$location.' '.$section.'-'.$township.'-'.$range.'W'.$westofmeridian;

    }
  }
  }
  if ($beds==0 && $baths==0)
    $listing_type=2;
  else
    $listing_type=1;
  if ($update)
    $isql.=",listing_typeid=$listing_type ";
  else
    $isql.=",$listing_type,'".date('Y-m-d')."','y')";
  $lid=-1;
  if ($update)
  {
    $isql.=' where listing_no="'.$col[$mlsno].'"';
  }
  $uuid=$col[$uid];
  echo $isql."-photo count=".$photocount."-unique id=$uuid\n";
  $res=mysql_query($isql);

    if ($res)
    {
      if ($update)
	    $lid=$list['id'];
	  else
       $lid=mysql_insert_id();
    }
    else
      echo $isql.' '.mysql_error();

    if ($photocount>0 && $uuid!='')
    {
      for ($m=1;$m<=$photocount;$m++)
	  {
        $newname='d:/websites/myrealtornow.ca/images/'.$uuid.'_'.$m.'.jpg';
        $pth='d:/websites/myrealtornow.ca/images/';
        $ext='jpg';
        if (!file_exists($newname))
        {
          $listingno=str_pad($m,2,"0",STR_PAD_LEFT);
//          die('http://images.realtyserver.com/photo_server.php?btnSubmit=GetPhoto&board=red_deer&name='.$uuid.'.L'.$listingno);
          $jpg=file_get_contents('http://images.realtyserver.com/photo_server.php?btnSubmit=GetPhoto&board=red_deer&name='.$uuid.'.L'.$listingno);
          $fp=fopen($newname,"w+");
		  fputs($fp, $jpg);
          fclose($fp);
        }
        if (file_exists($newname))
        {
          $start='d:/websites/myrealtornow.ca/images/'.$uuid.'_'.$m;
          if (!file_exists($start.'_sm1.jpg'))
            save_resize_image($newname,475,350,$ext,'_sm1');
		  if (!file_exists($start.'_sm2.jpg'))
            save_resize_image($newname,150,110,$ext,'_sm2');
          if (!file_exists($start.'_sm3.jpg'))
            save_resize_image($newname,105,332,$ext,'_sm3');
	      save_resize_image($newname,450,1200,$ext,'');
		  if (!file_exists($start.'_sm4.jpg'))
            createThumb($newname,$ext,'_sm4',125,125);
		  $n=$m+1;
          $sql='select * from tb_listing_images where imagename="'.$uuid.'_'.$m.'" and listingid='.$lid;
          $res=mysql_query($sql);
          if (mysql_num_rows($res)==0)
          {
            $sql2='insert into tb_listing_images (listingid,imagename,ext,ordr) values ('.$lid.',"'.$uuid.'_'.$m.'","jpg",'.$n.')';
            $res=mysql_query($sql2);
          }
        }
	  }
    }
  }
}

} // of of docode

function save_resize_image($file,$height,$width,$ext,$suffix)
 {
   $pth='d:/websites/myrealtornow.ca/images/';

   $file_tmp=$file;
   if($ext == "jpg")
     $new_img = imagecreatefromjpeg($file_tmp);
   elseif($ext == "png")
        $new_img = imagecreatefrompng($file_tmp);
      elseif($ext == "gif")
            $new_img = imagecreatefromgif($file_tmp);
   list($owidth, $oheight) = getimagesize($file_tmp);
   $source_aspect_ratio = $owidth / $oheight;
   $thumbnail_aspect_ratio = $width / $height;
   $newwidth=$width;
   $newheight=$height;
   if ( $owidth <= $width && $oheight <= $height )
   {
    $newwidth = $owidth;
    $newheight = $oheight;
   }
   elseif ( $thumbnail_aspect_ratio > $source_aspect_ratio )
     $newwidth = ( int ) ( $height * $source_aspect_ratio );
    else
     $newheight = ( int ) ( $width / $source_aspect_ratio );
   $resized_img = imagecreatetruecolor($newwidth,$newheight);
   imagecopyresampled($resized_img, $new_img, 0, 0, 0, 0, $newwidth, $newheight, $owidth, $oheight );
   $filename=basename($file);
   $filename=substr($filename, 0,strrpos($filename, '.')).$suffix.'.jpg';
   ImageJpeg ($resized_img,$pth.'/'.$filename,100);
   ImageDestroy ($resized_img);
   ImageDestroy ($new_img);
}

function createThumb($str_image_src,$ext,$suffix,$int_new_width,$int_new_height)
 {
   if($ext == "jpg")
     $res_src_image = imagecreatefromjpeg($str_image_src);
   elseif($ext == "png")
        $res_src_image = imagecreatefrompng($str_image_src);
      elseif($ext == "gif")
            $res_src_image = imagecreatefromgif($str_image_src);

      $str_image_dest=basename($str_image_src);
      $str_image_dest='d:/websites/myrealtornow.ca/images/'.substr($str_image_dest, 0,strrpos($str_image_dest, '.')).$suffix.'.jpg';
      $int_new_height_tmp = get_image_height($res_src_image, $int_new_width);
      $res_dst_image = imagecreatetruecolor($int_new_width,$int_new_height);

      // Check proportions are the same.
      if(($int_new_height / $int_new_width) == ( imageSY($res_src_image)/ imageSX($res_src_image))){
           // Resize Image
           imagecopyresampled($res_dst_image, $res_src_image, 0, 0, 0, 0, $int_new_width, $int_new_height,              imagesx($res_src_image), imagesy($res_src_image));
      } else {
           // Scale Image
           $res_scaled_image = scale_image(&$res_src_image, $int_new_width, $int_new_height);
           // Crop Image
           crop_image(&$res_dst_image, &$res_scaled_image, $int_new_width, $int_new_height);
      }
      imagejpeg($res_dst_image,$str_image_dest);
 }

 function get_image_height($res_src_image, $int_new_width) {

      // Get Image Dimensions
      $int_old_x = imageSX($res_src_image);
      $int_old_y = imageSY($res_src_image);

      // Determine if width is larger or smaller then height
      if ($int_old_x < $int_old_y) {
           $int_ratio = $int_old_y / $int_old_x;
           $int_new_height =  round($int_ratio * $int_new_width);
      } else if ($int_old_x > $int_old_y) {
           $int_ratio = $int_old_x / $int_old_y;
           $int_new_height =  round($int_ratio * $int_new_width);
      } else if ($int_old_x == $int_old_y) {
           $int_new_height = $int_new_width;
      }

      return $int_new_height;
 }

 function get_image_width($res_src_image, $int_new_height) {

      // Get Image Dimensions
      $int_old_x = imageSX($res_src_image);
      $int_old_y = imageSY($res_src_image);

      // Determine if width is larger or smaller then height
      if ($int_old_y < $int_old_x) {
           $int_ratio = $int_old_x / $int_old_y;
           $int_new_width =  round($int_ratio * $int_new_height);
      } else if ($int_old_y > $int_old_x) {
           $int_ratio = $int_old_y / $int_old_x;
           $int_new_width =  round($int_ratio * $int_new_height);
      } else if ($int_old_y == $int_old_x) {
           $int_new_width = $int_new_height;
      }

      return $int_new_width;
 }

 function scale_image($res_src_image, $int_new_width, $int_new_height){
      $int_orig_width = $int_new_width;
      $int_orig_height = $int_new_height;

      if(imagesx($res_src_image) < imagesy($res_src_image)){
           // Height Larger Then Width

           // Get New Width
           $int_width_diff =  $int_new_width - imagesx($res_src_image);
           $int_new_width =   imagesx($res_src_image) + $int_width_diff;

           // Get New Height
           $int_new_height = get_image_height($res_src_image, $int_new_width);

           $res_dst_image = imagecreatetruecolor($int_new_width,$int_new_height);
           imagecopyresampled($res_dst_image, $res_src_image, 0, 0, 0, 0, $int_new_width, $int_new_height, imagesx($res_src_image), imagesy($res_src_image));

      } else{
           // Width Larger Then Height or Equal

           // Get New Height
           $int_height_diff =  $int_new_height - imagesy($res_src_image);
           $int_new_height = imagesy($res_src_image) + $int_height_diff;

           // Get New Width
           $int_new_width = get_image_width($res_src_image, $int_new_height);

           $res_dst_image = imagecreatetruecolor($int_new_width,$int_new_height);
           imagecopyresampled($res_dst_image, $res_src_image, 0, 0, 0, 0, $int_new_width, $int_new_height, imagesx($res_src_image), imagesy($res_src_image));
      }

      return $res_dst_image;
 }

 function crop_image($res_dst_image, $res_src_image, $int_new_width, $int_new_height){
      // Get XY Cordinates for Crop
      $int_crop_x = floor(((imagesx($res_src_image) - $int_new_width)  / 2));
      $int_crop_y = floor(((imagesy($res_src_image) - $int_new_height)  / 2));
      imagecopyresampled($res_dst_image, $res_src_image, 0, 0, $int_crop_x, $int_crop_y,$int_new_width, $int_new_height, $int_new_width, $int_new_height);
 }

$sql='select * from tb_listings where updated="n"';
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res,MYSQL_ASSOC))
{
  $sql='select * from tb_listing_images where listingid='.$row['id'];
  $result=mysql_query($sql);
  while ($row2=mysql_fetch_array($result,MYSQL_ASSOC))
  {
    unlink('d:/Websites/myrealtornow.ca/images/'.$row2['imagename'].'_sm1.'.$row2['ext']);
	unlink('d:/Websites/myrealtornow.ca/images/'.$row2['imagename'].'_sm2.'.$row2['ext']);
	unlink('d:/Websites/myrealtornow.ca/images/'.$row2['imagename'].'_sm3.'.$row2['ext']);
	unlink('d:/Websites/myrealtornow.ca/images/'.$row2['imagename'].'_sm4.'.$row2['ext']);
	$sql='delete from tb_listing_images where id='.$row2['id'];
	$res2=mysql_query($sql);
  }
  $sql='delete from tb_listings where id='.$row['id'];
  $res3=mysql_query($sql);
}

$fname='ofc'.date('Ymd').'.csv';
$handle = fopen("d:\\Websites\\myrealtornow.ca\\century21\\".$fname, "r");
$offices=array();

while (!feof($handle))
{
    $data=fgets($handle,4096);
    $data=str_replace("'","",$data);
    $col=csv_explode(',', $data, '"');
    $offices[$col[3]]=$col[4];
}

fclose($handle);

// get the real estate agents

$fieldsneeded = array('zz','given names','lastname','email','associate number','office id');
$datafield = array('zz','fname','lname','email','realtorid','');
$found2 = array();

$fname='agt'.date('Ymd').'.csv';
$handle = fopen("d:\\Websites\\myrealtornow.ca\\century21\\".$fname, "r");

if ($handle)
{
  $col=strtolower(fgets($handle,4096));
  $col=str_replace('"',"",$col);
  $columns=explode(",",$col);
  $realtorid=-1;
  $officeid=-1;
  for ($i=0;$i<count($fieldsneeded);$i++)
  {
    echo $i.'-'.$fieldsneeded[$i]."-".array_search($fieldsneeded[$i],$columns)."\n";
    $match=array_search($fieldsneeded[$i],$columns);
    if ($match===false)
      $found2[$i]=-1;
    else
    {
      $found2[$i]=$match;
      if ($columns[$match]=='associate number')
        $realtorid=$match;
      if ($columns[$match]=='office id')
        $officeid=$match;
    }
  }
while (!feof($handle))
{
    $data=fgets($handle,4096);
    $data=str_replace("'","",$data);
    $col=csv_explode(',', $data, '"');
    $update=false;
    $sql='select * from tb_other_realtors where realtorid="'.$col[$realtorid].'"';
    $res=mysql_query($sql);
    if (mysql_num_rows($res)>0)
    {
      $update=true;
      $list=mysql_fetch_array($res,MYSQL_ASSOC);
      $isql='update tb_other_realtors set office="'.$offices[$col[$officeid]].'"';

    }
    else
    {
      $isql='insert into tb_other_realtors (office,';
      $k=count($fieldsneeded);
      for ($j=1;$j<$k;$j++)
      {
        if ($datafield[$j]!='')
        {
          if ($found2[$j]>=0)
  		  {
            if ($j<count($datafield) && $j>1)
            $isql.=',';
            $isql.=$datafield[$j];
          }
	    }
      }
    }
    if (!$update)
      $isql.=') values ("'.$offices[$col[$officeid]].'",';
    for ($j=1;$j<count($fieldsneeded);$j++)
    {
      if ($isql!='' && $datafield[$j]!='' && $j>1 and $found2[$j]>=0 )
        $isql.=',';

      if ($datafield[$j]!='' and $found2[$j]>=0)
      {
	    if ($update)
	     $isql.=$datafield[$j].'=';
        $isql.="'".htmlentities($col[$found2[$j]],ENT_QUOTES)."'";
      }
    }
    $lid=-1;
    if ($update)
      $isql.=' where realtorid="'.$col[$realtorid].'"';
    else
      $isql.=')';

    echo $isql."\n";
    $res=mysql_query($isql);
    if ($res)
    {
      if ($update)
	    $lid=$list['id'];
	  else
       $lid=mysql_insert_id();
	   echo $col[$realtorid],' updated <br>';
    }
    else
     echo $isql.' '.mysql_error();
   }
 }

 die();

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

function myErrorHandler($errno, $errstr, $errfile, $errline)
{
    switch ($errno) {
    case E_USER_ERROR:
        echo "<b>My ERROR</b> [$errno] $errstr<br />\n";
        echo "  Fatal error on line $errline in file $errfile";
        echo "Aborting...<br />\n";
        exit(1);
        break;

    case E_USER_WARNING:
        echo "<b>My WARNING</b> [$errno] $errstr<br />\n";
        break;

    case E_USER_NOTICE:
        echo "<b>My NOTICE</b> [$errno] $errstr<br />\n";
        break;

    default:
        echo "Unknown error type: [$errno] $errstr<br />\n";
        echo " error on line $errline in file $errfile";
        break;
    }

    /* Don't execute PHP internal error handler */
    return true;
}

function checkupdate()
{
  global $update,$isql,$j,$data_field;

   if ($update)
     $isql.=$data_field[$j].'=';
   $isql.='0';
}

function csv_explode($delim=',', $str, $enclose='"', $preserve=false){
  $resArr = array();
  $n = 0;
  $expEncArr = explode($enclose, $str);
  foreach($expEncArr as $EncItem){
    if($n++%2){
      array_push($resArr, array_pop($resArr) . ($preserve?$enclose:'') . $EncItem.($preserve?$enclose:''));
    }else{
      $expDelArr = explode($delim, $EncItem);
      array_push($resArr, array_pop($resArr) . array_shift($expDelArr));
      $resArr = array_merge($resArr, $expDelArr);
    }
  }
  return $resArr;
}
?>