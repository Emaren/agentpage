<?
  include_once('config.php');

    if (isset($_GET["PHPSESSID"]))
      session_id($_GET["PHPSESSID"]);
	else
      if (isset($_POST["PHPSESSID"]))
        session_id($_POST["PHPSESSID"]);
    session_start();

    error_reporting(0);
    if (isset($_GET['id']))
	  $id=$_GET['id'];
    if (isset($_GET['sid']))
	  $sid=$_GET['sid'];
    if (isset($_GET['cid']))
	  $cid=$_GET['cid'];

    $err=handle_upload($id,$sid,$cid);

function handle_upload($id,$sid,$cid)
{
    global $path,$config,$newname,$filename;

    $pth=realpath('.').'\\images';
    if (!is_dir($pth))
      mkdir($pth);

   $err='';

   foreach($_FILES as $file)
   {
        switch($file['error'])
       {
        case 0:
           if($file['name'] != NULL)
             $err = processFile($file['name'],$file['tmp_name'],$id,$sid,$cid);
           break;
        case (1|2):
           $err = 'file upload is too large';
           break;
        case (6|7):
           $err = 'internal error – flog the webmaster';
            break;
       }
   }
   return $err;
}

    function processFile($filename,$tmp_name,$id,$sid,$cid)
    {
        $pth=realpath('.').'\\images';
        $filename = basename($filename);
        $ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
        if (($ext == 'jpg') || ($ext=='gif') || ($ext=='png') || ($ext=='jpeg'))
        {
           $newname=$pth.'\\'.strtolower($filename);
           if (!(move_uploaded_file($tmp_name,$newname)))
               $err='Error: A problem occurred during file upload!';
        }
        else
          $err='Error: Only .jpg,gif,png,jpeg files are accepted for upload';
		if ($id!='')
		{
		  $bse = substr($filename, 0,strrpos($filename, '.'));
		  $sql='select * from tb_listing_images where listingid='.$id.' and imagename="'.$bse.'" and ext="'.$ext.'"';
		  $res=mysql_query($sql);
		  if (mysql_num_rows($res)==0)
		  {
		    $sql='insert into tb_listing_images (listingid,imagename,ext) values ('.$id.',"'.$bse.'","'.$ext.'")';
			$res=mysql_query($sql);
			$fid=mysql_insert_id();
		  }
		}
		if ($sid!='')
		{
		  $bse = substr($filename, 0,strrpos($filename, '.'));
		  $sql='select * from tb_subdivision_images where subdivisionid='.$sid.' and imagename="'.$bse.'" and ext="'.$ext.'"';
		  $res=mysql_query($sql);
		  if (mysql_num_rows($res)==0)
		  {
		    $sql='insert into tb_subdivision_images (subdivisionid,imagename,ext) values ('.$sid.',"'.$bse.'","'.$ext.'")';
			$res=mysql_query($sql);
			$fid=mysql_insert_id();
		  }
		}
		if ($cid!='')
		{
		  $bse = substr($filename, 0,strrpos($filename, '.'));
		  $sql='select * from tb_client_images where clientid='.$cid.' and imagename="'.$bse.'" and ext="'.$ext.'"';
		  $res=mysql_query($sql);
		  if (mysql_num_rows($res)==0)
		  {
		    $sql='insert into tb_client_images (clientid,imagename,ext) values ('.$cid.',"'.$bse.'","'.$ext.'")';
			$res=mysql_query($sql);
			$fid=mysql_insert_id();
		  }
		}
        save_resize_image($newname,600,600,$ext,'_sm1',false);
		list($width, $height) = getimagesize($newname);
		if ($height>114 || $width>121)
		  createThumb($newname,$ext,'_sm4',121,114,$sql,$fid);
        else
          save_resize_image($newname,114,121,$ext,'_sm4',true);
  	  return $err;
    }

 function save_resize_image($file,$height,$width,$ext,$suffix,$output)
 {
   $pth=realpath('.').'\\images';
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
   ImageJpeg ($resized_img,$pth.'\\'.$filename,100);
   ImageDestroy ($resized_img);
   ImageDestroy ($new_img);
}

function createThumb($str_image_src,$ext,$suffix,$int_new_width,$int_new_height,$sql,$fid)
 {

   if($ext == "jpg")
     $res_src_image = imagecreatefromjpeg($str_image_src);
   elseif($ext == "png")
        $res_src_image = imagecreatefrompng($str_image_src);
      elseif($ext == "gif")
            $res_src_image = imagecreatefromgif($str_image_src);

      $str_image_dest=basename($str_image_src);
      $str_image_dest=realpath('.').'\\images\\'.substr($str_image_dest, 0,strrpos($str_image_dest, '.')).$suffix.'.jpg';
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
      global $nooutput;
      if ($nooutput=='' or !isset($nooutput))
      {
	    if (!isset($_SESSION["file_info"]))
		 $_SESSION["file_info"] = array();
   	    ob_end_clean();
 	    ob_start();
	    imagejpeg($res_dst_image);
        $imagevariable = ob_get_contents();
   	    ob_end_clean();
	    $file_id = md5($_FILES["Filedata"]["tmp_name"] + rand()*100000);
   	    $_SESSION["file_info"][$file_id] = $imagevariable;
        echo "FILEID:" . $file_id."\n";	// Return the file id to the script
	    echo "FID:".$fid;
	    return $res_dst_image;
	  }
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

?>
