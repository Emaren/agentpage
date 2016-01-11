<?
   include('../config.php');
   $municipalityid=4;
   $county='y';
   $_SESSION['county']='y';
   $_SESSION['municipalityid']=3;

   if ($_GET['fid']!='' && $_SESSION['uid']!='')
   {
     $sql='delete from tb_user_listings where userid='.$_SESSION['uid'].' and listingid='.$_GET['fid'];
	 $res=mysql_query($sql);
   }

   $sql='select * from tb_users where id='.$_SESSION['uid'];
   $res=mysql_query($sql);
   $user=mysql_fetch_array($res,MYSQL_ASSOC);

   if ($user['realtor']=='y')
     $realtr=$user;
   else
   {
     $sql='select * from tb_users where id='.$user['realtorid'];
     $res=mysql_query($sql);
     $realtr=mysql_fetch_array($res,MYSQL_ASSOC);
   }

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
<script src="http://widgets.twimg.com/j/2/widget.js"></script>
<script type="text/javascript" src="/js/jquery.fancybox-1.3.4.js"></script>

<style type="text/css">
body {
background: url(images/countygrad.jpg) no-repeat scroll 0 0 #000;	
}

#fancybox-left-ico {
 left: 20px;
}

#fancybox-right-ico {
 right: 20px;
 left: auto;
}
</style>
<script type="text/javascript">

function goToAnchor() {
location.href = "map16a.php#bottom";
}

</script>

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
	  originalWidth[imgID] = img.width();
	  originalHeight[imgID] = img.height();
      pos[imgID] = getPos(imgID);
	  originalzIndex[imgID] = img.css('z-index');
	}
 	  $(imgID).css({'z-index' : '11'});
	  var t=(pos[imgID].top-22)+'px';
	  var w=(originalWidth[imgID]*factor)+'px';
	  var h=(originalHeight[imgID]*factor)+'px';
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
			width: originalWidth[imgID],
			height: originalHeight[imgID]
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

    function getHeight()
    {
    	var html=document.getElementsByTagName('html');
		hght=html.item(0).scrollHeight;
	    if (hght<100)
        {
           var D = document;
           hght=Math.max( Math.max(D.body.scrollHeight, D.documentElement.scrollHeight), Math.max(D.body.offsetHeight, D.documentElement.offsetHeight), Math.max(D.body.clientHeight, D.documentElement.clientHeight));
        }

        return hght;
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
		$(obj).css({'z-index' : originalzIndex['#'+name],'width' : originalWidth['#'+name], 'height' : originalHeight['#'+name], 'top' : pos['#'+name].top+'px'});
		mapOverlay = document.getElementById('overlay');
		closeBtn = document.getElementById('closebtn');
		closeBtn2 = document.getElementById('closebtn2');
	    infoDiv = document.getElementById('info_left_grow');
  	    infoDiv.style.top = '80px';
		document.getElementById('info_left_grow').style.display='block';
		document.getElementById('info_right_grow').style.display='block';
		w=GetWidth();
		woffset=0;
		if (w>1370)
		{
		  woffset=Math.round((w-1370)/2);
		  w=woffset+785;
          t=570+woffset;
		  highlight.style.left=t+'px';
		}
		else
		  w=(w*.6);
		$('#info_right_grow').animate({ top:"80px",left:w,opacity: 0.8,width:'490px',height:"629px"},500,function () { showlisting(w);});
		$('#info_left_grow').animate({ opacity: 0.8,left:woffset+'px',width:'490px',height:"629px"},500,function () {showinfo(woffset);});
		mapOverlay.style.display = 'block';
		mapOverlay.style.width = '100%';
		hght=getHeight();
		mapOverlay.style.height = hght+'px';
		closeBtn.onclick = function() { hideDiv(obj,area,factor); };
		closeBtn2.onclick = function() { hideDiv(obj,area,factor); };
		mapOverlay.onclick = function() { hideDiv(obj,area,factor); };
		highlight.onclick = function() { hideDiv(obj,area,factor); };
		obj2=obj;
		are2=area;
		factor2=factor;
	}



    function goback()
	{
	  hideDetail();
	  infoDiv = document.getElementById('info_left_grow');
	  infoDiv.style.top = '80px';
	  showDiv2(obj2,are2);
	}

	function searchresults_to_search()
	{
	     document.getElementById('searchresults2').style.display='none';
	     document.getElementById('search').style.display='block';
	}

	function goback_search()
	{
	  hideDetail();
 	  infoDiv = document.getElementById('info_left_grow');
	  infoDiv.style.top = '80px';
 	  document.getElementById('info_right_grow').style.display='block';
      $('#info_right_grow').animate({ top:"80px",left:'60%',opacity: 0.8,width:'490px',height:"629px"},500,function () {
  		  mapOverlay = document.getElementById('overlay');
		  mapOverlay.style.display = 'block';
		  mapOverlay.style.width = '100%';
		  hght=getHeight();
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

    function showinfo(offset)
	{
		document.getElementById('info_left_grow').style.display='none';
		document.getElementById('info_left_grow').style.height='1px';
		document.getElementById('info_left_grow').style.width='1px';
		document.getElementById('info_left_grow').style.opacity=0.1;
        setTimeout( function() {
		$("a.fncybx").fancybox();
		document.getElementById('pimg2').onclick=function () {$("a.fncybx:first").trigger("click");};
		document.getElementById('pimg').onclick=function () {$("a.fncybx:first").trigger("click");};
		},1000);
		infoDiv = document.getElementById('info_left_w');
		infoDiv.style.top = '80px';
		infoDiv.style.left = offset+'px';
		infoDiv.style.display = 'block';
		infoDiv = document.getElementById('info_left');
		infoDiv.style.display = 'block';
		highlight = document.getElementById('highlighted');
		highlight.style.display = 'block';
		closeBtn2 = document.getElementById('closebtn');
		closeBtn2.style.display='block';
	}

	function showlisting(offset)
	{
		document.getElementById('info_right_grow').style.display='none';
		document.getElementById('info_right_grow').style.height='1px';
		document.getElementById('info_right_grow').style.width='1px';
		document.getElementById('info_right_grow').style.opacity=0.1;
		document.getElementById('info_right_grow').style.top='664px';
		document.getElementById('info_right_grow').style.left='1300px';
		listDiv = document.getElementById('info_right_w');
		listDiv.style.top = '80px';
		listDiv.style.left = offset+'px';
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
		w=GetWidth();
		woffset=0;
		if (w>1370)
		{
		  woffset=Math.round((w-1370)/2);
		  w=woffset+785;
		}
		else
		  w=(w*.6);
		infoDiv = document.getElementById('map_div');
		infoDiv.style.top = '80px';
		infoDiv.style.left = woffset+'px';
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
		w=GetWidth();
		woffset=0;
		if (w>1370)
		{
		  woffset=Math.round((w-1370)/2);
		  w=woffset+785;
		}
		else
		  w=(w*.6);
		listDivC = document.getElementById('list_detail_contain');
		listDivC.style.left=w+'px';
		listDiv = document.getElementById('list_detail');
		listDiv.style.top = '80px';
		listDiv.style.display = 'block';
        setTimeout( function() {
		$("a.fancybox").fancybox();
	    document.getElementById('loader').style.display='none';
		document.getElementById('houseimg').onclick=function () { setup_gallery(); };});
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
//		$(obj).css({'top' : pos[obj].top+'px',left: pos[obj].left,marginLeft:0,marginTop:0,width: originalWidth[obj]/factor,height: originalHeight[obj]/factor});
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
        $('#search_grow').animate({ top:"80px",left: "805px", opacity: 0.8,height:"659px"},500,function () { make_search_appear(); });
	}

    function displayservices()
	{
	  hideall_popups();
	  document.getElementById('services_grow').style.display='block';
      $('#services_grow').animate({ opacity: 0.8,width:"1214px"},500,function () { make_services_appear(); });
	}

	function make_services_appear()
	{
  	    document.getElementById('services_grow').style.display='none';
	    document.getElementById('services_grow').style.width='1px';
	    searchBox = document.getElementById('services_contain');
   	    searchBox.style.display = 'block';
		mapOverlay = document.getElementById('overlay');
		mapOverlay.style.display = 'block';
		mapOverlay.style.width = '100%';
    	var html=document.getElementsByTagName('html');
		hght=getHeight();
		mapOverlay.style.height = hght+'px';
		mapOverlay.style.minHeight = '1060px';
		mapOverlay.onclick = function() { hideservices(); };
	}

	function displayref() {
	  hideall_popups();
	  document.getElementById('ref_grow').style.display='block';
      $('#ref_grow').animate({ opacity: 0.8,width:"1200px",height:"556px"},500,function () { showref(); });
	}

	function showref()
	{
	document.getElementById('ref_grow').style.display='none';
	document.getElementById('ref_grow').style.height='1px';
	document.getElementById('referral').style.display='block';
	mapOverlay = document.getElementById('overlay');
	mapOverlay.style.display = 'block';
	mapOverlay.style.width = '100%';
    var html=document.getElementsByTagName('html');
	hght=getHeight();
	mapOverlay.style.height = hght+'px';
	mapOverlay.style.minHeight = '1060px';
	mapOverlay.onclick = function() { hideref(); };
	}

	function hideref() {
	document.getElementById('referral').style.display='none';
	mapOverlay = document.getElementById('overlay');
	  mapOverlay.style.display = 'none';
	}

	function hideservices() {
	  searchBox = document.getElementById('services_contain');
	  searchBox.style.display = 'none';
	  mapOverlay = document.getElementById('overlay');
	  mapOverlay.style.display = 'none';
	}

    function make_search_appear()
	{
  	    document.getElementById('search_grow').style.display='none';
	    document.getElementById('search_grow').style.height='1px';
  	    document.getElementById('search_grow').style.top='5%';
	    searchBox = document.getElementById('search');
   	    searchBox.style.display = 'block';
		mapOverlay = document.getElementById('overlay');
		mapOverlay.style.display = 'block';
		mapOverlay.style.width = '100%';
		hght=getHeight();
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
      $('#fave_grow').animate({ top:"80px",opacity: 0.8,height:"659px"},500,function () { make_fave_appear(); });
      $('#fave_map_grow').animate({ top:"80px",opacity: 0.8,height:"659px"},500,function () { });
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
	  hght=getHeight();
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
        document.getElementById(are2).onmouseover = function() { mover(obj2); };
	    document.getElementById(are2).onmouseout = function() { mout(obj2); };
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

</script>

</head>

<body>

<!--START NAV-->

<div>
      
            <object style="position: absolute; left: 26px; top: -8px; z-index:5;" id="shimmer_list" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="300" height="200" wmode="transparent">
        <param name="movie" value="../shimmer_list2.swf" />
        <param name="wmode" value="transparent" />
        <!--[if !IE]>-->
        <object type="application/x-shockwave-flash" data="../shimmer_list2.swf" id="shimmer_list" width="300" height="200" wmode="transparent">
        <!--<![endif]-->
          <img src="images/mylist.png" class="hover" style="position: absolute; left: 104px; top: 23px; z-index:5;" onclick="showMyList();" />
        <!--[if !IE]>-->
        </object>
        <!--<![endif]-->
      </object>


<object wmode="transparent" style="position: absolute; left: 1034px; top: -10px; z-index: 5;" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="300" id="shimmer_search" height="200">
        <param name="movie" value="../shimmer_search2.swf" />
        <param name="wmode" value="transparent" />
        <!--[if !IE]>-->
        <object wmode="transparent" id="shimmer_search" type="application/x-shockwave-flash" data="../shimmer_search2.swf" width="300" height="200">
        <!--<![endif]-->
          <img src="images/mysearch.png" class="hover" style="position: absolute; left: 1050px; top: 19px; z-index:5;" onclick="showMyList();" />
        <!--[if !IE]>-->
        </object>
        <!--<![endif]-->
      </object>
      
      
</div>

<!--END NAV-->


<!--START MRN-->

<div id="all">

<!--START MAP-->

<div id="gp_contain" style="position:relative; z-index: 0; background: url(images/rd_county_river.png) no-repeat scroll 282px 47px transparent;">

<img src="images/1.png" border="0" usemap="#m1" width="339" height="395" class="thumb" id="shape1" style="position:absolute; top: 131px; left: 746px; z-index: 5;" />
<map name="m1" id="m1">
  <area shape="poly" coords="3,72,3,392,46,392,46,362,144,360,144,371,332,371,328,363,334,357,286,255,275,239,267,208,269,145,248,102,252,77,248,51,246,43,241,35,248,23,234,6,218,10,202,14,196,7,179,20,169,31,162,44,163,56,163,68,154,78,140,87,121,91,103,93,88,99,72,99,51,103,33,93,22,73" href="#" id="area1" href="javascript:void(0)" onclick="showDiv('#shape1','area1',111,1.1)" />
</map>

<img src="images/2.png" border="0" usemap="#m2" width="175" height="198" class="thumb" id="shape2" style="position:absolute; top: 98px; left: 573px; z-index: 5;" />
<map name="m2" id="m2">
  <area shape="poly" coords="92,13,102,14,104,8,109,17,113,24,108,35,114,42,116,64,143,88,155,84,170,107,170,193,33,193,33,182,45,165,3,163,31,90,32,108,53,147,62,147,63,155,77,154,78,139,85,136,85,125,91,121,92,69,79,62,77,27,79,22,87,34,94,36" href="#" id="area2" href="javascript:void(0)" onclick="showDiv('#shape2','area2',112,1.1)" />
</map>

<img src="images/3.png" border="0" usemap="#m3" width="265" height="216" class="thumb" id="shape3" style="position:absolute; top: 234px; left: 483px; z-index: 5;" />
<map name="m3" id="m3">
  <area shape="poly" coords="4,5,95,5,85,33,127,32,118,44,118,65,260,65,259,210,89,212,108,180,65,180,61,167,50,169,46,180,33,180,19,169,5,181" href="#" id="area3" href="javascript:void(0)" onclick="showDiv('#shape3','area3',113,1.1)" />
</map>

<img src="images/4.png" width="440" height="215" border="0" usemap="#shape4Map" class="thumb" id="shape4" style="position:absolute; top: 389px; left: 306px; z-index: 5;" />
<map name="shape4Map" id="shape4Map">
  <area shape="poly" coords="436,60,261,61,243,88,236,88,229,82,228,70,236,53,234,19,224,33,207,33,196,25,193,34,155,25,156,14,143,11,134,16,128,38,127,51,112,58,95,70,92,79,89,90,62,94,45,61,35,77,40,99,21,114,11,142,5,156,3,178,128,178,131,196,144,195,145,211,307,210,307,196,406,195,402,134,438,132" href="#" id="area4" href="javascript:void(0)" onclick="showDiv('#shape4','area4',114,1.1)" />
</map>
<img src="images/5.png" border="0" usemap="#m5" width="238" height="436" class="thumb" id="shape5" style="position:absolute; top: 104px; left: 249px; z-index: 5;" />
<map name="m5" id="m5">
  <area shape="poly" coords="74,84,75,235,3,236,4,291,37,294,37,430,46,419,57,424,63,403,78,386,87,373,84,360,102,331,126,363,126,352,125,338,148,324,169,317,168,307,176,298,177,288,189,292,195,280,209,287,227,301,233,294,233,77,222,77,222,28,208,23,200,28,150,27,148,38,116,36,133,60,121,85" href="#" id="area5" href="javascript:void(0)" onclick="showDiv('#shape5','area5',115,1.1)" />
</map>

<img src="images/red_deer.png" border="0" usemap="#m6" width="65" height="124" class="thumb" id="red_deer" style="position:absolute; top: 128px; left: 603px; z-index: 5;" />
<map name="m6" id="m6">
  <area shape="poly" coords="1,15,4,73,27,113,37,110,39,122,47,120,48,101,53,102,51,88,60,85,58,47,47,36,46,17" href="#" id="area6" href="javascript:void(0)" onclick="window.open('http://myagentnow.ca/rd_city/map20a.php','_parent');" />
</map>

<img src="images/sylvan_lake.png" border="0" usemap="#m7" width="92" height="69" class="thumb" id="sylvan_lake" style="position:absolute; top: 130px; left: 441px; z-index: 12;" />
<map name="m7" id="m7">
  <area shape="poly" coords="34,7,34,46,56,47,55,24,62,22,64,32,69,31,69,11,55,10,45,22" href="#" id="area7" href="javascript:void(0)" onclick="showDiv('#sylvan_lake','area7',116,1.5)" />
</map>

<img src="images/innisfail.png" border="0" usemap="#m8" width="85" height="55" class="thumb" id="innisfail" style="position:absolute; top: 423px; left: 536px; z-index: 12;" />
<map name="m8" id="m8">
  <area shape="poly" coords="6,4,43,4,13,52,8,52,9,44,3,45,2,33,9,19" href="#" id="area8" href="javascript:void(0)" onclick="showDiv('#innisfail','area8',118,1.5)" />
</map>

<img src="images/bowden.png" border="0" usemap="#m9" width="76" height="36" class="thumb" id="bowden" style="position:absolute; top: 539px; left: 513px; z-index: 12;" />
<map name="m9" id="m9">
  <area shape="rect" coords="2,1,17,28" href="#" id="area9" href="javascript:void(0)" onclick="showDiv('#bowden','area9',117,1.5)" />
</map>

<img src="images/penhold.png" border="0" usemap="#m10" width="64" height="53" class="thumb" id="penhold" style="position:absolute; top: 318px; left: 566px; z-index: 12;" />
<map name="m10" id="m10">
  <area shape="rect" coords="23,1,38,33" href="#" id="area10" href="javascript:void(0)" onclick="showDiv('#penhold','area10',119,1.5)" />
</map>

<img src="images/delburne.png" border="0" usemap="#m11" width="73" height="39" class="thumb" id="delburne" style="position:absolute; top: 260px; left: 883px; z-index: 12;" />
<map name="m11" id="m11">
  <area shape="rect" coords="27,1,46,20" href="#" id="area11" href="javascript:void(0)" onclick="showDiv('#delburne','area11',121,1.5)" />
</map>

<img src="images/6.png" border="0" usemap="#m12" width="184" height="141" class="thumb" id="shape12" style="position:absolute; top: 95px; left: 483px; z-index: 5;" />
<map name="m12" id="m12">
  <area shape="poly" coords="5,14,9,20,15,22,16,36,22,37,16,43,26,45,27,70,19,70,18,63,15,64,14,85,1,86,2,138,99,138,105,128,104,121,104,112,113,100,117,96,116,90,120,86,120,46,148,45,156,32,161,33,161,23,170,16,171,4,157,13,151,9,143,6,133,7,123,11,114,15,107,19,92,13,83,12,82,17,13,14" id="area12" href="javascript:void(0)" onclick="showDiv('#shape12','area12',122,1.1)" />
</map>
</div>
1

<!--END MAP-->

<!--START POPUPS-->

<div style="width: 100%; min-width: 1020px;">
<center><img src="" id="highlighted" style="position: fixed; z-index: 1000; top: 20%; left: 570px; display: none;" /></center>

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
   include('gallery.php');
   include('referral.php');
?>
<table id="twitter_table" cellpadding="0" cellspacing="0" style="position: absolute; left: 397px; top: 684px;">

<tr>
<td rowspan="2" valign="bottom"><? if ($realtr['twitter_account']!='') include('twitter3.php'); ?></td>
<td rowspan="2" valign="bottom" align="center" width="200">
<?
   if ($realtr['personal_image']!='')
   {
?>
<img src="/images/woman.png<? //echo $realtr['personal_image'];?>" width="180" />
<? }
else {
?>
<img src="/images/woman.png" width="180" />
<?
}
?>
</td>
<td height="187" valign="bottom" align="center"><? if ($realtr['brokerage_website']!='') { ?>
<a href="http://<? echo $realtr['brokerage_website'];?>" target="_blank" class="logo_link">
<? }
   if ($realtr['brokerage_image']!='')
   {
?>
<img src="/images/<? echo $realtr['brokerage_image'];?>" />
<?
   }
   if ($realtr['brokerage_website']!='') { ?>
</a>
<? }
?>
<br />
<span style="font-family: geneva, tahoma, helvetica, arial, sans-serif; font-size: 12px; color: #666;"><? echo $realtr['brokerage_name'];?></span></td>
</tr>

<tr>
<td valign="bottom" height="70">
<p class="realtor_name" style="color: #fff; font-size: 28px; margin: 0; font-weight: 600; text-align: center;text-shadow: 0px 1px 0px #444; line-height: 35px;"><a href="<? echo $realtr['website'];?>" target="_blank" class="realtor_name">Shyla Paige,<br />My REALTOR<span class="reg_symbol">&reg;</span></a></p></td>
</tr>

</table>


<a href="/rd_city/map20a.php"><img src="images/gotocity.png" class="hover" style="position:absolute; top: 595px; left: 42px; width: 113px; height: 169px;" id="area00" href="javascript:void(0)" /></a>

<object style="position: absolute; left: 28px; top: 339px;" id="myservlink" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="150" height="120" wmode="transparent">
        <param name="movie" value="../servicesflash.swf" />
        <param name="wmode" value="transparent" />
        <!--[if !IE]>-->
        <object type="application/x-shockwave-flash" data="../servicesflash.swf" id="myservlink" width="150" height="120" wmode="transparent">
        <!--<![endif]-->
          <a href="javascript:displayservices();"><img src="images/myservbtn.png" class="hover" style="position:absolute; top: 421px; left: 29px; height: 100px;" /></a>
        <!--[if !IE]>-->
        </object>
        <!--<![endif]-->
      </object>


<object style="position: absolute; left: 27px; top: 467px;" id="myreflink" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="150" height="120" wmode="transparent">
        <param name="movie" value="../referralflash.swf" />
        <param name="wmode" value="transparent" />
        <!--[if !IE]>-->
        <object type="application/x-shockwave-flash" data="../referralflash.swf" id="myreflink" width="150" height="120" wmode="transparent">
        <!--<![endif]-->
          <a href="javascript:displayref();"><img src="images/reficon.png" class="hover" style="position:absolute; top: 545px; left: 47px; height: 100px;" /></a>
        <!--[if !IE]>-->
        </object>
        <!--<![endif]-->
      </object>
      
      
<object style="position: absolute; left: 955px; top: 609px;" id="countyreddeerlink" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="385" height="100" wmode="transparent">
        <param name="movie" value="images/countyflash2.swf" />
        <param name="wmode" value="transparent" />
        <!--[if !IE]>-->
        <object type="application/x-shockwave-flash" data="images/countyflash2.swf" id="countyreddeerlink" width="385" height="100" wmode="transparent">
        <!--<![endif]-->
<a href="http://www.countygp.ab.ca" target="_blank"><img src="images/red_deer_county_title.png" style="position: absolute; left:955px; top: 609px;" /></a>
        <!--[if !IE]>-->
        </object>
        <!--<![endif]-->
      </object>
</div>


<!--END POPUPS-->



</div>
<!--START FOOTER-->

<table cellpadding="0" cellspacing="0" class="mrn_footer" style="width: 1277px; margin: 0; position: absolute; top: 1000px; min-width: 1277px;">
<tr>
<td align="left" width="670">
<p style="font-family: 'Myriad Pro', Helvetica, Arial, Sans-serif; font-size: 23px; letter-spacing: 0px; color: #898989; text-align: center; font-weight:bold; margin: 31px 0 0 21px;">&#169;2011 Emaren Technologies Inc. All Rights Reserved.<br />Patent Pending.</p>
</td>
<td align="right">


<?
  $url='/rd_users.php';
  if ($_SESSION['realtor']=='n' and $_SESSION['isadmin']==0)
    $url='/rd_edituser.php?id='.$_SESSION['uid'];
  echo ' <a href="'.$url.'" style="font-family: \'Myriad Pro\', Helvetica, Arial, Sans-serif; font-size: 25px; color: #fff; font-weight: 600;" class="settings_btn">My Settings</a>';  
?>
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

area = document.getElementById('area12');
area.onmouseover = function() { mover('#shape12',1.1); };
area.onmouseout = function() { mout('#shape12',1.1); };

area = document.getElementById('red_deer');
area.onmouseover = function() { mover('#red_deer',1.1); };
area.onmouseout = function() { mout('#red_deer',1.1); };

area = document.getElementById('sylvan_lake');
area.onmouseover = function() { mover('#sylvan_lake',1.5); };
area.onmouseout = function() { mout('#sylvan_lake',1.5); };

area = document.getElementById('innisfail');
area.onmouseover = function() { mover('#innisfail',1.5); };
area.onmouseout = function() { mout('#innisfail',1.5); };

area = document.getElementById('bowden');
area.onmouseover = function() { mover('#bowden',1.5); };
area.onmouseout = function() { mout('#bowden',1.5); };

area = document.getElementById('penhold');
area.onmouseover = function() { mover('#penhold',1.5); };
area.onmouseout = function() { mout('#penhold',1.5); };

area = document.getElementById('delburne');
area.onmouseover = function() { mover('#delburne',1.5); };
area.onmouseout = function() { mout('#delburne',1.5); };

area = document.getElementById('area00');
area.onmouseover = function() { mover('#area00',1.5); };
area.onmouseout = function() { mout('#area00',1.5); };
</script>

<?
  if ($realtr['twitter_account']!='')
  {
?>
<!-- <script src="http://twitter.com/javascripts/blogger.js" type="text/javascript"></script>
<script src="http://twitter.com/statuses/user_timeline/div1webdesign.json?callback=twitterCallback2&count=5" type="text/javascript"></script>  -->
<? } ?>
<a name="bottom" style="position:absolute; top: 100%;">&nbsp;</a>


</body>
</html>