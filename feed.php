<?
ini_set("max_execution_time",2400);
error_reporting(E_ERROR);
include "RETS_class.php";
include "config.php";

$city='Grande Prairie';

$sql='select * from tb_municipality where name="'.$city.'"';
$res=mysql_query($sql);
$cityrow=mysql_fetch_array($res,MYSQL_ASSOC);

$cid=$cityrow['id'];
$dsid=$cityrow['default_subdivision'];

$sql='update tb_municipality set feed_dated="'.date('Y-m-d').' where id='.$cid;
$res=mysql_query($sql);

$fields_needed = array('zz','housenum','propcode','bedrooms_up','bedrooms_down','fullbaths','halfbaths','sqftg','age','publicoverview','lprice','taxes','taxyr','la1_a_userid','la2_a_userid','mlsnumber','matrix_unique_id','photocount','sdistrict','city','street','strtyp');
$data_field = array('zz','building_no','postalcode','beds_up','beds_down','full_baths','half_baths','square_footage','building_age','public_overview','listing_price','tax_amount','tax_year','realtor1id','realtor2id','listing_no','','','','','','');
$found = array();
$rets = new RETS;

//Login to NAR CRT Test Server
$results = $rets->login(
     $Host = 'http://matrix.gpreb.com/rets/login.ashx'  //RETS server web address to login
    ,$Account = 'Division1'                         //account assigned by Assoication
    ,$Password = 'tonyblum'                 //password assigned by Assoication
    ,$User_Agent = 'RETS_class/1.0'    //string application & version
    ,$User_Agent_Pwd  = null             //string - useragent password assigned by Assoication
    ,$User_Agent_Auth = false           //true | false
    ,$Rets_Version = 'RETS/1.5'          //RETS/1.5 | RETS/1.7 | RETS/2.0
    ,$Standard_Names = false            //true | false
    ,$Post_Requests = true                //true | false - true for POST, false for GET
    ,$Format = 'COMPACT-DECODED'   //COMPACT | COMPACT-DECODED | STANDARD-DML | STANDARD-XML:dtd-version
    ,$HTTP_Logging = true                //true | false - enable logging to file
    ,$Log_File = 'C:/http.log'   //log file path & name
);

If (!$results ) { exit; }


if ($_GET['type']=='listing')
{
  $response = $rets->Search(
    $Resource ='Property'
   ,$Class = 'RESI'
   ,$Count = 1           //0 = data   1 = data & count, 2 = count
   ,$Format               //COMPACT | COMPACT-DECODED | STANDARD-XML | STANDARD-XML:dtd-version
   ,$Limit = 150
   ,$QueryType = 'DMQL2'
   ,$Standard_Names
   ,$Select = '*'
   ,$Query = '((City=Grande Prairie))'
  );

//echo $response;
$doc = new DOMDocument();
$doc->loadXML($response);
$col = strtolower($doc->getElementsByTagName( "COLUMNS" )->item(0)->nodeValue);
$columns=explode("\t",$col);

for ($i=0;$i<count($fields_needed);$i++)
{
  $match=array_search($fields_needed[$i],$columns);
  if ($match>0)
    $found[$i]=$match;
  else
    $found[$i]=-1;
}
for ($i=1;$i<count($found);$i++)
{
   if ($found[$i]==-1)
     echo $fields_needed[$i]." was not found\n";
}
$mlsno=array_search('mlsnumber',$columns);
$data = $doc->getElementsByTagName( "DATA" );
for ($i = 0; $i < $data->length; $i++)
{
  $line=$data->item($i)->nodeValue;
  $col=explode("\t",$line);
  $sql='select * from tb_listings where listing_no="'.$col[$mlsno].'"';
  $res=mysql_query($sql);
  if (mysql_num_rows($res)>0)
  {
  	$update=true;
    $isql='update tb_listings set ';
  }
  else
  {
    $update=false;
    $isql='insert into tb_listings (';
  }
  $k=count($fields_needed)-1;
  if (!$update)
  {
    for ($j=1;$j<$k;$j++)
    {
      if ($data_field[$j]!='')
      {
        if ($j<count($data_field)-2 && $j>1)
          $isql.=',';
        $isql.=$data_field[$j];
      }
    }
    $isql.=',orig_sname,subdivision,municipalityid,street_name,listing_typeid,photocount,matrixid) values (';
  }
  $street_type='';
  $matrixid=-1;
  $beds=0;
  $baths=0;
  $listing_type=1;
  for ($j=1;$j<count($fields_needed);$j++)
  {
    if ($data_field[$j]=='full_baths' && $col[$found[$j]]!='')
     $bath+=$col[$found[$j]];
    else
    if ($data_field[$j]=='half_baths' && $col[$found[$j]]!='')
     $bath+=$col[$found[$j]];
    if ($data_field[$j]=='beds_up' && $col[$found[$j]]!='')
       $beds+=$col[$found[$j]];
    else
    if ($data_field[$j]=='beds_down' && $col[$found[$j]]!='')
       $beds+=$col[$found[$j]];
    if ($isql!='' && $data_field[$j]!='' && $j>1)
      $isql.=',';
    if ($data_field[$j]=='full_baths' && $col[$found[$j]]=='')
    {
     if (!$update)
       $isql.='0';
     else
       $isql.=$data_field[$j].'=0';
    }
    else
    if ($data_field[$j]=='half_baths' && $col[$found[$j]]=='')
    {
     if (!$update)
       $isql.='0';
     else
       $isql.=$data_field[$j].'=0';
    }
    else
    if ($data_field[$j]=='square_footage' && $col[$found[$j]]=='')
    {
     if (!$update)
       $isql.='0';
     else
       $isql.=$data_field[$j].'=0';
    }
    else
    if ($data_field[$j]=='beds_up' && $col[$found[$j]]=='')
    {
     if (!$update)
       $isql.='0';
     else
       $isql.=$data_field[$j].'=0';
    }
    else
    if ($data_field[$j]=='beds_down' && $col[$found[$j]]=='')
    {
     if (!$update)
       $isql.='0';
     else
       $isql.=$data_field[$j].'=0';
    }
    else
    if ($data_field[$j]=='building_age' && $col[$found[$j]]=='')
    {
     if (!$update)
       $isql.='0';
     else
       $isql.=$data_field[$j].'=0';
    }
    else
    if ($data_field[$j]=='tax_amount' && $col[$found[$j]]=='')
    {
     if (!$update)
       $isql.='0';
     else
       $isql.=$data_field[$j].'=0';
    }
    else
    if ($data_field[$j]!='')
    {
      if (!$update)
        $isql.="'".htmlentities($col[$found[$j]],ENT_QUOTES)."'";
      else
        $isql.=$data_field[$j]."='".htmlentities($col[$found[$j]],ENT_QUOTES)."'";
    }
    else
    {
      if ($fields_needed[$j]=='city')
      {
        if (!$update)
          $isql.=','.$cid;
        else
          $isql.=',municipalityid='.$cid;

      }
      if ($fields_needed[$j]=='sdistrict')
      {
        if (!$update)
          $isql.=", '".htmlentities($col[$found[$j]],ENT_QUOTES)."'";
        else
          $isql.=",orig_sname='".htmlentities($col[$found[$j]],ENT_QUOTES)."'";
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
             else
               $isql.=',subdivision='.$dsid;

           }
		   else
		   {
		     $rw=mysql_fetch_array($res,MYSQL_ASSOC);
             if (!$update)
			   $isql.=','.$rw['id'];
             else
               $isql.=',subdivision='.$rw['id'];
		   }
		}
        else
        {
          $rw=mysql_fetch_array($res,MYSQL_ASSOC);
          if (!$update)
            $isql.=','.$rw['id'];
          else
            $isql.=',subdivision='.$rw['id'];
        }
      }

      if ($fields_needed[$j]=='strtyp')
        $street_type=$col[$found[$j]];
      if ($fields_needed[$j]=='street')
        $street=$col[$found[$j]];
      if ($fields_needed[$j]=='matrix_unique_id')
        $matrixid=$col[$found[$j]];
      if ($fields_needed[$j]=='photocount')
        $photocount=$col[$found[$j]];

    }
  }
  if ($beds==0 && $baths==0)
    $listing_type=2;
  if ($photocount=='')
    $photocount=0;
  if (!$update)
    $isql.=",'".htmlentities($street." ".$street_type,ENT_QUOTES)."',$listing_type,$photocount,$matrixid)";
  else
  {
    $isql.=",street_name='".htmlentities($street." ".$street_type,ENT_QUOTES)."',listing_typeid=$listing_type,photocount=$photocount,matrixid=$matrixid";
    $isql.=' where listing_no="'.$col[$mlsno].'"';
  }
  $lid=-1;
  $res=mysql_query($isql);
  if ($res)
    $lid=mysql_insert_id();
  else
     echo $isql.' '.mysql_error();
//  echo $isql;

}
}

if ($_GET['type']=='photo')
{
  echo 'getting images';
  $sql='select * from tb_listings';
  $res=mysql_query($sql);
  while ($row=mysql_fetch_array($res,MYSQL_ASSOC))
  {
    $sql='select * from tb_listing_images where listingid='.$row['id'];
    $res2=mysql_query($sql);
    if (mysql_num_rows($res2)==0)
    {
      $photocount=$row['photocount'];
      $matrixid=$row['matrixid'];
      if ($photocount>0 && $matrixid!=-1 )
      {
        echo 'getting '.$row['listing_no'].' '.$photocount.' '.$matrixid.'<br>';
        for ($m=1;$m<=$photocount;$m++)
  	    {
          $result=$rets->GetObject( 'Property','HiRes',$matrixid.':'.$m);
          $newImg = imagecreatefromstring($result);
          imagejpeg($newImg, "d:/Websites/myrealtornow.ca/images/".$matrixid.'_'.$m.".jpg",100);
          $newname='d:/websites/myrealtornow.ca/images/'.$matrixid.'_'.$m.'.jpg';
          $pth='d:/websites/myrealtornow.ca/images/';
          $ext='jpg';
          if (file_exists($newname))
          {
            save_resize_image($newname,475,350,$ext,'_sm1');
            save_resize_image($newname,150,110,$ext,'_sm2');
            save_resize_image($newname,105,332,$ext,'_sm3');
	        save_resize_image($newname,450,1200,$ext,'');
            createThumb($newname,$ext,'_sm4',125,125);
	        $n=$m+1;
	        $lid=$row['id'];
            $sql2='insert into tb_listing_images (listingid,imagename,ext,ordr) values ('.$lid.',"'.$matrixid.'_'.$m.'","jpg",'.$n.')';
            echo $sql2.'<br>';
            $res3=mysql_query($sql2);
          }
	   }
      }
    }
  }
}

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


$results = $rets->logout();


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
?>