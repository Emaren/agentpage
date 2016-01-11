<?  
    include('config.php');
	include('template/header.php');
    echo '<script type="text/javascript" src="ajax.js"></script>';
	
   if ($_POST['post'])
   {
     $sql='select *,ts.name as sname,tb_listings.id as lid from tb_listings,tb_subdivision ts where ';
     $sql.=' (full_baths+half_baths)>='.$_POST['bathsearch'];
     $sql.=' and (beds_up+beds_down)>='.$_POST['bedssearch'];
	 if ($_POST['garagesearch']=='yes')
        $sql.=' and garage_typeid>0';
     else
	   $sql.=' and garage_typeid=0';
	 if ($_POST['subdivsearch']!=0)		
	   $sql.=' and subdivision='.$_POST['subdivsearch'];
     $price=breakup($_POST['pricesearch']);
     if ($price[0]=='')
	 {
       $price[1]=$price[1]*1000;
	   $sql.=' and listing_price>='.$price[1];	 
	 }
	 else
	 {
       $price[1]=$price[1]*1000;
       $price[0]=$price[0]*1000;	   	   
	   $sql.=' and listing_price between '.$price[0].' and '.$price[1];
     }
	 $age=breakup($_POST['agesearch']);
     if ($age[0]=='')
	   $sql.=' and building_age>='.$age[1];
	 else
	   $sql.=' and building_age between '.$age[0].' and '.$age[1];
     if ($_POST['sizeone']=='')
	   $sql.=' and square_footage<='.$_POST['sizetwo'];
	 else
	   $sql.=' and square_footage between '.$_POST['sizeone'].' and '.$_POST['sizetwo'];	     
     $sql.=' and subdivision=ts.id';
     $res=mysql_query($sql);
   }
?>

<div class="wrapper" style="background: none;">
<div class="pageheader">
<p>Search Results For: </p>
</div>
<table class="table_lists" cellpadding="0" cellspacing="0" style="padding: 20px; width: 100%; min-width: 590px;">

<tr>
<th></th>
<th>Address</th>
<th>Price</th>
<th>Size</th>
<th>Age</th>
<th>Beds</th>
<th>Baths</th>
<th>Garage</th>
<th>Subdivision</th>
<th></th>
</tr>
<?
  $garagetype=array('zzz','Attached','Detatched','Single','Double','1.5');
  while ($row=mysql_fetch_array($res,MYSQL_ASSOC))
  {
    $nobeds=$row['beds_up']+$row['beds_down'];
	$nobaths=$row['full_baths']+$row['half_baths'];
	$sql2='select * from tb_listing_images where listingid='.$row['lid'].' and ordr=0';
	$res2=mysql_query($sql2);
	$row2=mysql_fetch_array($res2,MYSQL_ASSOC);
    echo '<tr><td><img src="/images/'.$row2['imagename'].'_sm4.'.$row2['ext'].'" /></td><td>'.$row['building_no'].' '.$row['street_name'].'</td><td>$'.number_format($row['listing_price'],0,'.',',').'</td><td>'.$row['square_footage'];
	echo ' Sq. Ft.</td><td>'.$row['building_age'].' years</td><td>'.$nobeds.'</td><td>'.$nobaths.'</td><td>'.$garagetype[$_POST['garagesearch']].'</td><td>'.$row['sname'].'</td><td><a href="javascript:addtofavories('.$row['lid'].');">Add to Favourites</a></td></tr>';  
  }
?>
</table>

</div>

</body>
</html>
<?
  function breakup($str)
  {
     $ps=strpos($str,'-');
	 $frst=substr($str,0,$ps);
	 $ps++;
	 $scnd=substr($str,$ps);
	 return array($frst,$scnd);  
  }