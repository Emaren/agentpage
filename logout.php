<?
    include_once('config.php');

     if (isset($_SESSION['uid']))
     {
         unset($_SESSION['uid']);
         unset($_SESSION['uname']);
         unset($_SESSION['password']);
         unset($_SESSION['firstname']);
         unset($_SESSION['lastname']);
         unset($_SESSION['isadmin']);
		 unset($_SESSION['realtor']);
    }
    header('Location: '.$web_app_path.'login.php');

?>