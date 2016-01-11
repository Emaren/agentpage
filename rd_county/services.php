<?
  $sql='select * from tb_users where id='.$_SESSION['uid'];
  $res=mysql_query($sql);
  $client=mysql_fetch_array($res,MYSQL_ASSOC);

  $realtorid=$client['realtorid'];
  if ($client['realtor']=='y')
    $realtorid=$_SESSION['uid'];

  $sql='select * from tb_users where id='.$realtorid;
  $res=mysql_query($sql);
  $realtor=mysql_fetch_array($res,MYSQL_ASSOC);

  $html=array();
  $sql='select * from tb_realtor_services,tb_services where userid='.$realtorid.' and tb_services.id=serviceid';
  $res=mysql_query($sql);
  $highest=1;
  while ($row=mysql_fetch_array($res,MYSQL_ASSOC))
  {
     if ($row['personimage']!='')
	    $imagename=$row['personimage'];
	  else
	    $imagename='noman.jpg';
	  if ($row['companylogo']!='')
	    $companyimage=$row['companylogo'];
	  else
	    $companyimage='noman.jpg';
	  $txt='<table class="services_link" cellpadding="0" cellspacing="0" width="100%"';
	  if ($row['url']!='')
	    $txt.=' onclick="window.open(\''.$row['url'].'\',\'_blank\');"; ';
	  $txt.='>';
	  $txt.='<tr><td><img src="/images/'.$imagename.'" class="serv_icon" /></td>';
	  $txt.='<td class="serv_name">'.$row['name'];
	  $txt.='</span></td></tr><tr><td colspan="2">';
	  $txt.='<img class="serv_icon" src="/images/'.$companyimage.'" />';
	  $txt.='</td></tr></table></div>';
	  $m=$row['typeid']-1;
      $html[$m][]=$txt;
	  if ((count($hmtl[$row['typeid']]))>$highest)
	    $highest=count($hmtl[$row['typeid']]);
  }
//  print_r($html);
//  die();
?>

<div id="services_grow"></div>


<div id="services_contain">

<img src="images/close_button.png" style="position: absolute; left: -22px; top: -22px; z-index:
 6000;" id="closebtn3c" onclick="hideservices();" />

<div style="display:none" id="services">

<!--START-->
<table cellpadding="0" cellspacing="0" width="100%" height="130px" style="margin-bottom: 20px;">
<tr>
<td align="left" style="border: none;"><img src="images/group_icon.png" /></td>
<td align="center" width="60%" style="border: none;">
<p class="title">My Services</p>
<p class="recc_by">Recommended by <? echo $realtor['firstname'];?></p>
</td>
<td style="border: none;" align="right"><img src="images/group_icon.png" /></td>
</tr>
</table>

<table cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 20px; min-height: 250px;">

<tr>
<th><p>Lenders</p></th>
<th><p>Inspectors</p></th>
<th><p>Lawyers</p></th>
<th><p>Appraisers</p></th>
<th style=""><p>Builders</p></th>
</tr>

<tr>
<?
    $col=0;
	$row=0;
	for ($i=0;$i<5;$i++)
	{
	  echo '<td valign="top"><div class="scrollable">';
	  for ($j=0;$j<=$highest+1;$j++)
	  {
	    if ($j==($highest-1))
	    {
	  	  $st='border-bottom:none;';
		  $st2='style="border-bottom:none;"';
	    }
	    if (isset($html[$i][$j]))
	    {
	      if ($i!=4)
  	        echo '<div class="serv_break" '.$st2.'>';
	      else
		   echo '<div class="serv_break" style="border-right:none;'.$st.'">';
	      echo $html[$i][$j];
        }
	    else
	    {
		  if ($i!=4)
  	        echo '<div class="serv_break" '.$st2.'>';
		  else
		    echo '<div class="serv_break" style="border-right:none;'.$st.'">';
		  echo '<table cellpadding="0" cellspacing="0" width="100%">';
	      echo '<tr><td>&nbsp;</td>';
		  echo '</td></tr><tr><td colspan="2"></td></tr></table></div>';
	   }
	   if ($i==5)
	   {
	     $col=0;
  	     $row++;
	   }

	 }
	 echo '</div></td>';
   }
?>

<!-- <table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td><img src="images/thumb.jpg" width="115" height="92" /></td>
<td class="serv_name">Tina Jennings</td>
</tr>
<tr>
<td colspan="2"><img src="images/rbc_logo.png" /></td>
</tr>
</table>

</td>
<td>

<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td><img src="images/thumb.jpg" width="115" height="92" /></td>
<td class="serv_name">Tina Jennings</td>
</tr>
<tr>
<td colspan="2"><img src="images/rbc_logo.png" /></td>
</tr>
</table>

</td>
<td>

<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td><img src="images/thumb.jpg" width="115" height="92" /></td>
<td class="serv_name">Tina Jennings</td>
</tr>
<tr>
<td colspan="2"><img src="images/rbc_logo.png" /></td>
</tr>
</table>

</td>
<td style="border-right: none;">

<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td><img src="images/thumb.jpg" width="115" height="92" /></td>
<td class="serv_name">Tina Jennings</td>
</tr>
<tr>
<td colspan="2"><img src="images/rbc_logo.png" /></td>
</tr>
</table>

</td>
</tr>
<tr>
<td style="border-top: ">

<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td><img src="images/thumb.jpg" width="115" height="92" /></td>
<td class="serv_name">Tina Jennings</td>
</tr>
<tr>
<td colspan="2"><img src="images/rbc_logo.png" /></td>
</tr>
</table>

</td>
<td>

<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td><img src="images/thumb.jpg" width="115" height="92" /></td>
<td class="serv_name">Tina Jennings</td>
</tr>
<tr>
<td colspan="2"><img src="images/rbc_logo.png" /></td>
</tr>
</table>

</td>
<td>

<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td><img src="images/thumb.jpg" width="115" height="92" /></td>
<td class="serv_name">Tina Jennings</td>
</tr>
<tr>
<td colspan="2"><img src="images/rbc_logo.png" /></td>
</tr>
</table>

</td>
<td>

<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td><img src="images/thumb.jpg" width="115" height="92" /></td>
<td class="serv_name">Tina Jennings</td>
</tr>
<tr>
<td colspan="2"><img src="images/rbc_logo.png" /></td>
</tr>
</table>

</td>
<td style="border-right: none;">

<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td><img src="images/thumb.jpg" width="115" height="92" /></td>
<td class="serv_name">Tina Jennings</td>
</tr>
<tr>
<td colspan="2"><img src="images/rbc_logo.png" /></td>
</tr>
</table> -->

</td>
</tr>
</table>


<!--END-->
</div>
</div>
