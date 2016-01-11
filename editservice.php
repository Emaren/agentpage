<?php
include('config.php');

$id=$_GET['id'];
$type=array('Lender','Inspector','Lawyers','Appraisers','Builders');

if ($_POST['submitedit']!='')
{
  $iname=basename($_FILES['userimage']['name']);
  move_uploaded_file($_FILES['userimage']['tmp_name'], 'd:/Websites/myrealtornow.ca/images/'.$iname);
  $lname=basename($_FILES['logoimage']['name']);
  move_uploaded_file($_FILES['logoimage']['tmp_name'], 'd:/Websites/myrealtornow.ca/images/'.$lname);
  if ($id!='')
  {
    $sql='update tb_services set url="'.htmlentities($_POST['url'],ENT_QUOTES).'", phone="'.htmlentities($_POST['phone'],ENT_QUOTES).'", email="'.htmlentities($_POST['email'],ENT_QUOTES).'", name="'.htmlentities($_POST['name'],ENT_QUOTES).'", company="'.htmlentities($_POST['company'],ENT_QUOTES).'",typeid='.$_POST['typeid'];
	if ($iname!='')
	  $sql.=', personimage="'.$iname.'"';
	if ($lname!='')
	  $sql.=', companylogo="'.$lname.'"';
	$sql.=' where id='.$id;
	$res=mysql_query($sql);
	if ($res)
	  $msg='Updated Successfully';
	else
	  $err='Updated failed '.mysql_error();
  }
  else
  {
	   $sql='insert into tb_services (name,typeid,email,company,phone,url';
   	   if ($iname!='')
	     $sql.=', personimage';
	   if ($lname!='')
	     $sql.=', companylogo';
       $sql.=') values ("'.htmlentities($_POST['name'],ENT_QUOTES).'",'.$_POST['typeid'].',"'.htmlentities($_POST['email'],ENT_QUOTES).'", "'.htmlentities($_POST['company'],ENT_QUOTES).'", "'.htmlentities($_POST['phone'],ENT_QUOTES).'", "'.htmlentities($_POST['url'],ENT_QUOTES).'"';
   	   if ($iname!='')
	     $sql.=', "'.$iname.'"';
   	   if ($lname!='')
	     $sql.=', "'.$lname.'"';
       $sql.=')';
  	   $res=mysql_query($sql);
   	   if ($res)
   	     $msg='Inserted Successfully';
 	   else
	     $err='Insertion failed '.mysql_error();
	   $id=mysql_insert_id();
  }
}

if ($id!='')
{
  $sql='select * from tb_services where id='.$id;
  $res=mysql_query($sql);
  $use=mysql_fetch_array($res,MYSQL_ASSOC);
  $txt='Edit Service';
}
else
     $txt='Create Service';

if ($err!='')
  $error_msg=$err;
 if ($msg!=''  && $_GET['idel']=='')
  {
    $_SESSION['ok_msg']=$msg;
    session_write_close();
	header('Location: /services.php');
  }
  else
    if ($msg!='')
      $ok_msg=$msg;

include('template/header.php');

?>


<div class="backend_wrap">
<div style="width: 155px; float:left;">


<table class="hint">
<tr>
<td align="right">
<img src="/images/maphint.png" />
</td>
<td align="left">
* Hint
</td>
</tr>

<tr>
<td colspan="2" align="center">
In the Google Map Window, drag this icon to anywhere on the Map to access the amazing Street View.
</td>
</tr>
</table>
</div>


</div>


<div class="wrapper" style="margin: 20px auto; float:left; background: none;">
<div class="pageheader">
<p><? echo $txt; ?></p>
</div>

<form method="POST"  enctype="multipart/form-data" action="<? echo $_SERVER['PHP_SELF']; if ($id!='') echo '?id='.$id;?>">
<table class="table_lists2" cellpadding="0" cellspacing="0" style="padding: 20px; width: 100%; min-width: 590px;clear:both;">
<tr>
<th width="290">Name</th>
<th width="290">E-mail</th>
<th>Company Name</th>
<th>Type</th>
</tr>

<tr>
<td><input name="name" type="text" class="rounded" value="<? echo post_or_row($use,'name');?>") /></td>
<td><input name="email" type="text" class="rounded" value="<? echo post_or_row($use,'email');?>") /></td>
<td><input name="company" type="text" class="rounded" value="<? echo post_or_row($use,'company');?>") /></td>
<td><select name="typeid" class="rounded" />
<?
  for ($i=0;$i<count($type);$i++)
  {
     $j=$i+1;
     echo '<option value='.$j;
	 if ($j==post_or_row($use,'typeid'))
	   echo ' selected ';
	 echo '>'.$type[$i].'</option>';
  }
?>
</select></td>
  </tr>
  <tr>
    <th>Website url</th>
	<th>Phone Number</th>
  </tr>
  <tr>
   <td><input type="text" style="width:232px;" class="rounded" name="url" value="<? echo post_or_row($use,'url');?>" /></td>
   <td><input type="text" class="rounded" name="phone" value="<? echo post_or_row($use,'phone');?>" /></td>
  </tr>
  <tr>
    <th colspan="2">User image</th>
	<th>Logo image</th>

  </tr>
  <tr>
   <td colspan="2"><input type="file" name="userimage" /></td>
   <td><input type="file" name="logoimage" /></td>
  </tr>
  <tr>
    <td><? if ($use['personimage']!='') echo '<img src="/images/'.$use['personimage'].'" border="0">'; ?></td>
    <td><? if ($use['companylogo']!='') echo '<img src="/images/'.$use['companylogo'].'" border="0">'; ?></td>
  </tr>
<tr>
<td colspan="3">
<input type="submit" name="submitedit" value="Save Service" class="sendinvite" />
<?
  if ($error_msg!='')
     echo '<img src="/images/failed.png" border=0><p style="display:inline" class="fgt_pass">'.$error_msg.'</p><br><br>';
?>
</td>
</tr>
</table>
</form>
<? include('emarentwitter.php'); ?>
</div>
</body>
</html>
<?
  function post_or_row($rw,$field)
  {
    $val='';
	if ($_POST[$field]!='')
	  $val=$_POST[$field];
	else
	  if ($rw[$field]!='')
	    $val=$rw[$field];
	return $val;
  }
?>