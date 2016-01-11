<?
   include('../config.php');
   $municipalityid=1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<link rel="stylesheet" href="style.css" />
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="/ajax.js"></script>
<script type="text/javascript">
//Larger thumbnail preview
	originalWidth = new Array();
	originalHeight = new Array();
	originalzIndex = new Array();
	pos = new Array();
	function mover(imgID)
	{
    if (typeof img !='undefined' && img!='')
	{
	  if(originalHeight[img.id] != img.width() || originalWidth[img.id] != img.height())
	  {
	    img.width=originalWidth[img.id];
	    img.height=originalHeight[img.id];
	   // img.css({"zIndex" : originalzIndex[img.id]});
      }
	}
	img = $(imgID);
    if (typeof originalWidth[imgID]=='undefined')
	{
	  originalWidth[imgID] = img.width()*1.5;
	  originalHeight[imgID] = img.height()*1.5;
	  pos[imgID] = img.offset();
	  originalzIndex[imgID] = img.css('zIndex');
	}
 	  $(imgID).css({'z-index' : '10'});
	  $(imgID).addClass("hover").stop()
	  	  .animate({
			/*marginTop: '-110px',
			marginLeft: '-110px',  */
			top: pos[imgID].top-22+'px',
			left: pos[imgID].left,
			width: originalWidth[imgID]+'px',
			height:originalHeight[imgID]+'px'
		  }, 200);
	}
	function mout(imgID)
	{
	originalz = originalzIndex[imgID];
//	i = document.getElementById(imgID.substr(1))
//	setTimeout(function () {i.style.zIndex = originalz}, 250);

	$(imgID).removeClass("hover").stop()
	  .css('z-index', '2')
		.animate({
			/*marginTop: '0',
			marginLeft: '0', */
			top: pos[imgID].top-22+'px',
			left: pos[imgID].left,
			width: originalWidth[imgID]/1.5,
			height: originalHeight[imgID]/1.5
		}, 400, function() {
		  $(imgID).css('z-index', originalzIndex[imgID]);
    });
	}

	function showDiv(obj,area,id)
	{
	  getdivision(id,obj,area);
	}

	function showDiv2(obj,area)
	{
		document.getElementById(area).onmouseover = '';
		document.getElementById(area).onmouseout = '';
		$(obj).stop();
		name = document.getElementById(obj.substr(1)).id;
		highlight = document.getElementById('highlighted');
		highlight.src = 'images/'+name+'2.png';
		highlight.style.display = 'block';
		$(obj).css({'zIndex' : '40','width' : originalWidth['#'+name]/1.5, 'height' : originalHeight['#'+name]/1.5});
		mapOverlay = document.getElementById('overlay');
		infoDiv = document.getElementById('info_left');
		listDiv = document.getElementById('info_right');
		closeBtn = document.getElementById('closebtn');
		closeBtn2 = document.getElementById('closebtn2');
		infoDiv.style.top = '5%';
		infoDiv.style.left = '0';
		listDiv.style.top = '5%';
		listDiv.style.left = '60%'; 
		listDiv.style.display = 'block'; 
		infoDiv.style.display = 'block';		
/*		$('#info_left').animate({ top: '5%', left: '0',width:'490px'},500);
		$('#info_right').animate({ top: '5%', left: '60%',width:'490px'},500); */		
		mapOverlay.style.display = 'block';
		mapOverlay.style.width = '100%';
		mapOverlay.style.height = '100%';
		closeBtn.onclick = function() { hideDiv(obj,area); };
		closeBtn2.onclick = function() { hideDiv(obj,area); };
		mapOverlay.onclick = function() { hideDiv(obj,area); };
	}

	function hideDiv(obj,area)
	{
		document.getElementById(area).onmouseover = function() { mover(obj); };
		document.getElementById(area).onmouseout = function() { mout(obj); };
		name = document.getElementById(obj.substr(1)).id;
		highlight = document.getElementById('highlighted');
		highlight.style.display = 'none';
		$(obj).css({'top' : pos[obj].top-22+'px',left: pos[obj].left,marginLeft:0,marginTop:0,width: originalWidth[obj]/1.5,height: originalHeight[obj]/1.5,'zIndex' : 1});
		infoDiv = document.getElementById('info_left');
		infoDiv.style.display = 'none';
		listDiv = document.getElementById('info_right');
		listDiv.style.top='0';
		listDiv.style.left='0';
		infoDiv.style.top='0';
		infoDiv.style.left='0';
		listDiv.style.display = 'none';
		mapOverlay = document.getElementById('overlay');
		mapOverlay.style.display = 'none';
	}

	function showSearch() {
	searchBox = document.getElementById('search');
	searchBox.style.display = 'block';
	}

	function hideSearch() {
	searchBox = document.getElementById('search');
	searchBox.style.display = 'none';
	}

	function showMyList() {
	myListBox = document.getElementById('fave_list');
	myListBox.style.display = 'block';
	}

	function hideMyList() {
	myListBox = document.getElementById('fave_list');
	myListBox.style.display = 'none';
	}

</script>

</head>

<body style="background: url(images/blackgrad.jpg) repeat-y scroll 0% 0 #c2c2c2;">
<img src="images/mylist.png" style="width: 25px; position: absolute; top: 20px; left: 96%; z-index:100;" onclick="showMyList();" />
<img src="images/mysearch.png" style="width: 25px; position: absolute; top: 240px; left: 96%; z-index:100;" onclick="showSearch();" />
<div id="gp_contain">
<img src="images/100ave.png" style="position: absolute; top: 324px; left: 525px; z-index: 5;" />
<img src="images/100street.png" style="position: absolute; top: 16px; left: 863px; z-index: 5;" />

<img src="images/92ndave.png" style="position:absolute; top: 383px; left: 995px; z-index: 5;" />
<img src="images/68ave.png" style="position:absolute; top: 559px; left: 918px; z-index: 5;" />
<img src="images/68ave.png" style="position:absolute; top: 559px; left: 784px; z-index: 5;" />
<img src="images/76ave.png" style="position:absolute; top: 498px; left: 907px; z-index: 5;" />
<img src="images/hwy43.png" style="position:absolute; top: 498px; left: 740px; z-index: 5;" />
<img src="images/hwy43.png" style="position:absolute; top: 353px; left: 740px; z-index: 5;" />
<img src="images/116st.png" style="position:absolute; top: 353px; left: 626px; z-index: 5;" />
<img src="images/116st.png" style="position:absolute; top: 252px; left: 626px; z-index: 5;" />


<img src="images/swanavon.png" border="0" usemap="#m1" height="100" width="46"  class="thumb" id="swanavon" style="position:absolute; top: 345px; left: 819px; z-index: 1;" />
<map name="m1" id="m1">
  <area shape="poly" coords="3,1,44,2,45,98,8,97,4,90,13,86,13,77,17,69,12,65,14,59,13,51,7,50,3,51,3,42,11,29,8,22,3,14" id="area1" href="javascript:void(0)" onclick="showDiv('#swanavon','area1',44)"  />
</map>
<img src="images/highland_park.png" border="0" usemap="#m2" height="100" width="74"  class="thumb" id="highland_park" style="position:absolute; top: 345px; left: 873px; z-index: 1;" />
<map name="m2" id="m2">
  <area shape="poly" coords="1,0,1,99,70,98,72,92,69,85,65,74,63,62,59,59,60,45,49,22,32,2" href="javascript:void(0)" id="area2" onclick="showDiv('#highland_park','area2',22)"  />
</map>
<img src="images/mission_heights.png" border="0" usemap="#m3" height="122" width="112" class="thumb" id="mission_heights" style="position:absolute; top: 447px; left: 752px; z-index: 1;" />
<map name="m3" id="m3">
  <area shape="poly" coords="2,2,2,119,97,120,103,115,98,111,102,102,109,105,110,97,108,88,98,89,93,85,99,81,91,78,90,72,94,69,85,67,84,62,88,59,93,53,84,53,79,50,77,45,79,35,82,23,73,17,70,2" id="area3" href="javascript:void(0)" onclick="showDiv('#mission_heights','area3',27)"  />
</map>
<img src="images/patterson_place.png" border="0" usemap="#m4" height="65" width="98"  class="thumb" id="patterson_place" style="position:absolute; top: 444px; left: 872px; z-index: 1;" />
<map name="m4" id="m4">
  <area shape="poly" coords="2,2,2,59,91,59,95,54,90,40,88,33,77,2" id="area4" href="javascript:void(0)" onclick="showDiv('#patterson_place','area4',32)"  />
</map>
<img src="images/south_patterson.png" border="0" usemap="#m5" height="62" width="115"  class="thumb" id="south_patterson" style="position:absolute; top: 507px; left: 872px; z-index: 1;" />
<map name="m5" id="m5">
  <area shape="poly" coords="2,1,2,17,8,27,12,37,12,60,106,60,114,59,114,41,102,11,97,2" id="area5" href="javascript:void(0)" onclick="showDiv('#south_patterson','area5',42)"  />
</map>
<img src="images/southview.png" border="0" usemap="#m6" height="81" width="37"  class="thumb" id="southview" style="position:absolute; top: 445px; left: 825px; z-index: 1;" />
<map name="m6" id="m6">
  <area shape="poly" coords="2,2,35,2,34,79,27,74,26,61,26,54,23,46,16,44,8,41,14,39,18,30,13,27,12,18,8,16,3,11" id="area6" href="javascript:void(0)" onclick="showDiv('#southview','area6',41)"  />
</map>


<img src="images/airport.png" border="0" usemap="#m7" class="thumb" height="163" width="228" id="airport" style="position:absolute; top: 162px; left: 350px; z-index: 1;" />
<map name="m7" id="m7">
  <area shape="poly" coords="4,14,4,59,9,61,59,61,60,153,64,158,219,157,222,152,223,15,217,12,55,13,54,4,30,4,22,13" id="area7" href="javascript:void(0)" onclick="showDiv('#airport','area7',1)" />
</map>
<img src="images/arbour_hills.png" border="0" usemap="#m8" class="thumb" width="168" height="71" id="arbour_hills" style="position:absolute; top: 14px; left: 637px; z-index: 1;" />
<map name="m8" id="m8">
  <area shape="poly" coords="3,5,4,61,160,62,165,57,163,9,156,4" id="area8" href="javascript:void(0)" onclick="showDiv('#arbour_hills','area8',2)" />
</map>
<img src="images/bear_creek_highlands.png" border="0" usemap="#m9" width="115" height="126" class="thumb" id="bear_creek_highlands" style="position:absolute; top: 12px; left: 521px; z-index: 1;" />
<map name="m9" id="m9">
  <area shape="poly" coords="3,4,3,118,110,118,112,112,112,8,104,4" id="area9" href="javascript:void(0)" onclick="showDiv('#bear_creek_highlands','area9',4)" />
</map>
<img src="images/canfor.png" width="82" height="93" border="0" usemap="#m10" class="thumb" id="canfor" style="position:absolute; top: 354px; left: 751px; z-index: 1;" />
<map name="m10" id="m10">
  <area shape="poly" coords="4,9,2,87,7,90,71,90,65,85,67,80,72,78,76,77,75,71,80,65,74,61,77,55,76,48,67,51,64,47,64,35,69,28,73,22,70,16,66,9,65,4,47,4,14,4" id="area10" href="javascript:void(0)" onclick="showDiv('#canfor','area10',60)" />
</map>
<!--<img src="images/carriage_lane.png" border="0" usemap="#m11" class="thumb" id="carriage_lane" style="position:absolute; top: 445px; left: 825px; z-index: 1;" />
<map name="m11" id="m11">
  <area shape="rect" coords="1,3,59,120" href="#" />
</map>-->
<img src="images/cobblestone.png" border="0" usemap="#m12" class="thumb" width="57" height="65" id="cobblestone" style="position:absolute; top: 332px; left: 978px; z-index: 1;" />
<map name="m12" id="m12">
  <area shape="rect" coords="0,1,56,62" id="area12" href="javascript:void(0)" onclick="showDiv('#cobblestone','area12',9)" />
</map>
<img src="images/college_park.png" border="0" usemap="#m13" width="70" height="101" class="thumb" id="college_park" style="position:absolute; top: 259px; left: 752px; z-index: 1;" />
<map name="m13" id="m13">
  <area shape="poly" coords="2,4,2,97,15,92,67,92,64,87,64,76,61,66,58,55,54,45,53,36,60,28,50,22,46,28,40,26,35,23,28,15,17,17,12,17,10,11,8,6" id="area13" href="javascript:void(0)" onclick="showDiv('#college_park','area13',10)"  />
</map>
<img src="images/copperwood.png" border="0" usemap="#m14" class="thumb" width="49" height="57" id="copperwood" style="position:absolute; top: 143px; left: 1036px; z-index: 1;" />
<map name="m14" id="m14">
  <area shape="poly" coords="2,4,2,48,7,49,15,49,24,53,48,54,47,4" id="area14" href="javascript:void(0)" onclick="showDiv('#copperwood','area14',11)" />
</map>
<img src="images/country_club.png" width="105" height="61" border="0" usemap="#m15" class="thumb" id="country_club" style="position:absolute; top: 568px; left: 880px; z-index: 1;" />
<map name="m15" id="m15">
  <area shape="poly" coords="4,4,97,4,103,8,102,52,98,56,31,56,30,47,25,41,18,42,8,33,9,24,13,20,7,18,3,10" id="area15" href="javascript:void(0)" onclick="showDiv('#country_club','area15')" />
</map>

<img src="images/countryside_north.png" width="45" height="65" border="0" usemap="#m16" class="thumb" id="countryside_north" style="position:absolute; top: 510px; left: 989px; z-index: 1;" />
<map name="m16" id="m16">
  <area shape="poly" coords="4,4,16,36,15,60,40,60,42,57,43,3" id="area16" href="javascript:void(0)" onclick="showDiv('#countryside_north','area16',13)" />
</map>
<img src="images/countryside_south.png" height="63" width="41" border="0" usemap="#m17" class="thumb" id="countryside_south" style="position:absolute; top: 574px; left: 993px; z-index: 2;" />
<map name="m17" id="m17">
  <area shape="rect" coords="2,2,39,61" id="area17" href="javascript:void(0)" onclick="showDiv('#countryside_south','area17',14)" />
</map>
<img src="images/creekside.png" border="0" usemap="#m18" height="69" width="29" class="thumb" id="creekside" style="position:absolute; top: 395px; left: 978px; z-index: 2;" />
<map name="m18" id="m18">
  <area shape="poly" coords="2,2,2,47,27,65,27,2" id="area18" href="javascript:void(0)" onclick="showDiv('#creekside','area18',19)" />
</map>
<img src="images/crystal_heights.png" border="0" usemap="#m19" class="thumb" id="crystal_heights" width="52" height="93" style="position:absolute; top: 203px; left: 979px; z-index: 1;" />
<map name="m19" id="m19">
  <area shape="poly" coords="2,3,2,89,9,90,16,89,35,77,40,71,49,71,50,6,41,4" id="area19" href="javascript:void(0)" onclick="showDiv('#crystal_heights','area19',18)" />
</map>
<img src="images/crystal_lake_estates.png" border="0" usemap="#m20" width="93" height="121" class="thumb" id="crystal_lake_estates" style="position:absolute; top: 82px; left: 942px; z-index: 1;" />
<map name="m20" id="m20">
  <area shape="poly" coords="7,34,13,23,20,15,25,12,32,11,45,11,53,12,61,13,68,10,68,4,88,4,90,8,91,113,87,118,3,118,8,107,12,98,16,89,14,77,13,70,13,61,11,52,7,43" id="area20" href="javascript:void(0)" onclick="showDiv('#crystal_lake_estates','area20',17)" />
</map>
<img src="images/crystal_landing.png" border="0" usemap="#m21" class="thumb" width="58" height="40" id="crystal_landing" style="position:absolute; top: 287px; left: 1032px; z-index: 1;" />
<map name="m21" id="m21">
  <area shape="rect" coords="1,2,56,36" id="area21" href="javascript:void(0)" onclick="showDiv('#crystal_landing','area21',15)" />
</map>
<img src="images/crystal_ridge.png" height="85" width="66" border="0" usemap="#m22" class="thumb" id="crystal_ridge" style="position:absolute; top: 118px; left: 888px; z-index: 1;" />
<map name="m22" id="m22">
  <area shape="poly" coords="8,5,3,20,3,79,7,81,51,81,54,73,63,59,63,52,62,41,60,30,59,20,55,10,41,4" id="area22" href="javascript:void(0)" onclick="showDiv('#crystal_ridge','area22',16)" />
</map>
<img src="images/gateway.png" height="63" width="56" border="0" usemap="#m23" class="thumb" id="gateway" style="position:absolute; top: 262px; left: 695px; z-index: 1;" />
<map name="m23" id="m23">
  <area shape="rect" coords="2,2,54,60" id="area23" href="javascript:void(0)" onclick="showDiv('#gateway','area23',20)" />
</map>
<img src="images/grande_banks.png" width="96" height="64" border="0" usemap="#m24" class="thumb" id="grande_banks" style="position:absolute; top: 570px; left: 752px; z-index: 1;" />
<map name="m24" id="m24">
  <area shape="poly" coords="2,2,2,56,7,60,51,59,53,56,57,52,56,47,56,45,60,47,66,44,65,38,68,33,68,29,80,26,74,23,75,19,82,17,82,10,89,5,89,4" id="area24" href="javascript:void(0)" onclick="showDiv('#grande_banks','area24')" />
</map>

<img src="images/hillside.png" border="0" usemap="#m25" height="61" width="87" class="thumb" id="hillside" style="position:absolute; top: 261px; left: 891px; z-index: 1;" />
<map name="m25" id="m25">
  <area shape="poly" coords="2,3,2,16,10,35,22,56,80,55,84,52,85,3" id="area25" href="javascript:void(0)" onclick="showDiv('#hillside','area25',23)" />
</map>
<img src="images/ivy_lake_estates.png" width="52" height="45" border="0" usemap="#m26" class="thumb" id="ivy_lake_estates" style="position:absolute; top: 278px; left: 979px; z-index: 1;" />
<map name="m26" id="m26">
  <area shape="poly" coords="3,20,2,38,5,41,41,41,50,39,50,4,41,3,18,19" id="area26" href="javascript:void(0)" onclick="showDiv('#ivy_lake_estates','area26',25)" />
</map>
<img src="images/lakeland.png" border="0" usemap="#m27" width="80" height="38" class="thumb" id="lakeland" style="position:absolute; top: 79px; left: 928px; z-index: 1;" />
<map name="m27" id="m27">
  <area shape="poly" coords="4,3,76,2,77,8,39,9,24,20,17,32,11,29,6,21,3,15" id="area27" href="javascript:void(0)" onclick="showDiv('#lakeland','area27',26)" />
</map>
<img src="images/mountview.png" border="0" usemap="#m28" class="thumb" id="mountview" width="89" height="59" style="position:absolute; top: 203px; left: 889px; z-index: 1;" />
<map name="m28" id="m28">
  <area shape="rect" coords="1,4,87,56" id="area28" href="javascript:void(0)" onclick="showDiv('#mountview','area28',28)" />
</map>
<img src="images/northgate.png" border="0" usemap="#m29" class="thumb" id="northgate" width="119" height="202" style="position:absolute; top: 5px; left: 805px; z-index: 1;" />
<map name="m29" id="m29">
  <area shape="poly" coords="3,13,45,13,53,4,54,12,118,14,117,27,118,55,117,65,117,73,117,112,88,111,82,128,82,195,62,194,62,73,3,73" id="area29" href="javascript:void(0)" onclick="showDiv('#northgate','area29',29)" />
</map>
<img src="images/obrien_lake.png" border="0" height="64" width="55" usemap="#m30" class="thumb" id="obrien_lake" style="position:absolute; top: 570px; left: 696px; z-index: 1;" />
<map name="m30" id="m30">
  <area shape="rect" coords="2,2,53,60" id="area30" href="javascript:void(0)" onclick="showDiv('#obrien_lake','area30',31)" />
</map>
<img src="images/pinnacle_ridge.png" width="115" height="88" border="0" usemap="#m31" class="thumb" id="pinnacle_ridge" style="position:absolute; top: 491px; left: 635px; z-index: 1;" />
<map name="m31" id="m31">
  <area shape="poly" coords="2,23,2,68,113,67,113,3,64,2,64,25" id="area31" href="javascript:void(0)" onclick="showDiv('#pinnacle_ridge','area31',33)" />
</map>
<img src="images/richmond_industrial.png" height="163" width="112" border="0" usemap="#m32" class="thumb" id="richmond_industrial" style="position:absolute; top: 332px; left: 638px; z-index: 1;" />
<map name="m32" id="m32">
  <area shape="poly" coords="5,2,6,117,42,118,42,133,63,133,63,172,106,172,110,168,110,5,101,2" id="area32" href="javascript:void(0)" onclick="showDiv('#richmond_industrial','area32',35)" />
</map>
<img src="images/riverstone.png" width="59" height="117" border="0" usemap="#m33" class="thumb" id="riverstone" style="position:absolute; top: 395px; left: 976px; z-index: 1;" />
<map name="m33" id="m33">
  <area shape="poly" coords="35,4,34,65,28,70,6,55,3,60,3,82,14,114,58,115,57,3" id="area33" href="javascript:void(0)" onclick="showDiv('#riverstone','area33',36)" />
</map>

<img src="images/signature_falls.png" width="55" height="65" border="0" usemap="#m34" class="thumb" id="signature_falls" style="position:absolute; top: 510px; left: 1035px; z-index: 1;" />
<map name="m34" id="m34">
  <area shape="rect" coords="2,1,54,62" id="area34" href="javascript:void(0)" onclick="showDiv('#signature_falls','area34,39')" />
</map>
<img src="images/smith.png" border="0" width="55" height="65" usemap="#m35" class="thumb" id="smith" style="position:absolute; top: 332px; left: 922px; z-index: 1;" />
<map name="m35" id="m35">
  <area shape="poly" coords="2,2,2,25,27,62,51,61,53,54,53,4" id="area35" href="javascript:void(0)" onclick="showDiv('#smith','area35',40)" />
</map>
<img src="images/summerside.png" border="0" usemap="#m36" class="thumb" width="84" height="125" id="summerside" style="position:absolute; top: 574px; left: 992px; z-index: 1;" />
<map name="m36" id="m36">
  <area shape="poly" coords="46,3,45,65,3,66,3,121,5,120,65,121,73,111,70,105,78,99,76,94,76,88,81,82,82,3" id="area36" href="javascript:void(0)" onclick="showDiv('#summerside','area36',43)" />
</map>
<img src="images/trumpeter_village.png" border="0" usemap="#m37" height="28" width="60" class="thumb" id="trumpeter_village" style="position:absolute; top: 260px; left: 1031px; z-index: 1;" />
<map name="m37" id="m37">
  <area shape="rect" coords="2,2,57,23" id="area37" href="javascript:void(0)" onclick="showDiv('#trumpeter_village','area37')" />
</map>
<img src="images/vision_west.png" width="113" height="60" border="0" usemap="#m38" class="thumb" id="vision_west" style="position:absolute; top: 332px; left: 525px; z-index: 1;" />
<map name="m38" id="m38">
  <area shape="rect" coords="1,2,112,57" id="area38" href="javascript:void(0)" onclick="showDiv('#vision_west','area38',61)" />
</map>
<!--<img src="images/wedgewood.png" border="0" usemap="#m39" class="thumb" id="wedgewood" style="position:absolute; top: 445px; left: 825px; z-index: 1;" />
<map name="m39" id="m39">
  <area shape="poly" coords="13,4,7,11,2,23,4,31,11,33,20,36,27,39,33,45,37,53,43,58,60,59,63,55,64,4,53,4" href="#" />
</map>-->
<img src="images/westgate.png" width="116" height="69" border="0" usemap="#m40" class="thumb" id="westgate" style="position:absolute; top: 261px; left: 579px; z-index: 1;" />
<map name="m40" id="m40">
  <area shape="rect" coords="2,3,115,62" id="area40" href="javascript:void(0)" onclick="showDiv('#westgate','area40',45)" />
</map>
<img src="images/westpointe.png" height="76" width="56" border="0" usemap="#m41" class="thumb" id="westpointe" style="position:absolute; top: 449px; left: 639px; z-index: 1;" />
<map name="m41" id="m41">
  <area shape="poly" coords="2,3,2,73,6,71,54,72,54,19,34,19,34,2" id="area41" href="javascript:void(0)" onclick="showDiv('#westpointe','area41',46)" />
</map>

<img src="images/avondale.png" width="106" height="89" border="0" usemap="#m42" class="thumb" id="avondale" style="position: absolute; top: 203px; left: 759px; z-index: 1;" />
<map name="m42" id="m42">
  <area shape="poly" coords="3,26,10,14,19,8,30,4,91,3,90,60,104,61,105,86,61,85,61,77,54,77,44,71,35,64,34,59,29,62,19,55,13,56,13,48,14,40,11,33,11,29" id="area42" href="javascript:void(0)" onclick="showDiv('#avondale','area42',3)" />
</map>
<img src="images/hidden_valley.png" width="68" height="181" border="0" usemap="#m43" class="thumb" id="hidden_valley" style="position: absolute; top: 85px; left: 695px; z-index: 1;" />
<map name="m43" id="m43">
  <area shape="poly" coords="4,4,3,175,53,175,53,162,54,151,58,137,64,131,62,125,56,97,57,4" id="area43" href="javascript:void(0)" onclick="showDiv('#hidden_valley','area43',21)" />
</map>
<img src="images/royal_oaks.png" width="54" height="136" border="0" usemap="#m44" class="thumb" id="royal_oaks" style="position: absolute; top: 83px; left: 753px; z-index: 1;" />
<map name="m44" id="m44">
  <area shape="poly" coords="4,4,3,96,11,127,28,116,51,116,50,5" id="area44" href="javascript:void(0)" onclick="showDiv('#royal_oaks','area44',37)" />
</map>
<img src="images/northridge.png" width="58" height="124" border="0" usemap="#m45" class="thumb" id="northridge" style="position: absolute; top: 83px; left: 806px; z-index: 1;" />
<map name="m45" id="m45">
  <area shape="poly" coords="2,3,3,119,35,117,44,112,53,105,55,98,55,2" id="area45" href="javascript:void(0)" onclick="showDiv('#northridge','area45',30)" />
</map>
<img src="images/vla.png" width="35" height="92" border="0" usemap="#m47" class="thumb" id="vla" style="position: absolute; top: 199px; left: 853px; z-index: 1;" />
<map name="m47" id="m47">
  <area shape="poly" coords="9,4,2,11,1,57,14,58,15,88,32,88,32,7,14,7" id="area47" href="javascript:void(0)" onclick="showDiv('#vla','area47',50)" />
</map>
<img src="images/central_business_district.png" width="113" height="75" border="0" usemap="#m48" class="thumb" id="central_business_district" style="position: absolute; top: 286px; left: 808px; z-index: 1;" />
<map name="m48" id="m48">
  <area shape="poly" coords="8,5,2,16,13,59,95,58,106,73,112,69,111,42,103,42,86,10,14,9" id="area48" href="javascript:void(0)" onclick="showDiv('#central_business_district','area48',7)" />
</map>
<img src="images/railtown.png" height="72" width="32" border="0" usemap="#m49" class="thumb" id="railtown" style="position:absolute; top: 394px; left: 945px; z-index: 1;" />
<map name="m49" id="m49">
  <area shape="poly" coords="0,1,29,105,46,106,46,3" id="area49" href="javascript:void(0)" onclick="showDiv('#railtown','area49',34)" />
</map>
<img src="images/nothing.png" style="position:absolute; top: 568px; left: 756px;" />
<img src="images/cityofgrandeprairie.png" style="position: absolute; top: 98%; left: 33%;" />
</div>

<MM:BeginLock translatorClass="MM_SSI" type="ssi" orig="%3C?php include(%22subdiv_info.php%22); ?%3E" fileRef="subdiv_info.php" depFiles="file:///C|/inetpub/wwwroot/GP_City/subdiv_info.php"><div id="info_left">
<img src="images/close_button.png" style="position: absolute; left: 95%; top: -20px; z-index:
 200;" id="closebtn" />
<h1 id="divisionname">Crystal Lake Estates</h1>
<hr />
<p id="nohomes" class="home_number">1002 Homes</p>

<img src="images/toppic.png" style="z-index: 100; position:absolute; top:68px;" />

<table cellpadding="0" cellspacing="0" width="490" id="info_table">
<tr>
<td style="border-right: 1px solid #b7b7ba;" width="43%">
<p class="helvtitle">Current Market Price Range</p>
<p id="pricerange" class="helv">$250,000 - $600,000</p>
<p class="helvtitle">Current Market Tax Range</p>
<p id="taxrange" class="helv">$2,500 - $6,000</p>
<p class="helvtitle">Age Range</p>
<p id="agerange" class="helv">2 - 20 Years</p>
<p class="helvtitle">Amenities</p>
<p id="amenities" class="helv">Crystal Lake<br />
Walking Trails<br />
Shopping Center<br />
Parks<br />
Maude Clifford Public K – 9</p>

</td>
<td style="padding-left: 10px;">
<p id="desc" class="didot">Located on the Upper East Side surrounding the beautiful Crystal Lake with walking trails, the Maude Clifford Public K – 9 School, and a small shopping center.</p>
<p class="helv" style="font-size: 25px; margin-top: 20px; text-align:center;">Photo Gallery</p>
<center><img src="images/photoimg.png" /></center>
</td>
</tr>
</table>
</div>
<MM:EndLock>

<img src="" id="highlighted" style="position: absolute; left: 40%; z-index: 1000; top: 20%;" />

<?php include("subdiv_listings.php"); ?>

<MM:BeginLock translatorClass="MM_SSI" type="ssi" orig="%3C?php include(%22fave_list.php%22); ?%3E" fileRef="fave_list.php" depFiles="file:///C|/inetpub/wwwroot/GP_City/fave_list.php"><div id="fave_list">
<img src="images/close_button.png" style="position: absolute; left: 95%; top: -20px; z-index:
 200;" id="closebtn4" onclick="hideMyList();" />
<?
  $sql='select *,ts.name as sname,tb_listings.id as lid from tb_user_listings,tb_listings,tb_subdivision ts where userid='.$_SESSION['uid'].' and tb_listings.id=listingid and ts.id=tb_listings.subdivision';
  $res=mysql_query($sql);
  while ($row=mysql_fetch_array($res,MYSQL_ASSOC))
  {
    echo '<table class="fave_table" cellpadding="0" cellspacing="0" width="95%" style="margin: 10px auto;"><tr>';
	$sql2='select * from tb_listing_images where listingid='.$row['lid'].' and ordr=0';
	$res2=mysql_query($sql2);
	$row2=mysql_fetch_array($res2,MYSQL_ASSOC);
	echo '<td rowspan="2" style="text-align:left;" width="125"><img src="/images/'.$row2['imagename'].'.'.$row2['ext'].'" style="width: 125px;" /></td>';
	echo '<td><p class="price">$'.number_format($row['listing_price'],2,'.',',').'</p></td>';
    echo '</tr><tr><td><p>'.html_entity_decode($row['sname'],ENT_QUOTES).'</p><img src="images/break.jpg" /></td></tr></table>';  
  }
?>
</div><MM:EndLock>
<MM:BeginLock translatorClass="MM_SSI" type="ssi" orig="%3C? include('search_box.php'); ?%3E" fileRef="search_box.php" depFiles="file:///C|/inetpub/wwwroot/GP_City/search_box.php"><div id="search" style="display: none;">
<img src="images/close_button.png" style="position: absolute; left: 95%; top: -20px; z-index:
 200;" id="closebtn3" onclick="hideSearch();" />
<form name="search" method="POST" action="http://myrealtornow.ca.ds498.alentus.com/searchresults.php">
<input type=hidden name=post value="post42">
<table class="search_table" cellpadding="0" cellspacing="0" width="100%" style="margin: 10px auto;">

<tr>
    <td colspan="2">Price</td>
  </tr>
  <tr>
    <td colspan="2"><select mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" name="pricesearch">
    <option value="1-50">$1 - $50,000</option>
    <option value="50-100">$50,000 - $100,000</option>
    <option value="100-150">$100,000 - $150,000</option>
    <option value="150-200">$150,000 - $200,000</option>
    <option value="200-250">$200,000 - $250,000</option>
    <option value="250-300">$250,000 - $300,000</option>
    <option value="300-400">$300,000 - $400,000</option>
    <option value="400-500">$400,000 - $500,000</option>
    <option value="500">$500,000+</option>
    </select></td>
  </tr>
  <tr>
    <td width="45%">Beds</td>
    <td width="45%">Age</td>
  </tr>
  <tr>
    <td><select mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" style="width: 98%;" name="bedssearch">
    	<option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4+</option>
        </select>
    </td>
    <td><select mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" style="width: 98%;" name="agesearch">
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
    <td>Baths</td>
    <td>Size</td>
  </tr>
  <tr>
    <td><select mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" style="width: 98%;" name="bathsearch">
    	<option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4+</option>
        </select></td>
    <td><select mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" style="width: 98%;" name="sizesearch">
    	<option value="500-1000">500 - 1000 sqft</option>
        <option value="1000-1500">1000 - 1500 sqft</option>
        <option value="1500-2000">1500 - 2000 sqft</option>
        <option value="2000">2000+ sqft</option>
        </select></td>
  </tr>
  <tr>
    <td colspan="2">Garage</td>
  </tr>
  <tr>
    <td colspan="2"><select mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" name="garagesearch"><option value="1">Attached</option><option value="2">Detatched</option>
	<option  value="3">Single</option><option  value="2">Double</option>
	<option value="1">1.5</option></td>
  </tr>
  <tr>
    <td colspan="2">Subdivision</td>
  </tr>
  <tr>
    <td colspan="2"><select mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" name="subdivsearch">
<?
   global $municipalityid;
   
   $sql='select * from tb_subdivision where municipalityid='.$municipalityid;
   echo $sql;
   $res=mysql_query($sql);
   while ($row=mysql_fetch_array($res,MYSQL_ASSOC))
     echo '<option value='.$row['id'].'>'.$row['name'].'</option>';	
?>	
	</select></td>
  </tr>
  <tr>
    <td colspan="2"><input type="submit" value=" " name="searchsubmit" style="background: url(images/searchbtn.png) no-repeat; width: 133px; height: 39px; border: none;" /></td>
  </tr>

</table>
</form>
</div><MM:EndLock>

<div id="search" style="display: none;">
<img src="images/close_button.png" style="position: absolute; left: 95%; top: -20px; z-index:
 200;" id="closebtn3" onclick="hideSearch();" />
<form name="search" method="POST" action="http://myrealtornow.ca.ds498.alentus.com/searchresults.php">
<table class="search_table" cellpadding="0" cellspacing="0" width="100%" style="margin: 10px auto;">

<tr>
    <td colspan="2">Price</td>
  </tr>
  <tr>
    <td colspan="2"><select mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" name="pricesearch">
    <option value="1-50">$1 - $50,000</option>
    <option value="50-100">$50,000 - $100,000</option>
    <option value="100-150">$100,000 - $150,000</option>
    <option value="150-200">$150,000 - $200,000</option>
    <option value="200-250">$200,000 - $250,000</option>
    <option value="250-300">$250,000 - $300,000</option>
    <option value="300-400">$300,000 - $400,000</option>
    <option value="400-500">$400,000 - $500,000</option>
    <option value="500">$500,000+</option>
    </select></td>
  </tr>
  <tr>
    <td width="45%">Beds</td>
    <td width="45%">Age</td>
  </tr>
  <tr>
    <td><select mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" style="width: 98%;" name="bedssearch">
    	<option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4+</option>
        </select>
    </td>
    <td><select mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" style="width: 98%;" name="agesearch">
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
    <td>Baths</td>
    <td>Size</td>
  </tr>
  <tr>
    <td><select mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" style="width: 98%;" name="bathsearch">
    	<option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4+</option>
        </select></td>
    <td><select mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" style="width: 98%;" name="sizesearch">
    	<option value="500-1000">500 - 1000 sqft</option>
        <option value="1000-1500">1000 - 1500 sqft</option>
        <option value="1500-2000">1500 - 2000 sqft</option>
        <option value="2000">2000+ sqft</option>
        </select></td>
  </tr>
  <tr>
    <td colspan="2">Garage</td>
  </tr>
  <tr>
    <td colspan="2"><select mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" name="garageserch"><option value="1">Attached</option><option value="2">Detatched</option>
	<option  value="3">Single</option><option  value="2">Double</option>
	<option value="1">1.5</option></td>
  </tr>
  <tr>
    <td colspan="2">Subdivision</td>
  </tr>
  <tr>
    <td colspan="2"><select mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" mmTranslatedValueHiliteColor="HILITECOLOR=%22No Color%22" name="subdivsearch">
<?
   global $municipalityid;

   $sql='select * from tb_subdivision where municipalityid='.$municipalityid;
   echo $sql;
   $res=mysql_query($sql);
   while ($row=mysql_fetch_array($res,MYSQL_ASSOC))
     echo '<option value='.$row['id'].'>'.$row['name'].'</option>';
?>
	</select></td>
  </tr>
  <tr>
    <td colspan="2"><input type="submit" value="" name="searchsubmit" style="background: url(images/searchbtn.png) no-repeat; width: 133px; height: 39px; border: none;" /></td>
  </tr>

</table>
</form>
</div><MM:EndLock>


<div id="overlay">
&nbsp;
</div>

<script type="text/javascript" language="javascript">
area = document.getElementById('area1');
area.onmouseover = function() { mover('#swanavon'); };
area.onmouseout = function() { mout('#swanavon'); };

area = document.getElementById('area2');
area.onmouseover = function() { mover('#highland_park'); };
area.onmouseout = function() { mout('#highland_park'); };

area = document.getElementById('area3');
area.onmouseover = function() { mover('#mission_heights'); };
area.onmouseout = function() { mout('#mission_heights'); };

area = document.getElementById('area4');
area.onmouseover = function() { mover('#patterson_place'); };
area.onmouseout = function() { mout('#patterson_place'); };

area = document.getElementById('area5');
area.onmouseover = function() { mover('#south_patterson'); };
area.onmouseout = function() { mout('#south_patterson'); };

area = document.getElementById('area6');
area.onmouseover = function() { mover('#southview'); };
area.onmouseout = function() { mout('#southview'); };




area = document.getElementById('area7');
area.onmouseover = function() { mover('#airport'); };
area.onmouseout = function() { mout('#airport'); };

area = document.getElementById('area8');
area.onmouseover = function() { mover('#arbour_hills'); };
area.onmouseout = function() { mout('#arbour_hills'); };

area = document.getElementById('area9');
area.onmouseover = function() { mover('#bear_creek_highlands'); };
area.onmouseout = function() { mout('#bear_creek_highlands'); };

area = document.getElementById('area10');
area.onmouseover = function() { mover('#canfor'); };
area.onmouseout = function() { mout('#canfor'); };

area = document.getElementById('area12');
area.onmouseover = function() { mover('#cobblestone'); };
area.onmouseout = function() { mout('#cobblestone'); };

area = document.getElementById('area13');
area.onmouseover = function() { mover('#college_park'); };
area.onmouseout = function() { mout('#college_park'); };


area = document.getElementById('area14');
area.onmouseover = function() { mover('#copperwood'); };
area.onmouseout = function() { mout('#copperwood'); };

area = document.getElementById('area15');
area.onmouseover = function() { mover('#country_club'); };
area.onmouseout = function() { mout('#country_club'); };

area = document.getElementById('area16');
area.onmouseover = function() { mover('#countryside_north'); };
area.onmouseout = function() { mout('#countryside_north'); };

area = document.getElementById('area17');
area.onmouseover = function() { mover('#countryside_south'); };
area.onmouseout = function() { mout('#countryside_south'); };

area = document.getElementById('area18');
area.onmouseover = function() { mover('#creekside'); };
area.onmouseout = function() { mout('#creekside'); };

area = document.getElementById('area19');
area.onmouseover = function() { mover('#crystal_heights'); };
area.onmouseout = function() { mout('#crystal_heights'); };



area = document.getElementById('area20');
area.onmouseover = function() { mover('#crystal_lake_estates'); };
area.onmouseout = function() { mout('#crystal_lake_estates'); };

area = document.getElementById('area21');
area.onmouseover = function() { mover('#crystal_landing'); };
area.onmouseout = function() { mout('#crystal_landing'); };

area = document.getElementById('area22');
area.onmouseover = function() { mover('#crystal_ridge'); };
area.onmouseout = function() { mout('#crystal_ridge'); };

area = document.getElementById('area23');
area.onmouseover = function() { mover('#gateway'); };
area.onmouseout = function() { mout('#gateway'); };

area = document.getElementById('area24');
area.onmouseover = function() { mover('#grande_banks'); };
area.onmouseout = function() { mout('#grande_banks'); };

area = document.getElementById('area25');
area.onmouseover = function() { mover('#hillside'); };
area.onmouseout = function() { mout('#hillside'); };




area = document.getElementById('area26');
area.onmouseover = function() { mover('#ivy_lake_estates'); };
area.onmouseout = function() { mout('#ivy_lake_estates'); };

area = document.getElementById('area27');
area.onmouseover = function() { mover('#lakeland'); };
area.onmouseout = function() { mout('#lakeland'); };

area = document.getElementById('area28');
area.onmouseover = function() { mover('#mountview'); };
area.onmouseout = function() { mout('#mountview'); };

area = document.getElementById('area29');
area.onmouseover = function() { mover('#northgate'); };
area.onmouseout = function() { mout('#northgate'); };

area = document.getElementById('area30');
area.onmouseover = function() { mover('#obrien_lake'); };
area.onmouseout = function() { mout('#obrien_lake'); };



area = document.getElementById('area31');
area.onmouseover = function() { mover('#pinnacle_ridge'); };
area.onmouseout = function() { mout('#pinnacle_ridge'); };

area = document.getElementById('area32');
area.onmouseover = function() { mover('#richmond_industrial'); };
area.onmouseout = function() { mout('#richmond_industrial'); };

area = document.getElementById('area33');
area.onmouseover = function() { mover('#riverstone'); };
area.onmouseout = function() { mout('#riverstone'); };

area = document.getElementById('area34');
area.onmouseover = function() { mover('#signature_falls'); };
area.onmouseout = function() { mout('#signature_falls'); };

area = document.getElementById('area35');
area.onmouseover = function() { mover('#smith'); };
area.onmouseout = function() { mout('#smith'); };

area = document.getElementById('area36');
area.onmouseover = function() { mover('#summerside'); };
area.onmouseout = function() { mout('#summerside'); };

area = document.getElementById('area37');
area.onmouseover = function() { mover('#trumpeter_village'); };
area.onmouseout = function() { mout('#trumpeter_village'); };

area = document.getElementById('area38');
area.onmouseover = function() { mover('#vision_west'); };
area.onmouseout = function() { mout('#vision_west'); };

area = document.getElementById('area40');
area.onmouseover = function() { mover('#westgate'); };
area.onmouseout = function() { mout('#westgate'); };

area = document.getElementById('area41');
area.onmouseover = function() { mover('#westpointe'); };
area.onmouseout = function() { mout('#westpointe'); };


area = document.getElementById('area42');
area.onmouseover = function() { mover('#avondale'); };
area.onmouseout = function() { mout('#avondale'); };

area = document.getElementById('area43');
area.onmouseover = function() { mover('#hidden_valley'); };
area.onmouseout = function() { mout('#hidden_valley'); };

area = document.getElementById('area44');
area.onmouseover = function() { mover('#royal_oaks'); };
area.onmouseout = function() { mout('#royal_oaks'); };

area = document.getElementById('area45');
area.onmouseover = function() { mover('#northridge'); };
area.onmouseout = function() { mout('#northridge'); };

area = document.getElementById('area47');
area.onmouseover = function() { mover('#vla'); };
area.onmouseout = function() { mout('#vla'); };

area = document.getElementById('area48');
area.onmouseover = function() { mover('#central_business_district'); };
area.onmouseout = function() { mout('#central_business_district'); };

area = document.getElementById('area49');
area.onmouseover = function() { mover('#railtown'); };
area.onmouseout = function() { mout('#railtown'); };

</script>

</body>
</html>