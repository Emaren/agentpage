<?
  $sql='select *,ts.name as sname,tb_listings.id as lid from tb_user_listings,tb_listings,tb_subdivision ts where userid='.$_SESSION['uid'].' and tb_listings.id=listingid and ts.id=tb_listings.subdivision order by listing_price desc';
  $res=mysql_query($sql);
  $nr=mysql_num_rows($res);
  if ($nr=='')
    $nr=0;
?>  
<div id="fave_grow"></div>
<div id="fave_list" style="display: none; width: 500px;">
<img src="images/close_button.png" style="position: absolute; left: 525px; top: -22px; z-index:
 2000;" id="closebtn4" onclick="hideMyList();" />
<div id="fave_listtest">
<div id="fave_bg">

 
 <h1>My List</h1>
 <hr />
 <input type=hidden id=nl value="<? echo $nr;?>">
 <p id="nolistings" class="home_number" style="margin: 15px 0px 40px 0; text-align: center;"><? echo $nr;?> Listings</p>
 <div id="favelist">
<?
  $jscript="$(document).ready(function() { var latln = new google.maps.LatLng(55.167299,-118.797913);\n var myOptions2 = {\n		zoom: 11,\n		        mapTypeControl: false,\n   center: latln,\n  				mapTypeId: google.maps.MapTypeId.ROADMAP\n    	};";
  $jscript.="  map2 = new google.maps.Map(document.getElementById('fave_map_wrap'), myOptions2);\n";
  while ($row=mysql_fetch_array($res,MYSQL_ASSOC))
  {
    echo '<table class="fave_table" onclick="aj_GetDetail('.$row['lid'].',\'fave\');" cellpadding="0" cellspacing="0" width="95%" style="margin: 10px auto;"><tr>';
	$sql2='select * from tb_listing_images where listingid='.$row['lid'].' order by ordr asc';
	$res2=mysql_query($sql2);
	$row2=mysql_fetch_array($res2,MYSQL_ASSOC);
	echo '<td rowspan="2" style="text-align:left;" width="125"><img src="/images/'.$row2['imagename'].'.'.$row2['ext'].'" style="width: 125px;" /><div style="padding: 5px 0 0 0;"><img style="width: 20px;" src="../images/failed.png" /><a class="del_fav" onclick="return deletefile(event);" href="'.$_SERVER['PHP_SELF'].'?fid='.$row['lid'].'">delete</a></div></td>';
	echo '<td><p class="price">$'.number_format($row['listing_price'],2,'.',',').'</p></td>';
    echo '</tr><tr><td><p>'.html_entity_decode($row['sname'],ENT_QUOTES).'</p></td></tr><tr><td colspan="2"><img src="images/break.jpg" /></td></tr></table>';  
    $jscript.="var latlng".$row['lid']." = new google.maps.LatLng(".$row['lat'].",".$row['long'].");\n";
    $jscript.=" var marker".$row['lid']." = new google.maps.Marker({\n";
	$jscript.="  position: latlng".$row['lid'].", \n     map: map2, title: ''\n	 	});  			";	
  }
  $jscript.="});";
  echo "\n";
?>
<script type="text/javascript">
<? 
  echo $jscript;
?>
  
  function deletefile(e)
  {
    var event = e || window.event;

    ret=confirm('Are you sure you want to delete this favorite?');
    if (!ret)
	{
	  var event = e || window.event; 
	  if (event.stopPropagation) 
		event.stopPropagation();
	  else 
		event.cancelBubble = true;
    }
	return ret;
  }
</script>
</div>
</div>
</div>
</div>