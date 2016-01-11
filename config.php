<?
   session_start();
  error_reporting(E_NONE);
  $path=realpath('.');
  $web_app_path='/';
  $host='localhost';
  $db='tbrealestate';
  $user='tbrealestate';
  $password='n1k0n42';
  $handle = mysql_connect($host,$user,$password);
  if (!$handle)
    die("Unable to connect to $host with user $user due to ".mysql_error());
  $db2 = mysql_select_db($db,$handle);
  if (!$db2)
    die('Unable to select database '.$db.' due to '.mysql_error());

 // ob_start("ob_gzhandler");
?>
