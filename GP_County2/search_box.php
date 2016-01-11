<div id="search_grow"></div>
<div style="width: 500px;display: none;" id="search">
<img src="images/close_button.png" style="position: absolute; left: -22px; top: -22px; z-index:
 2000;" id="closebtn3" onclick="hideSearch();" />
 <script type="text/javascript">

 function set_listingtype(tpe)
 {
   if (tpe=='Residential')
   {
     document.getElementById('bedst').innerHTML='Beds';
     document.getElementById('bathst').innerHTML='Baths';
     document.getElementById('purchase_lease').style.display='none';
     document.getElementById('purchase_lease2').style.display='none';
	 document.getElementById('sizefrom').style.display='none';
	 document.getElementById('sizeto').style.display='none';
	 document.getElementById('acresfrom').style.display='none';
	 document.getElementById('acresto').style.display='none';
	 document.getElementById('bedssearch').style.display='block';
	 document.getElementById('bathsearch').style.display='block';
   }
   else
   {
     document.getElementById('bedst').innerHTML='<p class="a_from" style="margin-right: 5px;">Size From</p><p class="a_to">Size To</p>';
     document.getElementById('bathst').innerHTML='<p class="a_from" style="margin-right: 5px;">Acres From</p><p class="a_to">Acres To</p>';
     document.getElementById('purchase_lease').style.display='table-row';
     document.getElementById('purchase_lease2').style.display='table-row';
	 document.getElementById('sizefrom').style.display='inline';
	 document.getElementById('sizeto').style.display='inline';
	 document.getElementById('acresfrom').style.display='inline';
	 document.getElementById('acresto').style.display='inline';
	 document.getElementById('bedssearch').style.display='none';
	 document.getElementById('bathsearch').style.display='none';
   }
 }

 function set_purchasetype()
 {
   var pt=document.searching.purchasetype;
   for (i=0;i<pt.length;i++)
   {
     if (pt[i].checked==true)
	 {
       if (pt[i].value=='Lease')
       {
	     document.getElementById('pricesearch_from').style.display='none';
	     document.getElementById('pricesearch_to').style.display='none';
	     document.getElementById('leasepricesearch_from').style.display='block';
	     document.getElementById('leasepricesearch_to').style.display='block';
       }
       else
       {
	     document.getElementById('pricesearch_from').style.display='block';
	     document.getElementById('pricesearch_to').style.display='block';
	     document.getElementById('leasepricesearch_from').style.display='none';
	     document.getElementById('leasepricesearch_to').style.display='none';
       }
     }
   }
 }
 </script>
<div id="searchtest">
<h1>My Search</h1>

<hr />
<form name="searching" onsubmit="return false;" style="margin-top: 50px;">
<table class="search_table" cellpadding="0" cellspacing="0" width="95%" style="margin: 10px auto 0 auto;">
<tr>
    <td colspan="2" style="font-size: 20px;">Search listings for</td>
</tr>
<tr>
   <td><input type=radio name="listingtype" id="listingtype" checked onchange="set_listingtype(this.value);" value="Residential">&nbsp;Residential</td>
   <td><input type=radio name="listingtype" id="listingtype" onchange="set_listingtype(this.value);" value="Commercial">&nbsp;Commercial</td>
<tr>
<tr id="purchase_lease" style="display:none">
    <td colspan="2">
	  Purchase or Lease
	</td>
  </tr>
  <tr id="purchase_lease2" style="display:none">
   <td><input type=radio name="purchasetype" id="purchasetype" onchange="set_purchasetype();" value="Purchase">&nbsp;Purchase</td>
   <td><input type=radio name="purchasetype" id="purchasetype" onchange="set_purchasetype();" value="Lease">&nbsp;Lease</td>
  </tr>
    <td>Price From</td>
    <td>Price To</td>
  </tr>
  <tr>
    <td><select style="display:block" id="pricesearch_from" name="pricesearch_from">
    <option value="0">$0</option>
    <option value="50">$50,000</option>
    <option value="100">$100,000</option>
    <option value="150">$150,000</option>
    <option value="200">$200,000</option>
    <option value="250">$250,000</option>
    <option value="300">$300,000</option>
    <option value="350">$350,000</option>
    <option value="400">$400,000</option>
    <option value="450">$450,000</option>
    <option value="500">$500,000</option>
    <option value="600">$600,000</option>
    <option value="700">$700,000</option>
    <option value="800">$800,000</option>
    <option value="900">$900,000</option>
    <option value="1000">$1,000,000+</option>
    </select>
    <select style="display:none" id="leasepricesearch_from" name="leasepricesearch_from">
    <option value="100">$100</option>
    <option value="200">$200</option>
    <option value="300">$300</option>
    <option value="400">$400</option>
    <option value="500">$500</option>
    <option value="600">$600</option>
    <option value="700">$700</option>
    <option value="800">$800</option>
    <option value="900">$900</option>
    <option value="1000">$1000</option>
    <option value="2000">$2000</option>
    <option value="3000">$3000</option>
    <option value="4000">$4000</option>
    <option value="5000">$5000</option>
    <option value="6000">$6000</option>
    <option value="7000">$7000</option>
    <option value="8000">$8000</option>
    <option value="9000">$9000</option>
    <option value="10000">$1000</option>
    </select>
	</td>
    <td><select style="display:block"  id="pricesearch_to" name="pricesearch_to">
    <option value="0">$0</option>
    <option value="50">$50,000</option>
    <option value="100">$100,000</option>
    <option value="150">$150,000</option>
    <option value="200">$200,000</option>
    <option value="250">$250,000</option>
    <option value="300">$300,000</option>
    <option value="350">$350,000</option>
    <option value="400">$400,000</option>
    <option value="450">$450,000</option>
    <option value="500">$500,000</option>
    <option value="600">$600,000</option>
    <option value="700">$700,000</option>
    <option value="800">$800,000</option>
    <option value="900">$900,000</option>
    <option value="1000">$1,000,000+</option>
    </select>
    <select style="display:none" id="leasepricesearch_to" name="leasepricesearch_to">
    <option value="100">$100</option>
    <option value="200">$200</option>
    <option value="300">$300</option>
    <option value="400">$400</option>
    <option value="500">$500</option>
    <option value="600">$600</option>
    <option value="700">$700</option>
    <option value="800">$800</option>
    <option value="900">$900</option>
    <option value="1000">$1000</option>
    <option value="2000">$2000</option>
    <option value="3000">$3000</option>
    <option value="4000">$4000</option>
    <option value="5000">$5000</option>
    <option value="6000">$6000</option>
    <option value="7000">$7000</option>
    <option value="8000">$8000</option>
    <option value="9000">$9000</option>
    <option value="10000">$1000</option>
    </select>
	</td>
  </tr>
  <tr>
    <td id="bedst" width="45%">Beds</td>
    <td width="45%">Age</td>
  </tr>
  <tr>
    <td><select style="width: 98%;" id="bedssearch" name="bedssearch">
    	<option value="any">Any</option>
    	<option value="1">1+</option>
        <option value="2">2+</option>
        <option value="3">3+</option>
        <option value="4">4+</option>
        </select>
		<select name="sizefrom" id="sizefrom" style="display:none;width:48%;">
    	<option value="1">1 sqft</option>
    	<option value="500">500 sqft</option>
    	<option value="1000">1000 sqft</option>
    	<option value="2000">2000 sqft</option>
    	<option value="3000">3000 sqft</option>
    	<option value="4000">4000 sqft</option>
    	<option value="5000">5000 sqft</option>
    	<option value="6000">6000 sqft</option>
    	<option value="7000">7000 sqft</option>
    	<option value="8000">8000 sqft</option>
    	<option value="9000">9000 sqft</option>
    	<option value="10000">10000 sqft</option>
        </select>
		<select name="sizeto" id="sizeto" style="display:none;width:48%;">
    	<option value="500">500 sqft</option>
    	<option value="1000">1000 sqft</option>
    	<option value="2000">2000 sqft</option>
    	<option value="3000">3000 sqft</option>
    	<option value="4000">4000 sqft</option>
    	<option value="5000">5000 sqft</option>
    	<option value="6000">6000 sqft</option>
    	<option value="7000">7000 sqft</option>
    	<option value="8000">8000 sqft</option>
    	<option value="9000">9000 sqft</option>
    	<option value="10000">10000+ sqft</option>
        </select>
    </td>
    <td><select style="width: 98%;" id="agesearch" name="agesearch">
        <option value="any">Any</option>
    	<option value="0-5">0 - 5</option>
        <option value="5-10">5 - 10</option>
        <option value="10-20">10 - 20</option>
        <option value="20-30">20 - 30</option>
        <option value="30-40">30 - 40</option>
        <option value="40">40+</option>
        </select>
    </td>
  </tr>
  <tr>
    <td id="bathst">Baths</td>
    <td>Garage</td>
  </tr>
  <tr>
    <td><select style="width: 98%;" id="bathsearch" name="bathsearch">
        <option value="any">Any</option>
    	<option value="1">1+</option>
        <option value="2">2+</option>
        <option value="3">3+</option>
        <option value="4">4+</option>
        </select>
		<select name="acresfrom" id="acresfrom" style="display:none;width:48%;">
    	<option value="1">1 acre</option>
    	<option value="2">2 acres</option>
    	<option value="3">3 acres</option>
    	<option value="4">4 acres</option>
    	<option value="5">5 acres</option>
    	<option value="6">6 acres</option>
    	<option value="7">7 acres</option>
    	<option value="8">8 acres</option>
    	<option value="9">9 acres</option>
    	<option value="10">10 acres</option>
        </select>
		<select name="acresto" id="acresto" style="display:none;width:48%;">
    	<option value="2">2 acres</option>
    	<option value="3">3 acres</option>
    	<option value="4">4 acres</option>
    	<option value="5">5 acres</option>
    	<option value="6">6 acres</option>
    	<option value="7">7 acres</option>
    	<option value="8">8 acres</option>
    	<option value="9">9 acres</option>
    	<option value="10">10+ acres</option>
        </select>

		</td>
    <td><input type="radio" id="garagesearch" name="garagesearch" value="yes"> Yes &nbsp; <input id="garagesearch" type="radio" name="garagesearch" value="no" /> No</td>
  </tr>
  <tr>
    <td colspan="2">Subdivision</td>
  </tr>
  <tr>
    <td colspan="2"><select id="subdivsearch" multiple name="subdivsearch" style="height: 138px;"><option value="0">All</option>
<?
   global $municipalityid,$county;

   if ($county=='y')
     $sql='select tb_subdivision.* from tb_subdivision,tb_municipality tm where tm.id=municipalityid and county_parent='.$municipalityid.' and county="'.$county.'"';
   else
     $sql='select * from tb_subdivision where municipalityid='.$municipalityid;
   $res=mysql_query($sql);
   while ($row=mysql_fetch_array($res,MYSQL_ASSOC))
     echo '<option value='.$row['id'].'>'.$row['name'].'</option>';
?>
	</select></td>
  </tr>

    <tr>
    <td colspan="2" align="left" style="text-align: left;"><span style="display: block; float: left; margin-right: 10px; padding-top: 10px;">MLS#</span> <input type="text" name="mlsnum" value="" id="mlsnum" style="padding: 6px 10px; width: 90px; margin-right: 20px; font-size: 14px;" /><input type="submit" value=" " onclick="callSearch();" name="searchsubmit" style="background: url(images/searchbtn.png) no-repeat; width: 133px; height: 39px; border: none;" /></td>
  </tr>


</table>
</form>
<div style="clear: both;">&nbsp;</div>
<script type="text/javascript">

function callSearch()
{
  ltype='';
  ptype='';
  grage='';
  var selection = document.searching.listingtype;
  for (i=0; i<selection.length; i++)
  {
    if (selection[i].checked == true)
	  ltype=selection[i].value;
  }
  selection = document.searching.garagesearch;
  for (i=0; i<selection.length; i++)
  {
    if (selection[i].checked == true)
	  grage=selection[i].value;
  }
  selection = document.searching.purchasetype;
  for (i=0; i<selection.length; i++)
  {
    if (selection[i].checked == true)
	  ptype=selection[i].value;
  }
  bds=document.getElementById('bedssearch').value;
  bths=document.getElementById('bathsearch').value;
  sizefrom=document.getElementById('sizefrom').value;
  sizeto=document.getElementById('sizeto').value;
  acresfrom=document.getElementById('acresfrom').value;
  acresto=document.getElementById('acresto').value;
  ge=document.getElementById('agesearch').value;
  prce_from=document.getElementById('pricesearch_from').value;
  prce_to=document.getElementById('pricesearch_to').value;
  leaseprice_from=document.getElementById('leasepricesearch_from').value;
  leaseprice_to=document.getElementById('leasepricesearch_to').value;
  listingno=document.getElementById('mlsnum').value;
  var sdivision = '';
  var division=document.getElementById('subdivsearch');
  for (var intLoop=0; intLoop < division.options.length; intLoop++)
  {
         if (division.options[intLoop].selected)
		 {
		    if (sdivision!='')
			  sdivision=sdivision+'-';
            sdivision=sdivision+division.options[intLoop].value;
         }
  }
  if (prce_from>prce_to)
    alert('The from price must be less then the to price');
  else
    aj_doSearch(bds,bths,ge,grage,prce_from,prce_to,sdivision,ltype,ptype,sizefrom,sizeto,acresfrom,acresto,leaseprice_from,leaseprice_to,listingno);
  return false;
}

function dumpObj(arr,level) {
var dumped_text = "";
if(!level) level = 0;

//The padding given at the beginning of the line.
var level_padding = "";
for(var j=0;j<level+1;j++) level_padding += "    ";

if(typeof(arr) == 'object') { //Array/Hashes/Objects
 for(var item in arr) {
  var value = arr[item];

  if(typeof(value) == 'object') { //If it is an array,
   dumped_text += level_padding + "'" + item + "' ...\n";
   dumped_text += dump(value,level+1);
  } else {
   dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
  }
 }
} else { //Stings/Chars/Numbers etc.
 dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
}
return dumped_text;
}
</script>
</div>
</div>