<?
$ua = $_SERVER["HTTP_USER_AGENT"];


/* ==== Detect the OS ==== */
// Windows
$win = strpos($ua, 'Windows') ? true : false;
$mac = strpos($ua, 'Macintosh') ? true : false;

/* ============================ */
/* ==== Detect the UA ==== */

// Chrome
$chrome = strpos($ua, 'Chrome') ? true : false; // Google Chrome
$firefox = strpos($ua, 'Firefox') ? true : false;//Firefox
$opera = preg_match("/\bOpera\b/i", $ua); // All Opera
$safari = strpos($ua, 'Safari') ? true : false; // All Safari

/* ============================ */

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<link rel="stylesheet" href="style.css?version=1" />
<!--[if IE]>
        <link rel="stylesheet" type="text/css" href="ie-only.css" />
<![endif]-->
<!--[if IE 9]>
        <link rel="stylesheet" type="text/css" href="ie9-only.css" />
<![endif]-->
<?

if ($win && $chrome) {
echo '<link rel="stylesheet" type="text/css" href="chrome-only.css" />';
}

if (($win && $firefox) || ($mac && $firefox) || ($win && $opera) || ($win && $safari)) {
echo '<link rel="stylesheet" type="text/css" href="firefox-ie.css" />';
}
?>



</head>

<body>
<div id="header">
<object style="position: absolute; top: -53px; padding: 50px 10px 5px 50px; float:left;" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="325" height="160" wmode="opaque" bgcolor="#330000">
        <param name="movie" value="../shimmer_agent.swf" />
        <param name="wmode" value="opaque" />
        <param name="bgcolor" value="#330000" />
        <!--[if !IE]>-->
        <object type="application/x-shockwave-flash" data="../shimmer_agent.swf" width="325" height="160" wmode="transparent"  bgcolor="#330000">
        <!--<![endif]-->
          <img src="images/agentpage.jpg" style="position: absolute; top: -53px; z-index:5;" />
        <!--[if !IE]>-->
        </object>
        <!--<![endif]-->
      </object>

      <? if ($_SESSION['isadmin']==0)
         {
           $sql='select * from tb_users where id='.$_SESSION['uid'];
           $res=mysql_query($sql);
           $user=mysql_fetch_array($res,MYSQL_ASSOC);
           if ($user['payment_status']=='n')
           {
      ?>

      <a href="/register.php"><img border="0" src="images/freetrial.jpg" style="position:relative; left: 40%; top: 20px;" /></a>

      <?   }
	     }
	  ?>
<?
  if ($_SESSION['uid']!='')
    echo '<p class="logged_in">'.$_SESSION['firstname'].' '.$_SESSION['lastname'].', <span style="font-size: 14px;">Member since '.date('F Y',strtotime($_SESSION['cdate'])).'</span></p>';
?>
<!--<a href="#" class="logged_in" style="text-decoration: none; position: relative; top: 30px; left: 230px; margin: 0;">My Settings</a>-->

</div>
<div id="menu">
<? if ($_SESSION['uid']!='') { ?>
<ul>
<? if ($_SESSION['isadmin']!=0) { ?>
<li><a href="users.php">Users</a></li>
<?
   }
   if ($_SESSION['realtor']=='y') {
?>
<li><a href="/users.php">My Clients</a></li>
<? } ?>
<? if ($_SESSION['isadmin']!=0) { ?>
<li><a href="listings.php">Listings</a></li>
<? } ?>
<? if ($_SESSION['realtor']=='y') { ?>
<li><a href="listings.php">My Listings</a></li>
<li><a href="/GP_city7/map20a.php">My Map</a></li>
<li><a href="edit_realtor_services.php">My Services</a></li>
<?
  $sql='select * from tb_users where id='.$_SESSION['uid'];
  $res=mysql_query($sql);
  $user=mysql_fetch_array($res,MYSQL_ASSOC);
  if ($user['payment_status']=='n' and $user['add_client']=='n')
    echo '<li><a href="/upgrade.php">Upgrade</a></li>';
  }
  else
    if ($_SESSION['isadmin']==0)
      echo '<li><a href="/GP_city7/map20a.php">My Map</a></li>	';
  if ($_SESSION['isadmin']==1) {
?>
<li><a href="/subdivisions.php">Subdivisions</a></li>
<? } ?>
<? if ($_SESSION['isadmin']!=0) { ?>
<li><a href="services.php">Services</a></li>
<? } ?>
<li><a href="logout.php">Logout</a></li>
</ul>
<? } ?>
</div>