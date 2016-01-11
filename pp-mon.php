<?
  include('config.php');

  $txt=print_r($_POST,true);
  $sql='insert into tb_dump (cdate,msg) values ("'.date('Y-m-d H:i:s').'","'.$txt.'")';
  $res=mysql_query($sql);
   if ($_POST['response_order_id']<>'' && $_POST['response_order_id']!='')
   {
     if ($_POST['response_code']<50)
	 {
       $sql='update tb_users set payment_status="y",add_client="y" where id='.str_replace('MRM','',$_POST['response_order_id']);
       $result=mysql_query($sql);
       if ($_SESSION['uid']!='')
         $_SESSION['add_client']='y';
     }
   }

  include('template/header.php');
?>

<html>
<head>
<title></title>
<style type="text/css">
#menu {
min-height: 21px;
}


#login_wrap {
width: 960px;
text-align: center;
margin: 40px auto;
}

#login_wrap img {
clear: both;
}

#login_wrap input[type='text'], #login_wrap input[type='password'] {
background: transparent;
border-top: 1px solid #808080;
border-bottom: 1px solid #fff;
border-right: 1px solid #fff;
border-left: 1px solid #fff;
padding: 0px;
color: #fff;
font-size: 18px;
font-family: "Century Gothic", helvetica, arial, sans-serif;
text-transform: uppercase;
width: 280px;
text-align: center;
-webkit-border-radius: 25px;
-moz-border-radius: 25px;
border-radius: 25px;
}

#login_wrap .county {
cursor: pointer;
cursor: hand;
width: 306px;
height: 256px;
border: none;
z-index: 1;
left: 50px;
position: relative;
}

#login_wrap .city {
cursor: pointer;
cursor: hand;
width: 302px;
height: 295px;
border: none;
position: relative;
left: -46px;
top: -17px;
z-index: 5;
}

#login_wrap .fgt_pass {
font-family: "Century Gothic", helvetica, arial, sans-serif;
font-size: 14px;
color: #A6A6A6;
text-decoration: none;
}

#login_wrap .login_btn {
background: url(images/new_login.png) no-repeat;
width: 104px;
height: 46px;
border: none;
margin-top: 16px;
}

#login_wrap .login_btn:hover {
cursor: pointer;
cursor: hand;
}

</style>
</head>

<body>
<div class="backend_wrap">
<div class="wrapper" style="background:none;">
<div class="pageheader">
<p>Receipt</p>
</div>

<table class="table_lists no_underlines" cellpadding="0" cellspacing="0" style="padding: 20px; width: 100%; min-width: 590px;">
<Tr>
<th width="15%">Amount: </th>
<td width="15%"><? echo $_POST['charge_total'];?></td>

<th width="15%">Transaction Type: </th>
<td width="15%"><? echo $_POST['trans_name'];?></td>

<th width="15%">Date &amp; Time: </th>
<td width="15%"><? echo $_POST['date_stamp'].' '.$_POST['time_stamp'];?></td>

</Tr>

<tr>
<th>Authorisation Code: </th>
<td><? echo $_POST['bank_approval_code'];?></td>

<th>Response Code: </th>
<td><? echo $_POST['response_code'];?></td>

<th>ISO Code: </th>
<td><? echo $_POST['iso_code'];?></td>
</tr>

<tr style="border-bottom: 1px solid #000;">
<th>Reference Number: </th>
<td colspan="3"><? echo $_POST['bank_transaction_id'];?></td>
<td colspan="2"><a href="#" class="style40 style42" onClick="window.print();return false;">Print Receipt </a></td>
</tr>

<tr>
<th colspan="6">Reference Message: </th>
</tr>

<tr style="border-bottom: 1px solid #000;">
<td colspan="6"><div style="border: 1px solid #ccc; padding: 10px; width: 95%; min-height: 100px;"><? echo $_POST['message'];?></div></td>
</tr>

<tr>
<th>Goods &amp; Services Order: </th>
<td colspan="5">Subscription to Myagentnow.ca</td>
</tr>

<tr>
<th>Merchant Name: </th>
<td>Emaren</td>
<th>Merchant URL: </th>
<td>www.myagentnow.ca</td>
<th>Cardholder: </th>
<td><? echo $_POST['cardholder'];?></td>
</tr>

</table>






</div>
</div>
</div>
</body>
</html>
