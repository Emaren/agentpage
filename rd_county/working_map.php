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
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/fancyzoom.js"></script>
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
		$(obj).css({'zIndex' : '40','width' : originalWidth['#'+name]/1.5, 'height' : originalHeight['#'+name]/1.5});
		mapOverlay = document.getElementById('overlay');
		closeBtn = document.getElementById('closebtn');
		closeBtn2 = document.getElementById('closebtn2');
		document.getElementById('info_left_grow').style.display='block';
		document.getElementById('info_right_grow').style.display='block';		
		$('#info_right_grow').animate({ top:"30.85px",left:"997.8px",opacity: 0.8,width:'490px',height:"529px"},500,function () { showlisting();});				
		$('#info_left_grow').animate({ opacity: 0.8,width:'490px',height:"529px"},500,function () {showinfo();});
		mapOverlay.style.display = 'block';
		mapOverlay.style.width = '100%';
		mapOverlay.style.height = '100%';
		closeBtn.onclick = function() { hideDiv(obj,area); };
		closeBtn2.onclick = function() { hideDiv(obj,area); };
		mapOverlay.onclick = function() { hideDiv(obj,area); };
	}

    function showinfo()
	{
		document.getElementById('info_left_grow').style.display='none';		
		document.getElementById('info_left_grow').style.height='1px';			
		document.getElementById('info_left_grow').style.width='1px';			
		document.getElementById('info_left_grow').style.opacity=0.1;								
		infoDiv = document.getElementById('info_left');
		infoDiv.style.top = '5%';
		infoDiv.style.left = '0';
		infoDiv.style.display = 'block';		
		highlight = document.getElementById('highlighted');
		highlight.style.display = 'block';		
	}
	
	function showlisting()
	{
		document.getElementById('info_right_grow').style.display='none';	
		document.getElementById('info_right_grow').style.height='1px';			
		document.getElementById('info_right_grow').style.width='1px';			
		document.getElementById('info_right_grow').style.opacity=0.1;					
		document.getElementById('info_right_grow').style.top='644px';					
		document.getElementById('info_right_grow').style.left='1300px';												
		listDiv = document.getElementById('info_right');
		listDiv.style.top = '5%';
		listDiv.style.left = '60%'; 
		listDiv.style.display = 'block'; 	
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
<img src="images/mylist.png" id="mylist" onclick="showMyList();" />
<img src="images/mysearch.png" id="mysearch" onclick="showSearch();" />
<div id="gp_contain">
<img src="images/road1.png" style="position: absolute; top: 362px; left: 502px; z-index: 5;" />
<img src="images/road2.png" style="position: absolute; top: 362px; left: 939px; z-index: 5;" />

<!--<img src="images/92ndave.png" style="position:absolute; top: 383px; left: 995px; z-index: 5;" />
<img src="images/68ave.png" style="position:absolute; top: 559px; left: 918px; z-index: 5;" />
<img src="images/68ave.png" style="position:absolute; top: 559px; left: 784px; z-index: 5;" />
<img src="images/76ave.png" style="position:absolute; top: 498px; left: 907px; z-index: 5;" />
<img src="images/hwy43.png" style="position:absolute; top: 498px; left: 740px; z-index: 5;" />
<img src="images/hwy43.png" style="position:absolute; top: 353px; left: 740px; z-index: 5;" />
<img src="images/116st.png" style="position:absolute; top: 353px; left: 626px; z-index: 5;" />
<img src="images/116st.png" style="position:absolute; top: 252px; left: 626px; z-index: 5;" />-->


<img src="images/swanavon.png" border="0" usemap="#m1" height="117" width="55"  class="thumb" id="swanavon" style="position:absolute; top: 388px; left: 829px; z-index: 1;" />
<map name="m1" id="m1">
  <area shape="poly" coords="3,1,53,2,53,112,10,111,5,104,13,102,15,89,20,81,16,77,17,61,14,57,6,61,4,56,7,48,15,35,8,22,3,14" id="area1" href="javascript:void(0)" onclick="showDiv('#swanavon','area1',44)"  />
</map>
<img src="images/highland_park.png" border="0" usemap="#m2" height="117" width="85"  class="thumb" id="highland_park" style="position:absolute; top: 384px; left: 883px; z-index: 1;" />
<map name="m2" id="m2">
  <area shape="poly" coords="1,0,2,108,9,112,80,112,82,107,47,4,46,3,39,2,36,2,35,2,32,2" href="javascript:void(0)" id="area2" onclick="showDiv('#highland_park','area2',22)"  />
</map>
<img src="images/mission_heights.png" border="0" usemap="#m3" height="141" width="128" class="thumb" id="mission_heights" style="position:absolute; top: 499px; left: 756px; z-index: 1;" />
<map name="m3" id="m3">
  <area shape="poly" coords="2,2,1,138,114,137,117,130,112,128,117,115,127,121,126,110,123,104,126,100,113,102,109,95,119,92,108,90,105,84,110,81,99,78,97,72,103,68,99,61,94,61,93,53,88,52,88,42,93,40,93,26,86,19,80,3" id="area3" href="javascript:void(0)" onclick="showDiv('#mission_heights','area3',27)"  />
</map>
<img src="images/patterson_place.png" border="0" usemap="#m4" height="72" width="107"  class="thumb" id="patterson_place" style="position:absolute; top: 498px; left: 881px; z-index: 1;" />
<map name="m4" id="m4">
  <area shape="poly" coords="2,2,3,65,7,68,102,69,105,63,86,6,84,2" id="area4" href="javascript:void(0)" onclick="showDiv('#patterson_place','area4',32)"  />
</map>
<img src="images/south_patterson.png" border="0" usemap="#m5" height="75" width="132"  class="thumb" id="south_patterson" style="position:absolute; top: 568px; left: 881px; z-index: 1;" />
<map name="m5" id="m5">
  <area shape="poly" coords="2,1,2,17,13,33,18,52,15,70,127,69,131,60,110,7,110,4,97,2" id="area5" href="javascript:void(0)" onclick="showDiv('#south_patterson','area5',42)"  />
</map>
<img src="images/southview.png" border="0" usemap="#m6" height="94" width="43"  class="thumb" id="southview" style="position:absolute; top: 499px; left: 838px; z-index: 1;" />
<map name="m6" id="m6">
  <area shape="poly" coords="2,2,39,1,42,7,40,87,36,89,31,86,31,72,29,56,20,51,14,49,20,38,21,32,16,33,16,22,3,13" id="area6" href="javascript:void(0)" onclick="showDiv('#southview','area6',41)"  />
</map>


<img src="images/airport.png" border="0" usemap="#m7" class="thumb" height="185" width="259" id="airport" style="position:absolute; top: 177px; left: 302px; z-index: 1;" />
<map name="m7" id="m7">
  <area shape="poly" coords="3,12,3,68,10,71,68,72,68,177,74,182,256,181,257,176,256,11,68,10,68,3,54,4,38,3,26,11" id="area7" href="javascript:void(0)" onclick="showDiv('#airport','area7',1)" />
</map>
<img src="images/arbour_hills.png" border="0" usemap="#m8" class="thumb" width="191" height="82" id="arbour_hills" style="position:absolute; top: 12px; left: 625px; z-index: 1;" />
<map name="m8" id="m8">
  <area shape="poly" coords="3,2,6,73,184,73,189,67,189,8,183,2" id="area8" href="javascript:void(0)" onclick="showDiv('#arbour_hills','area8',2)" />
</map>
<img src="images/bear_creek_highlands.png" border="0" usemap="#m9" width="131" height="144" class="thumb" id="bear_creek_highlands" style="position:absolute; top: 11px; left: 495px; z-index: 1;" />
<map name="m9" id="m9">
  <area shape="poly" coords="-1,2,-1,135,123,137,129,131,129,5,122,2" id="area9" href="javascript:void(0)" onclick="showDiv('#bear_creek_highlands','area9',4)" />
</map>
<img src="images/canfor.png" width="93" height="106" border="0" usemap="#m10" class="thumb" id="canfor" style="position:absolute; top: 396px; left: 756px; z-index: 1;" />
<map name="m10" id="m10">
  <area shape="poly" coords="4,9,2,101,5,103,81,102,75,92,80,89,87,89,87,82,90,72,85,71,85,63,89,55,78,57,74,49,77,38,84,26,80,18,77,9,76,2,71,4,65,4,47,4,14,4" id="area10" href="javascript:void(0)" onclick="showDiv('#canfor','area10',63)" />
</map>
<img src="images/carriage_lane.png" width="69" height="142" border="0" usemap="#m11" class="thumb" id="carriage_lane" style="position:absolute; top: 220px; left: 1195px; z-index: 1;" />
<map name="m11" id="m11">
  <area shape="rect" coords="1,3,67,140"  id="area11" href="javascript:void(0)" onclick="showDiv('#carriage_lane','area11',6)" />
</map>
<img src="images/cobblestone.png" border="0" usemap="#m12" class="thumb" width="65" height="75" id="cobblestone" style="position:absolute; top: 366px; left: 1010px; z-index: 1;" />
<map name="m12" id="m12">
  <area shape="rect" coords="0,1,65,72" id="area12" href="javascript:void(0)" onclick="showDiv('#cobblestone','area12',9)" />
</map>
<img src="images/college_park.png" border="0" usemap="#m13" width="79" height="114" class="thumb" id="college_park" style="position:absolute; top: 292px; left: 756px; z-index: 1;" />
<map name="m13" id="m13">
  <area shape="poly" coords="2,4,3,110,19,106,78,105,73,97,72,79,65,61,61,49,64,41,68,31,58,24,51,31,46,28,40,26,35,23,28,15,17,17,12,17,10,11,8,6" id="area13" href="javascript:void(0)" onclick="showDiv('#college_park','area13',10)"  />
</map>
<img src="images/copperwood.png" border="0" usemap="#m14" class="thumb" width="56" height="67" id="copperwood" style="position:absolute; top: 158px; left: 1074px; z-index: 1;" />
<map name="m14" id="m14">
  <area shape="poly" coords="2,2,2,48,2,50,3,53,2,64,55,64,55,2" id="area14" href="javascript:void(0)" onclick="showDiv('#copperwood','area14',11)" />
</map>
<img src="images/country_club.png" width="119" height="70" border="0" usemap="#m15" class="thumb" id="country_club" style="position:absolute; top: 640px; left: 894px; z-index: 1;" />
<map name="m15" id="m15">
  <area shape="poly" coords="4,4,112,1,117,6,118,60,114,67,33,67,35,56,25,48,21,51,9,38,9,24,13,20,7,18,3,10" id="area15" href="javascript:void(0)" onclick="showDiv('#country_club','area15',12)" />
</map>

<img src="images/countryside_north.png" width="52" height="75" border="0" usemap="#m16" class="thumb" id="countryside_north" style="position:absolute; top: 572px; left: 1021px; z-index: 1;" />
<map name="m16" id="m16">
  <area shape="poly" coords="4,4,19,43,19,71,48,72,50,68,51,2" id="area16" href="javascript:void(0)" onclick="showDiv('#countryside_north','area16',13)" />
</map>
<img src="images/countryside_south.png" height="73" width="47" border="0" usemap="#m17" class="thumb" id="countryside_south" style="position:absolute; top: 644px; left: 1027px; z-index: 2;" />
<map name="m17" id="m17">
  <area shape="rect" coords="2,2,46,71" id="area17" href="javascript:void(0)" onclick="showDiv('#countryside_south','area17',14)" />
</map>
<img src="images/creekside.png" border="0" usemap="#m18" height="83" width="34" class="thumb" id="creekside" style="position:absolute; top: 439px; left: 1009px; z-index: 2;" />
<map name="m18" id="m18">
  <area shape="poly" coords="2,2,1,57,34,79,32,2" id="area18" href="javascript:void(0)" onclick="showDiv('#creekside','area18',19)" />
</map>
<img src="images/crystal_heights.png" border="0" usemap="#m19" class="thumb" id="crystal_heights" width="59" height="107" style="position:absolute; top: 225px; left: 1010px; z-index: 1;" />
<map name="m19" id="m19">
  <area shape="poly" coords="2,3,2,98,6,104,15,103,26,98,41,89,57,84,58,6,54,2" id="area19" href="javascript:void(0)" onclick="showDiv('#crystal_heights','area19',18)" />
</map>
<img src="images/crystal_lake_estates.png" border="0" usemap="#m20" width="107" height="139" class="thumb" id="crystal_lake_estates" style="position:absolute; top: 90px; left: 968px; z-index: 1;" />
<map name="m20" id="m20">
  <area shape="poly" coords="13,34,17,25,25,18,30,16,32,11,45,11,53,12,61,13,73,14,78,11,79,2,105,3,107,130,102,134,3,134,11,110,12,98,14,88,14,77,13,70,13,61,11,52,7,43" id="area20" href="javascript:void(0)" onclick="showDiv('#crystal_lake_estates','area20',17)" />
</map>
<img src="images/crystal_landing.png" border="0" usemap="#m21" class="thumb" width="67" height="47" id="crystal_landing" style="position:absolute; top: 314px; left: 1070px; z-index: 1;" />
<map name="m21" id="m21">
  <area shape="rect" coords="1,2,65,43" id="area21" href="javascript:void(0)" onclick="showDiv('#crystal_landing','area21',15)" />
</map>
<img src="images/crystal_ridge.png" height="99" width="74" border="0" usemap="#m22" class="thumb" id="crystal_ridge" style="position:absolute; top: 127px; left: 909px; z-index: 1;" />
<map name="m22" id="m22">
  <area shape="poly" coords="8,5,3,20,4,94,17,95,59,95,69,74,72,54,71,33,69,13,63,7,57,7,49,3,41,4" id="area22" href="javascript:void(0)" onclick="showDiv('#crystal_ridge','area22',16)" />
</map>
<img src="images/gateway.png" height="73" width="64" border="0" usemap="#m23" class="thumb" id="gateway" style="position:absolute; top: 290px; left: 693px; z-index: 1;" />
<map name="m23" id="m23">
  <area shape="rect" coords="2,2,62,69" id="area23" href="javascript:void(0)" onclick="showDiv('#gateway','area23',20)" />
</map>
<img src="images/grande_banks.png" width="110" height="74" border="0" usemap="#m24" class="thumb" id="grande_banks" style="position:absolute; top: 638px; left: 756px; z-index: 1;" />
<map name="m24" id="m24">
  <area shape="poly" coords="2,2,2,67,6,70,18,69,40,70,63,71,62,65,66,59,65,54,71,53,76,51,78,41,78,36,82,31,92,30,87,24,95,22,97,13,107,4,89,4" id="area24" href="javascript:void(0)" onclick="showDiv('#grande_banks','area24',62)" />
</map>

<img src="images/hillside.png" border="0" usemap="#m25" height="72" width="102" class="thumb" id="hillside" style="position:absolute; top: 290px; left: 907px; z-index: 1;" />
<map name="m25" id="m25">
  <area shape="poly" coords="2,3,2,16,10,35,28,68,96,68,101,62,100,2" id="area25" href="javascript:void(0)" onclick="showDiv('#hillside','area25',23)" />
</map>
<img src="images/ivy_lake_estates.png" width="60" height="54" border="0" usemap="#m26" class="thumb" id="ivy_lake_estates" style="position:absolute; top: 308px; left: 1009px; z-index: 1;" />
<map name="m26" id="m26">
  <area shape="poly" coords="3,20,1,47,8,51,54,51,59,47,57,2,44,8,21,21" id="area26" href="javascript:void(0)" onclick="showDiv('#ivy_lake_estates','area26',25)" />
</map>
<img src="images/lakeland.png" border="0" usemap="#m27" width="92" height="45" class="thumb" id="lakeland" style="position:absolute; top: 86px; left: 953px; z-index: 1;" />
<map name="m27" id="m27">
  <area shape="poly" coords="85,1,91,6,87,13,45,12,21,41,14,36,1,19,0,7,6,2" id="area27" href="javascript:void(0)" onclick="showDiv('#lakeland','area27',26)" />
</map>
<img src="images/mountview.png" border="0" usemap="#m28" class="thumb" id="mountview" width="101" height="69" style="position:absolute; top: 226px; left: 908px; z-index: 1;" />
<map name="m28" id="m28">
  <area shape="rect" coords="1,4,100,67" id="area28" href="javascript:void(0)" onclick="showDiv('#mountview','area28',28)" />
</map>
<img src="images/northgate.png" border="0" usemap="#m29" class="thumb" id="northgate" width="133" height="221" style="position:absolute; top: 3px; left: 817px; z-index: 1;" />
<map name="m29" id="m29">
  <area shape="poly" coords="1,12,53,12,63,1,64,13,132,13,132,25,132,46,131,62,132,74,132,125,99,124,93,139,93,217,69,218,70,81,2,80" id="area29" href="javascript:void(0)" onclick="showDiv('#northgate','area29',29)" />
</map>
<img src="images/obrien_lake.png" border="0" height="74" width="64" usemap="#m30" class="thumb" id="obrien_lake" style="position:absolute; top: 645px; left: 690px; z-index: 1;" />
<map name="m30" id="m30">
  <area shape="rect" coords="2,2,63,72" id="area30" href="javascript:void(0)" onclick="showDiv('#obrien_lake','area30',31)" />
</map>
<img src="images/pinnacle_ridge.png" width="129" height="99" border="0" usemap="#m31" class="thumb" id="pinnacle_ridge" style="position:absolute; top: 556px; left: 627px; z-index: 1;" />
<map name="m31" id="m31">
  <area shape="poly" coords="1,36,1,90,6,95,59,95,59,89,127,90,128,2,70,2,70,37" id="area31" href="javascript:void(0)" onclick="showDiv('#pinnacle_ridge','area31',33)" />
</map>
<img src="images/richmond_industrial.png" height="195" width="128" border="0" usemap="#m32" class="thumb" id="richmond_industrial" style="position:absolute; top: 369px; left: 626px; z-index: 1;" />
<map name="m32" id="m32">
  <area shape="poly" coords="5,2,6,117,6,134,48,133,48,151,70,150,69,187,126,188,126,6,120,0" id="area32" href="javascript:void(0)" onclick="showDiv('#richmond_industrial','area32',35)" />
</map>
<img src="images/riverstone.png" width="68" height="134" border="0" usemap="#m33" class="thumb" id="riverstone" style="position:absolute; top: 439px; left: 1006px; z-index: 1;" />
<map name="m33" id="m33">
  <area shape="poly" coords="38,2,38,73,33,79,9,62,4,62,2,92,17,131,67,131,66,2" id="area33" href="javascript:void(0)" onclick="showDiv('#riverstone','area33',36)" />
</map>

<img src="images/signature_falls.png" width="63" height="75" border="0" usemap="#m34" class="thumb" id="signature_falls" style="position:absolute; top: 572px; left: 1075px; z-index: 1;" />
<map name="m34" id="m34">
  <area shape="rect" coords="2,1,62,72" id="area34" href="javascript:void(0)" onclick="showDiv('#signature_falls','area34',39)" />
</map>
<img src="images/smith.png" border="0" width="63" height="75" usemap="#m35" class="thumb" id="smith" style="position:absolute; top: 366px; left: 945px; z-index: 1;" />
<map name="m35" id="m35">
  <area shape="poly" coords="2,2,2,25,6,35,17,72,62,72,62,2" id="area35" href="javascript:void(0)" onclick="showDiv('#smith','area35',40)" />
</map>
<img src="images/summerside.png" border="0" usemap="#m36" class="thumb" width="97" height="143" id="summerside" style="position:absolute; top: 646px; left: 1026px; z-index: 1;" />
<map name="m36" id="m36">
  <area shape="poly" coords="51,2,51,72,1,73,2,134,7,140,79,139,86,127,83,119,91,113,88,101,96,93,96,6,92,2" id="area36" href="javascript:void(0)" onclick="showDiv('#summerside','area36',43)" />
</map>
<img src="images/trumpeter_village.png" border="0" usemap="#m37" height="33" width="69" class="thumb" id="trumpeter_village" style="position:absolute; top: 284px; left: 1070px; z-index: 1;" />
<map name="m37" id="m37">
  <area shape="rect" coords="2,2,67,28" id="area37" href="javascript:void(0)" onclick="showDiv('#trumpeter_village','area37',67)" />
</map>
<img src="images/vision_west.png" width="129" height="70" border="0" usemap="#m38" class="thumb" id="vision_west" style="position:absolute; top: 368px; left: 501px; z-index: 1;" />
<map name="m38" id="m38">
  <area shape="rect" coords="1,2,129,67" id="area38" href="javascript:void(0)" onclick="showDiv('#vision_west','area38',61)" />
</map>
<!--<img src="images/wedgewood.png" border="0" usemap="#m39" class="thumb" id="wedgewood" style="position:absolute; top: 445px; left: 825px; z-index: 1;" />
<map name="m39" id="m39">
  <area shape="poly" coords="13,4,7,11,2,23,4,31,11,33,20,36,27,39,33,45,37,53,43,58,60,59,63,55,64,4,53,4" href="#" />
</map>-->
<img src="images/westgate.png" width="132" height="80" border="0" usemap="#m40" class="thumb" id="westgate" style="position:absolute; top: 286px; left: 562px; z-index: 1;" />
<map name="m40" id="m40">
  <area shape="rect" coords="2,3,133,73" id="area40" href="javascript:void(0)" onclick="showDiv('#westgate','area40',45)" />
</map>
<img src="images/westpointe.png" height="88" width="64" border="0" usemap="#m41" class="thumb" id="westpointe" style="position:absolute; top: 504px; left: 630px; z-index: 1;" />
<map name="m41" id="m41">
  <area shape="poly" coords="2,3,2,82,5,85,64,85,62,22,40,23,39,2" id="area41" href="javascript:void(0)" onclick="showDiv('#westpointe','area41',46)" />
</map>

<img src="images/avondale.png" width="120" height="102" border="0" usemap="#m42" class="thumb" id="avondale" style="position: absolute; top: 227px; left: 764px; z-index: 1;" />
<map name="m42" id="m42">
  <area shape="poly" coords="3,26,10,14,19,8,30,4,104,2,104,69,119,69,119,99,70,98,69,92,62,93,56,86,51,85,41,78,39,72,25,70,19,64,14,63,11,50,11,33,7,35" id="area42" href="javascript:void(0)" onclick="showDiv('#avondale','area42',3)" />
</map>
<img src="images/hidden_valley.png" width="79" height="205" border="0" usemap="#m43" class="thumb" id="hidden_valley" style="position: absolute; top: 91px; left: 689px; z-index: 1;" />
<map name="m43" id="m43">
  <area shape="poly" coords="3,1,3,175,3,184,4,199,63,200,64,172,70,157,77,147,65,111,67,1" id="area43" href="javascript:void(0)" onclick="showDiv('#hidden_valley','area43',21)" />
</map>
<img src="images/royal_oaks.png" width="62" height="150" border="0" usemap="#m44" class="thumb" id="royal_oaks" style="position: absolute; top: 92px; left: 757px; z-index: 1;" />
<map name="m44" id="m44">
  <area shape="poly" coords="2,0,2,109,13,146,24,134,60,131,61,1" id="area44" href="javascript:void(0)" onclick="showDiv('#royal_oaks','area44',37)" />
</map>
<img src="images/northridge.png" width="67" height="141" border="0" usemap="#m45" class="thumb" id="northridge" style="position: absolute; top: 88px; left: 819px; z-index: 1;" />
<map name="m45" id="m45">
  <area shape="poly" coords="2,3,3,119,3,134,43,133,55,127,65,116,66,2" id="area45" href="javascript:void(0)" onclick="showDiv('#northridge','area45',30)" />
</map>
<img src="images/vla.png" width="42" height="103" border="0" usemap="#m47" class="thumb" id="vla" style="position: absolute; top: 226px; left: 866px; z-index: 1;" />
<map name="m47" id="m47">
  <area shape="poly" coords="15,2,2,2,3,65,18,65,18,100,41,100,41,3,28,3" id="area47" href="javascript:void(0)" onclick="showDiv('#vla','area47',50)" />
</map>
<img src="images/central_business_district.png" width="129" height="86" border="0" usemap="#m48" class="thumb" id="central_business_district" style="position: absolute; top: 322px; left: 817px; z-index: 1;" />
<map name="m48" id="m48">
  <area shape="poly" coords="8,5,2,16,14,67,114,68,120,82,126,83,127,46,117,45,99,9,14,9" id="area48" href="javascript:void(0)" onclick="showDiv('#central_business_district','area48',7)" />
</map>
<img src="images/railtown.png" height="84" width="44" border="0" usemap="#m49" class="thumb" id="railtown" style="position:absolute; top: 439px; left: 964px; z-index: 1;" />
<map name="m49" id="m49">
  <area shape="poly" coords="-1,0,31,80,43,80,43,2" id="area49" href="javascript:void(0)" onclick="showDiv('#railtown','area49',34)" />
</map>
<img src="images/wedgewood.png" height="72" width="75" border="0" usemap="#m50" class="thumb" id="wedgewood" style="position:absolute; top: 770px; left: 935px; z-index: 1;" />
<map name="m50" id="m50">
  <area shape="poly" coords="15,4,7,13,5,21,2,25,2,38,6,40,17,41,26,44,34,49,41,58,42,64,49,69,70,69,73,65,73,3"  id="area50" href="javascript:void(0)" onclick="showDiv('#wedgewood','area50',48)" />
</map>
<img src="images/nothing.png" style="position:absolute; top: 639px; left: 756px; width: 188px; height: 276px;" />
<img src="images/cityofgrandeprairie.png" style="position: absolute; top: 98%; left: 33%;" />
</div>

<img src="" id="highlighted" style="position: absolute; left: 40%; z-index: 1000; top: 20%;" />

<? 
   include("subdiv_listings.php"); 
   include('fave_list.php');
   include('search_box.php');
   include('subdiv_info.php');   
/*   include('map.php'); 
   include('list_detail.php');*/
?>

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

area = document.getElementById('area50');
area.onmouseover = function() { mover('#wedgewood'); };
area.onmouseout = function() { mout('#wedgewood'); };

area = document.getElementById('area11');
area.onmouseover = function() { mover('#carriage_lane'); };
area.onmouseout = function() { mout('#carriage_lane'); };

</script>

</body>
</html>