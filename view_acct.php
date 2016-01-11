<?
  include('config.php');
  if ($_SESSION['uid']=='')
    header('Location: login.php');
  if ($_SESSION['realtor']=='n' && $_SESSION['isadmin']==0)
    header('Location: login.php');

  include('template/header.php');
?>

<div class="wrapper" style="background: none;">
<div class="pageheader">
<p>View Account</p>
</div>
<table class="table_lists" cellpadding="0" cellspacing="0" style="padding: 20px; width: 100%; min-width: 590px;">

<tr>
<th>Date of Joining</th>
<th>Last Payment</th>
<th>Next Payment</th>
</tr>

<tr>
<td style="border: none;">10-10-2011</td>
<td style="border: none;">10-10-2011</td>
<td style="border: none;">10-10-2011</td>
</tr>

<tr>
<td style="border: none;" colspan="3"><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_subscr-find&alias=<? echo $_SESSION['email'];?>" class="brown_btn" style="padding:7px 38px;">Cancel</a></td>
</tr>

</table>
<? include('emarentwitter.php'); ?>
</div>
<div style="clear: both;">&nbsp;</div>




</div>

</body>
</html>
