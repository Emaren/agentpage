<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<link rel="stylesheet" href="style.css?version=1" />
<!--[if IE]>
        <link rel="stylesheet" type="text/css" href="ie-only.css" />
<![endif]-->
</head>

<body>
<div id="header">
<object style="position: absolute; top: -53px; padding: 50px 10px 5px 50px; float:left;" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="325" height="160" wmode="opaque">
        <param name="movie" value="../shimmer_agent.swf" />
        <param name="wmode" value="opaque" />
        <!--[if !IE]>-->
        <object type="application/x-shockwave-flash" data="../shimmer_agent.swf" width="325" height="160" wmode="opaque">
        <!--<![endif]-->
          <img src="images/agentpage.jpg" style="position: absolute; top: -53px; z-index:5;" />
        <!--[if !IE]>-->
        </object>
        <!--<![endif]-->
      </object>
      
            <? if ($_SESSION['isadmin']==0) { ?>

      <a href="http://www.myagentnow.ca/upgrade.php"><img border="0" src="images/freetrial.jpg" style="position:relative; left: 40%; top: 20px;" /></a>
      
      <? }
	  ?>
<?
  if ($_SESSION['uid']!='')
    echo '<p class="logged_in">'.$_SESSION['firstname'].' '.$_SESSION['lastname'].', <span style="font-size: 14px;">Member since September 2011</span></p>';
?>
<!--<a href="#" class="logged_in" style="text-decoration: none; position: relative; top: 30px; left: 230px; margin: 0;">My Settings</a>-->

</div>
<div id="menu">
<? if ($_SESSION['uid']!='') { ?>
<ul>
<? if ($_SESSION['isadmin']!=0) { ?>
<li><a href="rd_users.php">Users</a></li>
<?
   }
   if ($_SESSION['realtor']=='y') {
?>
<li><a href="/rd_users.php">My Clients</a></li>
<? } ?>
<? if ($_SESSION['isadmin']!=0) { ?>
<li><a href="rd_listings.php">Listings</a></li>
<? } ?>
<? if ($_SESSION['realtor']=='y') { ?>
<li><a href="rd_listings.php">My Listings</a></li>
<li><a href="/rd_city/map20a.php">My Map</a></li>
<li><a href="rd_edit_realtor_services.php">My Services</a></li>
<?
  }
  if ($_SESSION['isadmin']==1) {
?>
<li><a href="/rd_subdivisions.php">Subdivisions</a></li>
<? } ?>
<? if ($_SESSION['isadmin']!=0) { ?>
<li><a href="rd_services.php">Services</a></li>
<? } ?>
<li><a href="logout2.php">Logout</a></li>
</ul>
<? } ?>
</div>