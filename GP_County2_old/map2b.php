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
<link rel="stylesheet" href="/images/jquery.fancybox-1.3.4.css" />
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/fancyzoom.js"></script>
<script type="text/javascript" src="/ajax.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="/js/jquery.fancybox-1.3.4.js"></script>

<script type="text/javascript">

	originalWidth = new Array();
	originalHeight = new Array();
	originalzIndex = new Array();
	pos = new Array();
	function mover(imgID,factor)
	{
    if (typeof img !='undefined' && img!='')
	{
	  if(originalHeight[img.id] != img.width() || originalWidth[img.id] != img.height())
	  {
	    img.width=originalWidth[img.id];
	    img.height=originalHeight[img.id];
      }
	}
	img = $(imgID);
    if (typeof originalWidth[imgID]=='undefined')
	{
	  originalWidth[imgID] = img.width()*factor;
	  originalHeight[imgID] = img.height()*factor;
      pos[imgID] = getPos(imgID);
	  originalzIndex[imgID] = img.css('z-index');
	}
 	  $(imgID).css({'z-index' : '11'});
	  var t=(pos[imgID].top-22)+'px';
	  var w=originalWidth[imgID]+'px';
	  var h=originalHeight[imgID]+'px';
	  var l=pos[imgID].left+'px';
	  $(imgID).addClass("hover").stop()
	  	  .animate({
			top: t,
			left: l,
			width: w,
			height:h
		  },200);
	}

	function getPos(imgID)
	{
      var left = $(imgID).css("left");
	  var top = $(imgID).css("top");
	  var browserName=navigator.appName;
      if (browserName=="Microsoft Internet Explorer")
	  {
  	    left=left.replace('px','');
	    top=top.replace('px','');
	  }
	  return { top: top, left: left };
    }

	function mout(imgID,factor)
	{
	$(imgID).removeClass("hover").stop()
		.animate({
			top: pos[imgID].top+'px',
			left: pos[imgID].left,
			width: originalWidth[imgID]/factor,
			height: originalHeight[imgID]/factor
		}, 400, function() {
		  $(imgID).css('z-index', originalzIndex[imgID]);
    });
	}

	function showDiv(obj,area,id,factor)
	{
	  getdivision(id,obj,area,factor);
	}

    function GetWidth()
    {
        var x = 0;
        if (self.innerHeight)
          x = self.innerWidth;
        else
		  if (document.documentElement && document.documentElement.clientHeight)
               x = document.documentElement.clientWidth;
        else
		  if (document.body)
                x = document.body.clientWidth;
        return x;
	}

	function showDiv2(obj,area,factor)
	{
		document.getElementById(area).onmouseover = '';
		document.getElementById(area).onmouseout = '';
		$(obj).stop();
		name = document.getElementById(obj.substr(1)).id;
		highlight = document.getElementById('highlighted');
		if (highlight)
  		  highlight.src = 'images/'+name+'2.png';
		$(obj).css({'z-index' : originalzIndex['#'+name],'width' : originalWidth['#'+name]/factor, 'height' : originalHeight['#'+name]/factor, 'top' : pos['#'+name].top+'px'});
		mapOverlay = document.getElementById('overlay');
		closeBtn = document.getElementById('closebtn');
		closeBtn2 = document.getElementById('closebtn2');
		document.getElementById('info_left_grow').style.display='block';
		document.getElementById('info_right_grow').style.display='block';
		// used to be left: 997.8px;
		w=GetWidth();
		w=(w*.6);
		w=w+'px';
		$('#info_right_grow').animate({ top:"120px",left:w,opacity: 0.8,width:'490px',height:"629px"},500,function () { showlisting();});
		$('#info_left_grow').animate({ opacity: 0.8,width:'490px',height:"629px"},500,function () {showinfo();});
		mapOverlay.style.display = 'block';
		mapOverlay.style.width = '100%';
    	var html=document.getElementsByTagName('html');
		hght=html.item(0).scrollHeight;
		mapOverlay.style.height = hght+'px';
		closeBtn.onclick = function() { hideDiv(obj,area,factor); };
		closeBtn2.onclick = function() { hideDiv(obj,area,factor); };
		mapOverlay.onclick = function() { hideDiv(obj,area,factor); };
		obj2=obj;
		are2=area;
		factor2=factor;
	}

    function goback()
	{
	  hideDetail();
	  showDiv2(obj2,are2,factor2);
	}

	function searchresults_to_search()
	{
	     document.getElementById('searchresults2').style.display='none';
	     document.getElementById('search').style.display='block';
	}

	function goback_search()
	{
	  hideDetail();
 	  document.getElementById('info_right_grow').style.display='block';
      $('#info_right_grow').animate({ top:"120px",left:'60%',opacity: 0.8,width:'490px',height:"629px"},500,function () {
  		  mapOverlay = document.getElementById('overlay');
		  mapOverlay.style.display = 'block';
		  mapOverlay.style.width = '100%';
    	  var html=document.getElementsByTagName('html');
		  hght=html.item(0).scrollHeight;
		  mapOverlay.style.height = hght+'px';
		  mapOverlay.style.minHeight = '1060px';
		  mapOverlay.onclick = function() { hideMyList_search(); };
 	     document.getElementById('searchresults').style.display='block';
	     document.getElementById('searchresults2').style.display='block';
	     document.getElementById('search').style.display='none';
 		 document.getElementById('info_right_grow').style.display='none';
		 document.getElementById('info_right_grow').style.height='1px';
		 document.getElementById('info_right_grow').style.width='1px';
		 document.getElementById('info_right_grow').style.opacity=0.1;
		 document.getElementById('info_right_grow').style.top='664px';
		 document.getElementById('info_right_grow').style.left='1300px';
	  });
	}

    function showinfo()
	{
		document.getElementById('info_left_grow').style.display='none';
		document.getElementById('info_left_grow').style.height='1px';
		document.getElementById('info_left_grow').style.width='1px';
		document.getElementById('info_left_grow').style.opacity=0.1;
		infoDiv = document.getElementById('info_left_w');
		infoDiv.style.top = '120px';
		infoDiv.style.left = '0';
		infoDiv.style.display = 'block';
		infoDiv = document.getElementById('info_left');
		infoDiv.style.display = 'block';
		highlight = document.getElementById('highlighted');
		highlight.style.display = 'block';
		closeBtn2 = document.getElementById('closebtn');
		closeBtn2.style.display='block';
	}

	function showlisting()
	{
		document.getElementById('info_right_grow').style.display='none';
		document.getElementById('info_right_grow').style.height='1px';
		document.getElementById('info_right_grow').style.width='1px';
		document.getElementById('info_right_grow').style.opacity=0.1;
		document.getElementById('info_right_grow').style.top='664px';
		document.getElementById('info_right_grow').style.left='1300px';
		listDiv = document.getElementById('info_right_w');
		listDiv.style.top = '120px';
		listDiv.style.left = '805px';
		listDiv.style.display = 'block';
		listDiv = document.getElementById('info_right');
		listDiv.style.display = 'block';
		closeBtn2 = document.getElementById('closebtn2');
		closeBtn2.style.display='block';
	}

    function showmap(lat,long)
	{
		document.getElementById('info_left_grow').style.display='none';
		document.getElementById('info_left_grow').style.height='1px';
		document.getElementById('info_left_grow').style.width='1px';
		document.getElementById('info_left_grow').style.opacity=0.1;
		infoDiv = document.getElementById('map_div');
		infoDiv.style.top = '120px';
		infoDiv.style.left = '0';
		infoDiv.style.display = 'block';
        var latlng = new google.maps.LatLng(lat,long);
        var myOptions = {
      				zoom: 11,
			        center: latlng,
					mapTypeControl: false,
      				mapTypeId: google.maps.MapTypeId.ROADMAP
    	};
    	var map = new google.maps.Map(document.getElementById("map_wrap"), myOptions);
   		var marker = new google.maps.Marker({
                 position: latlng,
			     map: map,
                 title: add
	 	});
	}

	function showdetail()
	{
		document.getElementById('info_right_grow').style.display='none';
		document.getElementById('info_right_grow').style.height='1px';
		document.getElementById('info_right_grow').style.width='1px';
		document.getElementById('info_right_grow').style.opacity=0.1;
		document.getElementById('info_right_grow').style.top='664px';
		document.getElementById('info_right_grow').style.left='1300px';
		listDiv = document.getElementById('list_detail');
		listDiv.style.top = '120px';
		listDiv.style.left = '60%';
		listDiv.style.display = 'block';
        setTimeout( function() {
		$("a.fancybox").fancybox();
	    document.getElementById('loader').style.display='none';
		document.getElementById('houseimg').onclick=function () {$("a.fancybox:first").trigger("click");};
		},1000);
		closeBtn2 = document.getElementById('closebtn3b');
		closeBtn2.style.display='block';
		mapOverlay = document.getElementById('overlay');
		mapOverlay.onclick = function() { hideDetail(); };
	}

	function hideDiv(obj,area,factor)
	{
		document.getElementById(area).onmouseover = function() { mover(obj,factor); };
		document.getElementById(area).onmouseout = function() { mout(obj,factor); };
		name = document.getElementById(obj.substr(1)).id;
		$(obj).css({'top' : pos[obj].top+'px',left: pos[obj].left,marginLeft:0,marginTop:0,width: originalWidth[obj]/factor,height: originalHeight[obj]/factor});
	    hide_info_list();
		mapOverlay = document.getElementById('overlay');
		mapOverlay.style.display = 'none';
	    hl = document.getElementById('highlighted');
	    hl.style.display='none';
	}

	function hide_info_list()
	{
		infoDiv = document.getElementById('info_left');
		infoDiv.style.display = 'none';
		listDiv = document.getElementById('info_right');
		listDiv.style.top='0';
		listDiv.style.left='0';
		infoDiv.style.top='0';
		infoDiv.style.left='0';
		listDiv.style.display = 'none';
		closeBtn2 = document.getElementById('closebtn2');
		closeBtn2.style.display='none';
		closeBtn = document.getElementById('closebtn');
		closeBtn.style.display='none';
    }

	function showSearch()
	{
		hideall_popups();
		document.getElementById('search_grow').style.display='block';
        $('#search_grow').animate({ top:"120px",left: "60%", opacity: 0.8,height:"659px"},500,function () { make_search_appear(); });
	}

    function make_search_appear()
	{
  	    document.getElementById('search_grow').style.display='none';
	    document.getElementById('search_grow').style.height='1px';
  	    document.getElementById('search_grow').style.top='659px';
	    searchBox = document.getElementById('search');
   	    searchBox.style.display = 'block';
		mapOverlay = document.getElementById('overlay');
		mapOverlay.style.display = 'block';
		mapOverlay.style.width = '100%';
    	var html=document.getElementsByTagName('html');
		hght=html.item(0).scrollHeight;
		mapOverlay.style.height = hght+'px';
		mapOverlay.style.minHeight = '1060px';
		mapOverlay.onclick = function() { hideMyList_search(); };
	}


	function hideSearch() {
	  searchBox = document.getElementById('search');
	  searchBox.style.display = 'none';
	  searchBox = document.getElementById('searchresults');
	  searchBox.style.display = 'none';
	  mapOverlay = document.getElementById('overlay');
	  mapOverlay.style.display = 'none';
	}

	function showMyList() {
	  hideall_popups();	
	  document.getElementById('fave_grow').style.display='block';
	  document.getElementById('fave_map_grow').style.display='block';	  
      $('#fave_grow').animate({ top:"120px",opacity: 0.8,height:"659px"},500,function () { make_fave_appear(); });
      $('#fave_map_grow').animate({ top:"120px",opacity: 0.8,height:"659px"},500,function () { });	  
	}

    function make_fave_appear()
	{
	  document.getElementById('fave_grow').style.display='none';
	  document.getElementById('fave_map_grow').style.display='none';	  
	  document.getElementById('fave_map_grow').style.height='1px';	  	  
	  document.getElementById('fave_grow').style.height='1px';	 
	  document.getElementById('fave_map_div').style.display='block';
      google.maps.event.trigger(map2, "resize");	
	  var ll = new google.maps.LatLng(55.167299,-118.797913);	  
	  map2.setCenter(ll);
  	  myListBox = document.getElementById('fave_list');
	  myListBox.style.display = 'block';
	  mapOverlay = document.getElementById('overlay');		
	  mapOverlay.style.display = 'block';
	  mapOverlay.style.width = '100%';
      var html=document.getElementsByTagName('html');
	  hght=html.item(0).scrollHeight;
      mapOverlay.style.height = hght+'px';
	  mapOverlay.style.minHeight = '1060px';
	  mapOverlay.onclick = function() { hideMyList_search(); };					
	}

    function hideall_popups()
	{
       hideDetail();
	   hide_info_list();
	}

	function hideMyList()
	{
	  myListBox = document.getElementById('fave_list');
	  myListBox.style.display = 'none';
	  document.getElementById('fave_map_div').style.display='none';	  
	  mapOverlay = document.getElementById('overlay');
	  mapOverlay.style.display = 'none';
	}

	function hideMyList_search()
	{
      hideSearch();
	  hideMyList();
 	}

	function hideDetail()
	{
	  if (typeof(are2)!='undefined' && typeof(obj2)!='undefined')
	  {
        document.getElementById(are2).onmouseover = function() { mover(obj2,factor2); };
	    document.getElementById(are2).onmouseout = function() { mout(obj2,factor); };
  	  }
	  myListBox = document.getElementById('list_detail');
	  myListBox.style.display = 'none';
	  myListBox = document.getElementById('map_div');
	  myListBox.style.display = 'none';
	  myListBox = document.getElementById('closebtn3b');
	  myListBox.style.display = 'none';
	  mapOverlay = document.getElementById('overlay');
	  mapOverlay.style.display = 'none';
	  hl = document.getElementById('highlighted');
	  hl.style.display='none';
	}

    function displayservices()
	{
	  hideall_popups();
	  document.getElementById('services_grow').style.display='block';
      $('#services_grow').animate({ left:"170px",opacity: 0.8,width:"1300px"},500,function () { make_services_appear(); });
	}

	function make_services_appear()
	{
  	    document.getElementById('services_grow').style.display='none';
	    document.getElementById('services_grow').style.width='1px';
	    document.getElementById('services_grow').style.left='1470px';
	    searchBox = document.getElementById('services_contain');
   	    searchBox.style.display = 'block';
		mapOverlay = document.getElementById('overlay');
		mapOverlay.style.display = 'block';
		mapOverlay.style.width = '100%';
    	var html=document.getElementsByTagName('html');
		hght=html.item(0).scrollHeight;
		mapOverlay.style.height = hght+'px';
		mapOverlay.style.minHeight = '1060px';
		mapOverlay.onclick = function() { hideservices(); };
	}

	function hideservices() {
	  searchBox = document.getElementById('services_contain');
	  searchBox.style.display = 'none';
	  mapOverlay = document.getElementById('overlay');
	  mapOverlay.style.display = 'none';
	}
</script>


</head>

<body style="background: url(images/countygrad.jpg) no-repeat scroll 0 0 #000;">

<!--START MRN-->

<div id="all">

<!--START NAV-->

<div>
<img src="images/mylist.png" class="hover" style="position: absolute; left: 105px; top: 43px; z-index:5;" onclick="showMyList();" />
<img src="images/mysearch.png" class="hover" style="position: absolute; left: 1050px; top: 39px; z-index:5;" onclick="showSearch();" />
</div>

<!--END NAV-->

<!--START MAP-->

<div id="gp_contain" style="position:relative; z-index: 0;">

<img src="images/1.png" border="0" usemap="#m1" width="200" height="224" class="thumb" id="shape1" style="position:absolute; top: 348px; left: 850px; z-index: 5;" />
<map name="m1" id="m1">
  <area shape="poly" coords="6,5,6,108,21,108,19,208,24,213,35,212,42,207,51,200,73,191,82,179,98,174,113,174,121,172,128,163,131,150,139,153,154,154,173,154,192,125,169,124,159,117,159,106,164,89,154,83,152,70,162,64,156,47,150,36,158,4" id="area1" href="javascript:void(0)" onclick="showDiv('#shape1','area1',51)" href="#">
</map>

<img src="images/2.png" border="0" usemap="#m2" width="132" height="118" class="thumb" id="shape2" style="position:absolute; top: 348px; left: 718px; z-index: 5;" />
<map name="m2" id="m2">
  <area shape="poly" coords="4,6,8,110,16,111,18,107,89,104,88,110,125,109,124,6,87,10,77,25,49,29,46,19,44,5" href="#" id="area2" href="javascript:void(0)" onclick="showDiv('#shape2','area2',52,1.1)">
</map>

<img src="images/3.png" border="0" usemap="#m3" width="123" height="118" class="thumb" id="shape3" style="position:absolute; top: 464px; left: 741px; z-index: 5;" />
<map name="m3" id="m3">
  <area shape="poly" coords="114,5,118,99,108,107,92,109,67,92,54,95,10,111,9,71,5,54,22,75,38,79,44,68,65,68,64,59,75,47,71,38,68,33,72,19,78,7" href="#" id="area3" href="javascript:void(0)" onclick="showDiv('#shape3','area3',53,1.1)">
</map>

<img src="images/4.png" border="0" usemap="#m4" width="143" height="201" class="thumb" id="shape4" style="position:absolute; top: 397px; left: 608px; z-index: 5;" />
<map name="m4" id="m4">
  <area shape="poly" coords="6,6,7,86,19,87,21,122,7,123,6,132,12,141,9,186,24,189,42,190,86,161,101,162,122,184,128,178,130,150,123,144,126,101,102,73,102,5" href="#" id="area4" href="javascript:void(0)" onclick="showDiv('#shape4','area4',54,1.1)">
</map>

<img src="images/5.png" border="0" usemap="#m5" width="184" height="216" class="thumb" id="shape5" style="position:absolute; top: 397px; left: 429px; z-index: 5;" />
<map name="m5" id="m5">
  <area shape="poly" coords="7,4,171,7,170,148,178,151,179,185,167,183,153,186,147,199,124,191,103,204,87,193,90,172,98,154,101,137,94,117,76,112,78,63,66,61,64,47,36,48,39,61,6,64" href="#" id="area5" href="javascript:void(0)" onclick="showDiv('#shape5','area5',55,1.1)">
</map>

<img src="images/6.png" border="0" usemap="#m6" width="327" height="389" class="thumb" id="shape6" style="position:absolute; top: 468px; left: 210px; z-index: 5;" />
<map name="m6" id="m6">
  <area shape="poly" coords="9,8,6,358,19,366,24,380,28,380,32,366,65,343,54,332,55,306,108,280,124,277,150,260,140,235,152,220,179,203,183,188,203,181,217,191,260,145,291,154,317,137,293,113,294,96,299,83,310,74,304,62,291,48,280,43,283,7" href="#" id="area6" href="javascript:void(0)" onclick="showDiv('#shape6','area6',56,1.1)">
</map>

<img src="images/7.png" border="0" usemap="#m7" width="253" height="265" class="thumb" id="shape7" style="position:absolute; top: 202px; left: 210px; z-index: 5;" />
<map name="m7" id="m7">
  <area shape="poly" coords="9,30,9,257,211,255,210,189,249,187,245,59,238,54,241,6,232,6,228,32,204,32,203,57,109,58,113,43,74,44,75,30" href="#" id="area7" href="javascript:void(0)" onclick="showDiv('#shape7','area7',57,1.1)">
</map>

<img src="images/8.png" border="0" usemap="#m8" width="260" height="218" class="thumb" id="shape8" style="position:absolute; top: 178px; left: 457px; z-index: 5;" />
<map name="m8" id="m8">
  <area shape="poly" coords="143,56,253,51,248,79,255,82,252,211,12,212,16,68,4,65,7,35,33,7,142,7" href="#" id="area8" href="javascript:void(0)" onclick="showDiv('#shape8','area8',58,1.1)">
</map>

<img src="images/9.png" border="0" usemap="#m9" width="368" height="185" class="thumb" id="shape9" style="position:absolute; top: 163px; left: 712px; z-index: 5;" />
<map name="m9" id="m9">
  <area shape="poly" coords="5,68,139,68,139,87,266,89,265,33,288,33,313,6,330,4,338,25,348,37,361,47,359,58,343,65,346,78,351,85,334,80,322,87,325,101,337,102,326,114,320,135,324,153,330,163,307,180,88,179,89,161,52,158,54,180,4,179" href="#" id="area9" href="javascript:void(0)" onclick="showDiv('#shape9','area9',59,1.1)">
</map>

<img src="images/sexsmith.png" border="0" usemap="#m14" width="51" height="61" class="thumb" id="sexsmith" style="position:absolute; top: 307px; left: 758px; z-index: 12;" />
<map name="m14" id="m14">
  <area shape="poly" coords="15,23,35,22,33,49,21,49,19,37,14,37" href="#" id="area14" href="javascript:void(0)" onclick="showDiv('#sexsmith','area14',38,2)">
</map>

<img src="images/grande_prairie.png" border="0" usemap="#m10" width="91" height="79" class="thumb" id="grande_prairie" style="position:absolute; top: 459px; left: 725px; z-index: 12;" />
<map name="m10" id="m10">
  <area shape="poly" coords="22,5,69,4,83,11,82,25,67,25,70,51,72,61,65,57,58,58,48,71,46,57,36,57,22,30,10,29,10,23,29,23,29,13" href="#" id="area10" href="javascript:void(0)" onclick="showDiv('#grande_prairie','area10',67,1.1)">
</map>

<img src="images/wembley.png" border="0" usemap="#m12" width="70" height="23" class="thumb" id="wembley" style="position:absolute; top: 493px; left: 607px; z-index: 12;" />
<map name="m12" id="m12">
  <area shape="rect" coords="1,0,15,21" href="#" id="area12" href="javascript:void(0)" onclick="showDiv('#wembley','area12',49,2)">
</map>

<img src="images/hythe.png" border="0" usemap="#m13" width="32" height="40" class="thumb" id="hythe" style="position:absolute; top: 334px; left: 403px; z-index: 12;" />
<map name="m13" id="m13">
  <area shape="poly" coords="7,19,7,32,18,31,21,36,25,36,24,26,18,25" href="#" id="area13" href="javascript:void(0)" onclick="showDiv('#hythe','area13',24,2)">
</map>

<img src="images/beaverlodge.png" border="0" usemap="#m15" width="82" height="23" class="thumb" id="beaverlodge" style="position:absolute; top: 448px; left: 472px; z-index: 12;" />
<map name="m15" id="m15">
  <area shape="poly" coords="2,3,6,7,2,9,1,16,10,16,13,20,15,17,18,16,18,14,14,13,15,2" href="#" id="area15" href="javascript:void(0)" onclick="showDiv('#beaverlodge','area15',5,2)">
</map>

<img src="images/clairmont.png" border="0" usemap="#m16" width="62" height="40" class="thumb" id="clairmont" style="position:absolute; top: 384px; left: 754px; z-index: 12;" />
<map name="m16" id="m16">
  <area shape="poly" coords="25,22,18,28,22,37,28,36,31,33,30,25" href="javascript:void(0)" onclick="showDiv('#clairmont','area16',8,2)" id="area16" />
</map>


</div>

<!--END MAP-->

<!--START POPUPS-->

<div style="width: 100%; min-width: 1020px;">
<center><img src="" id="highlighted" style="position: absolute; z-index: 1000; top: 20%; left: 42%; display: none;" /></center>

<?
   include("subdiv_listings.php");
   include('fave_list.php');
   include('fave_map.php');
   include('search_box.php');
   include('search_results.php');
   include('subdiv_info.php');
   include('map.php');
   include('list_detail.php');
   include('services.php');
?>
<table id="twitter_table" cellpadding="0" cellspacing="0">

<tr>
<td rowspan="2" valign="bottom"><? include('twitter3.php'); ?></td>
<td rowspan="2" valign="bottom" align="center" width="200"><img src="images/woman.png" width="180" /></td>
<td height="187" valign="bottom" align="center"><img src="images/logoplaceholder.png" /><br /><span style="font-family: geneva, tahoma, helvetica, arial, sans-serif; font-size: 12px; color: #666; font-weight: 600;">Grande Prairie Realty Inc.</span></td>
</tr>

<tr>
<td valign="bottom" height="70"><p style="color: #fff; font-size: 36px; margin: 0; font-weight: 600; font-family: 'Myriad Pro', Arial, Helvetica, Sans-serif; text-align: center;text-shadow: 0px 1px 0px #444; line-height: 35px;">Shyla Paige,<br />My REALTOR&reg;</p></td>
</tr>

</table>
<a href="/gp_city5/map20a.php"><img src="images/gotocity.png" class="hover" style="position:absolute; top: 674px; left: 41px; width: 113px; height: 169px;" id="area00" href="javascript:void(0)" /></a>
<a href="javascript:displayservices();"><img src="images/myserv_bl.png" class="hover" style="position:absolute; top: 555px; left: 36px;" /></a>
<a href="http://www.countygp.ab.ca" target="_blank"><img src="images/gpcounty.png" style="position: absolute; left:686px; top: 706px;" /></a>
</div>


<!--END POPUPS-->



</div>
<!--START FOOTER-->

<table cellpadding="0" cellspacing="0" class="mrn_footer" style="width: 1277px; min-width: 1277px; margin: 0; position: absolute; top: 1118px;">
<tr>
<td></td>
<td align="left" width="559">
<p style="font-family: 'Myriad Pro', Helvetica, Arial, Sans-serif; font-size: 23px; letter-spacing: 0px; color: #898989; text-align: center; font-weight: bold; margin: 31px 0 0 21px;">&#169;2011 Emaren Technologies Inc. All Rights Reserved.<br />Patent Pending.</p>
</td>
<td align="right">
<a href="/edituser.php?id=<? echo $_SESSION['uid'];?>" style="font-family: 'Myriad Pro', Helvetica, Arial, Sans-serif; font-size: 25px; color: #fff; font-weight: 600;">My Settings</a>
</td>
</table>

<!--END FOOTER-->

<!--END MRN-->

<div id="overlay">
&nbsp;
</div>

<script type="text/javascript" language="javascript">
area = document.getElementById('area1');
area.onmouseover = function() { mover('#shape1',1.1); };
area.onmouseout = function() { mout('#shape1',1.1); };

area = document.getElementById('area2');
area.onmouseover = function() { mover('#shape2',1.1); };
area.onmouseout = function() { mout('#shape2',1.1); };

area = document.getElementById('area3');
area.onmouseover = function() { mover('#shape3',1.1); };
area.onmouseout = function() { mout('#shape3',1.1); };

area = document.getElementById('area4');
area.onmouseover = function() { mover('#shape4',1.1); };
area.onmouseout = function() { mout('#shape4',1.1); };

area = document.getElementById('area5');
area.onmouseover = function() { mover('#shape5',1.1); };
area.onmouseout = function() { mout('#shape5',1.1); };

area = document.getElementById('area6');
area.onmouseover = function() { mover('#shape6',1.1); };
area.onmouseout = function() { mout('#shape6',1.1); };




area = document.getElementById('area7');
area.onmouseover = function() { mover('#shape7',1.1); };
area.onmouseout = function() { mout('#shape7',1.1); };

area = document.getElementById('area8');
area.onmouseover = function() { mover('#shape8',1.1); };
area.onmouseout = function() { mout('#shape8',1.1); };

area = document.getElementById('area9');
area.onmouseover = function() { mover('#shape9',1.1); };
area.onmouseout = function() { mout('#shape9',1.1); };

area = document.getElementById('area10');
area.onmouseover = function() { mover('#grande_prairie',1.1); };
area.onmouseout = function() { mout('#grande_prairie',1.1); };

area = document.getElementById('area12');
area.onmouseover = function() { mover('#wembley',2); };
area.onmouseout = function() { mout('#wembley',2); };

area = document.getElementById('area13');
area.onmouseover = function() { mover('#hythe',2); };
area.onmouseout = function() { mout('#hythe',2); };


area = document.getElementById('area14');
area.onmouseover = function() { mover('#sexsmith',2); };
area.onmouseout = function() { mout('#sexsmith',2); };

area = document.getElementById('area15');
area.onmouseover = function() { mover('#beaverlodge',2); };
area.onmouseout = function() { mout('#beaverlodge',2); };

area = document.getElementById('area16');
area.onmouseover = function() { mover('#clairmont',2); };
area.onmouseout = function() { mout('#clairmont',2); };

area = document.getElementById('area00');
area.onmouseover = function() { mover('#area00',1.1); };
area.onmouseout = function() { mout('#area00',1.1); };


</script>

<script src="http://twitter.com/javascripts/blogger.js" type="text/javascript"></script>
<script src="http://twitter.com/statuses/user_timeline/div1webdesign.json?callback=twitterCallback2&count=5" type="text/javascript"></script>



</body>
</html>