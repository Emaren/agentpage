<?
  include('config.php');
  if ($_SESSION['uid']=='')
    header('Location: login.php');
  if ($_SESSION['realtor']=='n' && $_SESSION['isadmin']==0)
    header('Location: login.php');
	
  include('template/header.php');
  
  $old_error_handler = set_error_handler("myErrorHandler");
?>
<div class="backend_wrap">
<div style="width: 135px; float:left;">
<a href="/edituser.php" style="float:left;margin-top:20px;" <? if ($_SESSION['realtor']=='y') echo ' class="brown_btn addclient"'; else echo ' class="brown_btn addrealtor"'; ?> ><? if ($_SESSION['realtor']=='y') echo 'Add Client'; else echo 'Add Realtor'; ?></a>
<a style="float:left;margin-top:0px;max-width: 133px; display: block; min-width: 133px;" href="#" class="brown_btn traffic" >View Traffic</a>
<a style="float:left;margin-top:0px;max-width: 133px; display: block; min-width: 133px;" href="/view-acct.php" class="brown_btn viewaccount" >View Account</a>
</div>
<div class="wrapper" style="background: none;">
<div class="pageheader">
<p>Traffic</p>
</div>

<div class="table_lists">
<div style="padding: 10px;">
<script type="text/javascript" src="<? echo $web_app_path;?>js/json/json2.js"></script>
<script type="text/javascript" src="<? echo $web_app_path;?>js/swfobject.js"></script>
<script type="text/javascript">

swfobject.embedSWF(
"open-flash-chart.swf", "my_chart",
"900", "400", "9.0.0", "expressInstall.swf",
{"data-file":"trafficdata.php?uid=<? echo $_SESSION['uid'];?>"} );

</script>
<div id="my_chart"></div>
</div>
<div style="clear: both;">&nbsp;</div>
</div>
</div>
</div>

</body>
</html>
<?

function myErrorHandler($errno, $errstr, $errfile, $errline)
{
    switch ($errno) {
    case E_USER_ERROR:
        echo "<b>My ERROR</b> [$errno] $errstr<br />\n";
        echo "  Fatal error on line $errline in file $errfile";
        echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
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
        break;
    }

    /* Don't execute PHP internal error handler */
    return true;
}
?>