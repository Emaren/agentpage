<?
   include('../config.php');
   $municipalityid=1;
   $county='n';
   $_SESSION['county']='n';
   $_SESSION['municipalityid']=1;

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
<script type="text/javascript" src="/js/jquery.fancybox-1.3.4.js"></script>
<style type="text/css">
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
	function mover(imgID)
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
	  originalWidth[imgID] = img.width()*1.5;
	  originalHeight[imgID] = img.height()*1.5;
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

	function mout(imgID)
	{
	$(imgID).removeClass("hover").stop()
		.animate({
			top: pos[imgID].top+'px',
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


	function showDiv2(obj,area)
	{
		document.getElementById(area).onmouseover = '';
		document.getElementById(area).onmouseout = '';
		document.body.style.overflow='hidden';
		$(obj).stop();
		name = document.getElementById(obj.substr(1)).id;
		highlight = document.getElementById('highlighted');
		highlight.src = 'images/'+name+'2.png';
		$(obj).css({'z-index' : originalzIndex['#'+name],'width' : originalWidth['#'+name]/1.5, 'height' : originalHeight['#'+name]/1.5, 'top' : pos['#'+name].top+'px'});
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
		closeBtn.onclick = function() { hideDiv(obj,area); };
		closeBtn2.onclick = function() { hideDiv(obj,area); };
		mapOverlay.onclick = function() { hideDiv(obj,area); };
		highlight.onclick = function() { hideDiv(obj,area); };
		obj2=obj;
		are2=area;
	}

    function goback()
	{
	  hideDetail();
	  infoDiv = document.getElementById('info_left_grow');		
	  infoDiv.style.top = '120px';	  
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
	  infoDiv.style.top = '120px';	  
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
		document.body.style.overflow='hidden';
        setTimeout( function() {
		$("a.fncybx").fancybox();
		document.getElementById('pimg2').onclick=function () {$("a.fncybx:first").trigger("click");};
		document.getElementById('pimg').onclick=function () {$("a.fncybx:first").trigger("click");};
		},1000);
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
		document.body.style.overflow='hidden';
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
		document.body.style.overflow='hidden';
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
		document.body.style.overflow='hidden';
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

	function hideDiv(obj,area)
	{
		document.getElementById(area).onmouseover = function() { mover(obj); };
		document.getElementById(area).onmouseout = function() { mout(obj); };
		name = document.getElementById(obj.substr(1)).id;
		$(obj).css({'top' : pos[obj].top+'px',left: pos[obj].left,marginLeft:0,marginTop:0,width: originalWidth[obj]/1.5,height: originalHeight[obj]/1.5});
	    hide_info_list();
		mapOverlay = document.getElementById('overlay');
		mapOverlay.style.display = 'none';
	    hl = document.getElementById('highlighted');
	    hl.style.display='none';
	    document.body.style.overflow='auto';
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
		document.body.style.overflow='hidden';
		document.getElementById('search_grow').style.display='block';
        $('#search_grow').animate({ top:"120px",left: "805px", opacity: 0.8,height:"659px"},500,function () { make_search_appear(); });
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
	    document.body.style.overflow='hidden';
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
	  document.body.style.overflow='auto';
	  searchBox.style.display = 'none';
	  mapOverlay = document.getElementById('overlay');
	  mapOverlay.style.display = 'none';
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

    function make_search_appear()
	{
  	    document.getElementById('search_grow').style.display='none';
	    document.getElementById('search_grow').style.height='1px';
  	    document.getElementById('search_grow').style.top='5%';
  	    document.body.style.overflow='hidden';
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
	  document.body.style.overflow='auto';
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
	  document.body.style.overflow='hidden';
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
	  document.body.style.overflow='auto';
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
	  document.body.style.overflow='auto';
	}

</script>

</head>

<body style="background: url(images/blackgrad2.jpg) no-repeat scroll 0 0 #fff;">

<!--START MRN-->

<div id="all">

<!--START NAV-->

<div>
<div class="flashlink" style="width: 145px; height:138px; position: absolute; top: 23px; left: 104px; z-index: 5;" onclick="showMyList();"></div>
<object style="position: absolute; left: 104px; top: 23px;" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="145" height="138" wmode="transparent">
        <param name="movie" value="../shimmer_list.swf" />
        <param name="wmode" value="transparent" />
        <!--[if !IE]>-->
        <object type="application/x-shockwave-flash" data="../shimmer_list.swf" width="145" height="138" wmode="transparent">
        <!--<![endif]-->
          <img src="images/mylist.png" class="hover" style="position: absolute; left: 104px; top: 23px; z-index:5;" onclick="showMyList();" />
        <!--[if !IE]>-->
        </object>
        <!--<![endif]-->
      </object>
      
      <div class="flashlink" style="width: 267px; height:141px; position:absolute; top: 19px; left: 1050px; z-index: 5;" onclick="showSearch();"></div>
<object wmode="transparent" style="position: absolute; left: 1050px; top: 19px;" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="267" height="141">
        <param name="movie" value="../shimmer_search.swf" />
        <param name="wmode" value="transparent" />
        <!--[if !IE]>-->
        <object wmode="transparent" type="application/x-shockwave-flash" data="../shimmer_search.swf" width="267" height="141">
        <!--<![endif]-->
          <img src="images/mysearch.png" class="hover" style="position: absolute; left: 1050px; top: 19px; z-index:5;" onclick="showMyList();" />
        <!--[if !IE]>-->
        </object>
        <!--<![endif]-->
      </object>
</div>

<!--END NAV-->

<!--START MAP-->

<div id="gp_contain" style=" position:relative; z-index: 0; min-height: 1110px; margin-left: 0px; min-width: 1095px;">

<img src="images/central_park.png" border="0" usemap="#m1" width="313" height="205" class="thumb" id="central_park" style="position:absolute; top: 15px; left: 471px; z-index: 5;" />
<map name="m1" id="m1">
  <area shape="poly" coords="53,45,9,137,5,172,24,194,209,195,217,188,216,136,301,31,193,32,193,5" id="area1" href="javascript:void(0)" onclick="showDiv('#central_park','area1',68)" />
</map>

<img src="images/west_qe2.png" border="0" usemap="#m2" width="171" height="169" class="thumb" id="west_qe2" style="position:absolute; top: 216px; left: 303px; z-index: 5;" />
<map name="m2" id="m2">
  <area shape="poly" coords="4,4,2,107,57,108,58,164,164,163,166,24,146,3" id="area2" href="javascript:void(0)" onclick="showDiv('#west_qe2','area2',69)" />
</map>

<img src="images/edgar_industrial_park.png" border="0" usemap="#m3" width="113" height="227" class="thumb" id="edgar_industrial_park" style="position:absolute; top: 213px; left: 471px; z-index: 5;" />
<map name="m3" id="m3">
  <area shape="poly" coords="104,5,27,6,4,29,4,211,12,220,49,218,48,157,63,115,47,114,60,52,72,37,82,32,105,31" id="area3" href="javascript:void(0)" onclick="showDiv('#edgar_industrial_park','area3',71)" />
</map>

<img src="images/johnstone.png" border="0" usemap="#m4" width="67" height="137" class="thumb" id="johnstone" style="position:absolute; top: 243px; left: 519px; z-index: 5;" />
<map name="m4" id="m4">
  <area shape="poly" coords="59,5,60,34,51,63,52,95,62,118,60,127,5,127,19,85,4,83,17,23,29,10,43,4" id="area4" href="javascript:void(0)" onclick="showDiv('#johnstone','area4',72)" />
</map>

<img src="images/golden_west.png" border="0" usemap="#m5" width="56" height="61" class="thumb" id="golden_west" style="position:absolute; top: 375px; left: 526px; z-index: 5;" />
<map name="m5" id="m5">
  <area shape="rect" coords="0,2,55,61" id="area5" href="javascript:void(0)" onclick="showDiv('#golden_west','area5',73)" />
</map>

<img src="images/kentwood.png" border="0" usemap="#m6" width="124" height="100" class="thumb" id="kentwood" style="position:absolute; top: 214px; left: 573px; z-index: 5;" />
<map name="m6" id="m6">
  <area shape="poly" coords="11,5,12,66,4,91,10,89,47,90,70,83,96,81,114,84,114,13,108,5" id="area6" href="javascript:void(0)" onclick="showDiv('#kentwood','area6',74)" />
</map>

<img src="images/glendale.png" border="0" usemap="#m7" width="69" height="137" class="thumb" id="glendale" style="position:absolute; top: 304px; left: 573px; z-index: 5;" />
<map name="m7" id="m7">
  <area shape="poly" coords="5,8,47,8,57,5,61,16,61,74,54,74,40,96,47,128,9,128,11,49,2,34" id="area7" href="javascript:void(0)" onclick="showDiv('#glendale','area7',75)" />
</map>

<img src="images/normandeau.png" border="0" usemap="#m8" width="82" height="143" class="thumb" id="normandeau" style="position:absolute; top: 299px; left: 614px; z-index: 5;" />
<map name="m8" id="m8">
  <area shape="poly" coords="22,11,39,5,73,4,74,133,12,133,4,103,15,84,26,84,27,17" id="area8" href="javascript:void(0)" onclick="showDiv('#normandeau','area8',76)" />
</map>

<img src="images/northlands.png" border="0" usemap="#m9" width="75" height="99" class="thumb" id="northlands" style="position:absolute; top: 213px; left: 690px; z-index: 5;" />
<map name="m9" id="m9">
  <area shape="poly" coords="5,5,4,84,17,82,28,85,34,90,46,71,60,64,67,60,66,43,41,33,33,24,35,4" id="area9" href="javascript:void(0)" onclick="showDiv('#northlands','area9',77)" />
</map>

<img src="images/chiles_industrial_park.png" border="0" usemap="#m10" width="240" height="177" class="thumb" id="chiles_industrial_park" style="position:absolute; top: 44px; left: 693px; z-index: 5;" />
<map name="m10" id="m10">
  <area shape="poly" coords="86,5,3,113,3,164,68,165,57,152,57,142,64,132,75,124,96,110,105,94,114,86,128,80,145,83,156,91,163,109,163,118,174,151,165,153,173,163,209,163,215,154,218,142,219,135,196,81,178,64,174,56,178,48,182,43,181,34,179,27,194,4" id="area10" href="javascript:void(0)" onclick="showDiv('#chiles_industrial_park','area10',70)" />
</map>

<img src="images/riverside_heavy.png" border="0" usemap="#m11" width="115" height="137" class="thumb" id="riverside_heavy" style="position:absolute; top: 254px; left: 722px; z-index: 5;" />
<map name="m11" id="m11">
  <area shape="poly" coords="45,5,68,17,94,33,108,65,106,93,91,91,80,91,70,97,59,110,57,129,48,125,30,124,31,80,20,71,14,58,9,56,19,37,28,36,44,25" id="area11" href="javascript:void(0)" onclick="showDiv('#riverside_heavy','area11',78)" />
</map>

<img src="images/pines.png" border="0" usemap="#m12" width="93" height="155" class="thumb" id="pines" style="position:absolute; top: 298px; left: 694px; z-index: 5;" />
<map name="m12" id="m12">
  <area shape="poly" coords="4,5,4,133,33,132,36,137,71,144,81,87,68,83,52,83,53,37,42,27,35,15,19,4" id="area12" href="javascript:void(0)" onclick="showDiv('#pines','area12',79)" />
</map>

<img src="images/garden_heights.png" border="0" usemap="#m13" width="83" height="35" class="thumb" id="garden_heights" style="position:absolute; top: 407px; left: 819px; z-index: 5;" />
<map name="m13" id="m13">
  <area shape="rect" coords="2,2,78,28" id="area13" href="javascript:void(0)" onclick="showDiv('#garden_heights','area13',83)" />
</map>

<img src="images/college_park.png" border="0" usemap="#m14" width="91" height="59" class="thumb" id="college_park" style="position:absolute; top: 441px; left: 830px; z-index: 5;" />
<map name="m14" id="m14">
  <area shape="poly" coords="4,4,58,4,73,11,80,23,80,49,29,49,29,45" id="area14" href="javascript:void(0)" onclick="showDiv('#college_park','area14',84)" />
</map>

<img src="images/timberlands.png" border="0" usemap="#m15" width="61" height="116" class="thumb" id="timberlands" style="position:absolute; top: 435px; left: 917px; z-index: 5;" />
<map name="m15" id="m15">
  <area shape="rect" coords="0,2,54,106" id="area15" href="javascript:void(0)" onclick="showDiv('#timberlands','area15',85)" />
</map>

<img src="images/oriole_park.png" border="0" usemap="#m16" width="155" height="156" class="thumb" id="oriole_park" style="position:absolute; top: 436px; left: 455px; z-index: 5;" />
<map name="m16" id="m16">
  <area shape="poly" coords="50,4,126,4,127,71,131,83,139,93,148,99,135,129,124,133,108,133,103,137,86,137,81,129,72,96,58,82,42,78,27,83,21,94,21,108,22,135,19,142,6,124,3,106,28,42,29,27,43,5" id="area16" href="javascript:void(0)" onclick="showDiv('#oriole_park','area16',80)" />
</map>

<img src="images/highland_green.png" border="0" usemap="#m17" width="119" height="87" class="thumb" id="highland_green" style="position:absolute; top: 437px; left: 579px; z-index: 5;" />
<map name="m17" id="m17">
  <area shape="poly" coords="5,6,111,4,109,68,42,68,33,63,24,63,15,71,9,79,3,66" id="area17" href="javascript:void(0)" onclick="showDiv('#highland_green','area17',81)" />
</map>

<img src="images/riverside_light.png" border="0" usemap="#m18" width="77" height="83" class="thumb" id="riverside_light" style="position:absolute; top: 435px; left: 694px; z-index: 5;" />
<map name="m18" id="m18">
  <area shape="poly" coords="6,6,31,5,68,14,66,30,60,48,56,60,51,67,39,71,26,72,16,75,11,70,12,47,6,35" id="area18" href="javascript:void(0)" onclick="showDiv('#riverside_light','area18',82)" />
</map>

<img src="images/riverside_meadows.png" border="0" usemap="#m19" width="129" height="81" class="thumb" id="riverside_meadows" style="position:absolute; top: 502px; left: 584px; z-index: 5;" />
<map name="m19" id="m19">
  <area shape="poly" coords="5,20,23,5,35,10,109,8,118,16,112,22,103,27,90,32,76,40,54,71,37,58,23,37,16,31,7,26" id="area19" href="javascript:void(0)" onclick="showDiv('#riverside_meadows','area19',87)" />
</map>

<img src="images/fairview.png" border="0" usemap="#m20" width="128" height="105" class="thumb" id="fairview" style="position:absolute; top: 540px; left: 517px; z-index: 4;" />
<map name="m20" id="m20">
  <area shape="poly" coords="13,39,19,35,28,38,59,35,71,11,81,6,89,15,97,26,103,31,107,37,117,40,107,52,89,59,84,67,71,88,48,98,28,100,17,97,11,82" id="area20" href="javascript:void(0)" onclick="showDiv('#fairview','area20',86)" />
</map>

<img src="images/west_park.png" border="0" usemap="#m21" width="159" height="131" class="thumb" id="west_park" style="position:absolute; top: 611px; left: 513px; z-index: 5;" />
<map name="m21" id="m21">
  <area shape="poly" coords="16,35,41,42,59,39,74,32,88,21,98,4,106,8,107,14,120,14,140,49,149,115,87,116,58,127,5,47" id="area21" href="javascript:void(0)" onclick="showDiv('#west_park','area21',90)" />
</map>

<img src="images/red_deer_college.png" border="0" usemap="#m22" width="109" height="138" class="thumb" id="red_deer_college" style="position:absolute; top: 723px; left: 570px; z-index: 5;" />
<map name="m22" id="m22">
  <area shape="poly" coords="7,15,80,125,84,119,100,44,92,9,84,8,75,5,51,4,25,6" id="area22" href="javascript:void(0)" onclick="showDiv('#red_deer_college','area22',100)" />
</map>

<img src="images/south_hill.png" border="0" usemap="#m23" width="97" height="183" class="thumb" id="south_hill" style="position:absolute; top: 723px; left: 653px; z-index: 5;" />
<map name="m23" id="m23">
  <area shape="poly" coords="17,7,90,4,91,75,84,66,84,50,44,48,43,168,34,172,4,130,10,126,25,48" id="area23" href="javascript:void(0)" onclick="showDiv('#south_hill','area23',101)" />
</map>

<img src="images/bower.png" border="0" usemap="#m24" width="76" height="119" class="thumb" id="bower" style="position:absolute; top: 772px; left: 693px; z-index: 5;" />
<map name="m24" id="m24">
  <area shape="poly" coords="4,5,37,5,37,14,44,25,49,32,49,42,52,47,53,66,58,63,62,69,63,76,70,81,66,89,58,110,30,109,4,90" id="area24" href="javascript:void(0)" onclick="showDiv('#bower','area24',105)" />
</map>

<img src="images/westerner_park.png" border="0" usemap="#m25" width="77" height="85" class="thumb" id="westerner_park" style="position:absolute; top: 863px; left: 694px; z-index: 5;" />
<map name="m25" id="m25">
  <area shape="poly" coords="5,5,4,76,54,76,54,61,59,47,70,34,67,24,51,23,40,25,26,22,12,10" id="area25" href="javascript:void(0)" onclick="showDiv('#westerner_park','area25',107)" />
</map>

<img src="images/southbrook.png" border="0" usemap="#m26" width="54" height="45" class="thumb" id="southbrook" style="position:absolute; top: 829px; left: 756px; z-index: 5;" />
<map name="m26" id="m26">
  <area shape="poly" coords="4,5,46,5,47,36,18,37" id="area26" href="javascript:void(0)" onclick="showDiv('#southbrook','area26',106)" />
</map>

<img src="images/waste_management_facility.png" border="0" usemap="#m27" width="116" height="118" class="thumb" id="waste_management_facility" style="position:absolute; top: 885px; left: 806px; z-index: 5;" />
<map name="m27" id="m27">
  <area shape="rect" coords="4,4,115,111" id="area27" href="javascript:void(0)" onclick="showDiv('#waste_management_facility','area27',110)" />
</map>

<img src="images/inglewood.png" border="0" usemap="#m28" width="117" height="62" class="thumb" id="inglewood" style="position:absolute; top: 827px; left: 806px; z-index: 5;" />
<map name="m28" id="m28">
  <area shape="rect" coords="5,5,109,54" id="area28" href="javascript:void(0)" onclick="showDiv('#inglewood','area28',108)" />
</map>

<img src="images/vanier_woods.png" border="0" usemap="#m29" width="61" height="62" class="thumb" id="vanier_woods" style="position:absolute; top: 826px; left: 919px; z-index: 5;" />
<map name="m29" id="m29">
  <area shape="rect" coords="5,5,55,55" id="area29" href="javascript:void(0)" onclick="showDiv('#vanier_woods','area29',109)" />
</map>

<img src="images/anders.png" border="0" usemap="#m30" width="117" height="118" class="thumb" id="anders" style="position:absolute; top: 715px; left: 805px; z-index: 5;" />
<map name="m30" id="m30">
  <area shape="rect" coords="2,4,107,105" id="area30" href="javascript:void(0)" onclick="showDiv('#anders','area30',103)" />
</map>

<img src="images/lancaster.png" border="0" usemap="#m31" width="113" height="112" class="thumb" id="lancaster" style="position:absolute; top: 717px; left: 920px; z-index: 5;" />
<map name="m31" id="m31">
  <area shape="poly" coords="4,5,105,4,106,52,53,54,54,105,4,104" id="area31" href="javascript:void(0)" onclick="showDiv('#lancaster','area31',104)" />
</map>

<img src="images/sunnybrook.png" border="0" usemap="#m32" width="69" height="64" class="thumb" id="sunnybrook" style="position:absolute; top: 714px; left: 741px; z-index: 5;" />
<map name="m32" id="m32">
  <area shape="poly" coords="5,10,29,10,61,4,62,55,11,55,12,39,5,34" id="area32" href="javascript:void(0)" onclick="showDiv('#sunnybrook','area32',102)" />
</map>

<img src="images/mountview.png" border="0" usemap="#m33" width="70" height="64" class="thumb" id="mountview" style="position:absolute; top: 662px; left: 739px; z-index: 5;" />
<map name="m33" id="m33">
  <area shape="poly" coords="65,5,65,54,52,54,32,59,11,57,5,22,14,15,24,5" id="area33" href="javascript:void(0)" onclick="showDiv('#mountview','area33',98)" />
</map>

<img src="images/morrisroe.png" border="0" usemap="#m34" width="111" height="56" class="thumb" id="morrisroe" style="position:absolute; top: 661px; left: 807px; z-index: 5;" />
<map name="m34" id="m34">
  <area shape="rect" coords="3,2,107,53" id="area34" href="javascript:void(0)" onclick="showDiv('#morrisroe','area34',99)" />
</map>

<img src="images/eastview.png" border="0" usemap="#m35" width="111" height="57" class="thumb" id="eastview" style="position:absolute; top: 605px; left: 807px; z-index: 5;" />
<map name="m35" id="m35">
  <area shape="rect" coords="3,4,110,56" id="area35" href="javascript:void(0)" onclick="showDiv('#eastview','area35',97)" />
</map>

<img src="images/deer_park.png" border="0" usemap="#m36" width="117" height="117" class="thumb" id="deer_park" style="position:absolute; top: 605px; left: 918px; z-index: 5;" />
<map name="m36" id="m36">
  <area shape="rect" coords="3,4,108,106" id="area36" href="javascript:void(0)" onclick="showDiv('#deer_park','area36',95)" />
</map>

<img src="images/grandview.png" border="0" usemap="#m37" width="75" height="81" class="thumb" id="grandview" style="position:absolute; top: 588px; left: 735px; z-index: 5;" />
<map name="m37" id="m37">
  <area shape="poly" coords="4,9,4,72,20,74,21,68,68,69,67,20,50,20,26,4,18,4,17,11" id="area37" href="javascript:void(0)" onclick="showDiv('#grandview','area37',96)" />
</map>

<img src="images/downtown.png" border="0" usemap="#m38" width="143" height="202" class="thumb" id="downtown" style="position:absolute; top: 526px; left: 621px; z-index: 5;" />
<map name="m38" id="m38">
  <area shape="poly" coords="118,4,125,29,122,37,125,43,128,52,136,58,146,74,137,72,134,77,119,77,117,145,122,147,132,203,54,205,47,139,25,103,22,100,13,101,9,94,2,91,24,78,55,31,97,13,101,5" id="area38" href="javascript:void(0)" onclick="showDiv('#downtown','area38',91)" />
</map>

<img src="images/woodlea.png" border="0" usemap="#m39" width="75" height="58" class="thumb" id="woodlea" style="position:absolute; top: 543px; left: 736px; z-index: 5;" />
<map name="m39" id="m39">
  <area shape="poly" coords="5,10,47,10,61,4,67,9,67,32,50,35,31,33,31,48,17,27,8,21" id="area39" href="javascript:void(0)" onclick="showDiv('#woodlea','area39',89)" />
</map>


<img src="images/mitchener_hill.png" border="0" usemap="#m40" width="94" height="63" class="thumb" id="mitchener_hill" style="position:absolute; top: 547px; left: 765px; z-index: 5;" />
<map name="m40" id="m40">
  <area shape="poly" coords="43,4,86,4,85,54,24,53,15,47,4,44,3,32,14,32,18,35,27,35,42,32" id="area40" href="javascript:void(0)" onclick="showDiv('#mitchener_hill','area40',92)" />
</map>

<img src="images/clearview.png" border="0" usemap="#m41" width="69" height="61" class="thumb" id="clearview" style="position:absolute; top: 547px; left: 852px; z-index: 5;" />
<map name="m41" id="m41">
  <area shape="rect" coords="5,5,63,54" id="area41" href="javascript:void(0)" onclick="showDiv('#clearview','area41',93)" />
</map>

<img src="images/rosedale.png" border="0" usemap="#m42" width="116" height="61" class="thumb" id="rosedale" style="position:absolute; top: 547px; left: 917px; z-index: 5;" />
<map name="m42" id="m42">
  <area shape="rect" coords="3,4,109,53" id="area42" href="javascript:void(0)" onclick="showDiv('#rosedale','area42',94)" />
</map>

<img src="images/wakasoo.png" border="0" usemap="#m43" width="76" height="67" class="thumb" id="wakasoo" style="position:absolute; top: 488px; left: 734px; z-index: 5;" />
<map name="m43" id="m43">
  <area shape="poly" coords="31,4,48,4,48,9,69,40,70,57,10,57,2,29,8,26,15,26,26,18" id="area43" href="javascript:void(0)" onclick="showDiv('#wakasoo','area43',88)" />
</map>
</div>

<!--END MAP-->

<!--START POPUPS-->

<div style="width: 100%; min-width: 1020px;">
<center><img src="" id="highlighted" style="position: absolute; z-index: 1000; top: 20%; left: 570px; display: none;" /></center>

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
   include('referral.php');
?>
<table id="twitter_table" cellpadding="0" cellspacing="0" style="top: 750px;">

<tr style="height: 253px;">
<td height="145" valign="bottom" align="center" style="width: 141px; min-width: 141px; max-width: 141px;">
<a href="#" target="_blank" class="logo_link">

<img src="/images/logoplaceholder.png" />

</a>
<br />
<span style="font-family: geneva, tahoma, helvetica, arial, sans-serif; font-size: 12px; color: #666;">Century 21</span></td>
<td valign="bottom" align="center">

<img src="/images/woman.png" width="175" />
</td>

<td valign="bottom"><? include('twitter3.php'); ?></td>
</tr>

<tr>
<td></td>
<td valign="top" align="center"><p style="color: #fff; margin: 0; font-weight: 600; text-align: center;text-shadow: 0px 1px 0px #444; line-height: 35px;"><a href="" class="realtor_name">Shyla Paige,<br />My REALTOR<span class="reg_symbol">&reg;</span></a></p></td>
<td></td>
</tr>

</table>

<object style="position: absolute; left: 886px; top: 338px;" id="cityreddeerlink" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="325" height="100" wmode="transparent">
        <param name="movie" value="images/cityflash2.swf" />
        <param name="wmode" value="transparent" />
        <!--[if !IE]>-->
        <object type="application/x-shockwave-flash" data="images/cityflash2.swf" id="cityreddeerlink" width="325" height="100" wmode="transparent">
        <!--<![endif]-->
<a href="http://www.reddeer.ca/default.htm" target="_blank"><img src="images/city_of_red_deer.png" style=" position:absolute; top: 338px; left:886px; height: 38px;" /></a>
        <!--[if !IE]>-->
        </object>
        <!--<![endif]-->
      </object>


<a href="/rd_county/map2.php"><img src="images/tocounty.png" class="hover" style="position:absolute; top: 682px; left: 1180px; width: 111px; height: 117px;" id="area00" href="javascript:void(0)" /></a>
<a href="javascript:displayservices();"><img src="images/myservbtn.png" class="hover" style="position:absolute; top: 415px; left: 48px; height: 100px;" /></a>
<a href="javascript:displayref();"><img src="images/reficon.png" class="hover" style="position:absolute; top: 545px; left: 65px; height: 100px;" /></a>
</div>


<!--END POPUPS-->



</div>
<!--START FOOTER-->

<table cellpadding="0" cellspacing="0" class="mrn_footer" style="width: 1420px; margin: 0 0 0 -72px; position: absolute; top: 1080px;">
<tr>
<td align="left" width="670">
<p style="font-family: 'Myriad Pro', Helvetica, Arial, Sans-serif; font-size: 18px; letter-spacing: 0px; color: #000; text-align: center;">&#169;2011 Emaren Technologies Inc. All Rights Reserved.<br />Patent Pending.</p>
</td>
<td align="right">
<a class="settings_btn" href="/rd_edituser.php?id=<? echo $_SESSION['uid'];?>" style="font-family: 'Myriad Pro', Helvetica, Arial, Sans-serif; font-size: 18px; color: #000; text-align: center; font-weight: bold;">My Settings</a>
</td>
</table>

<!--END FOOTER-->

<!--END MRN-->

<div id="overlay">
&nbsp;
</div>

<script type="text/javascript" language="javascript">
area = document.getElementById('area1');
area.onmouseover = function() { mover('#central_park'); };
area.onmouseout = function() { mout('#central_park'); };

area = document.getElementById('area2');
area.onmouseover = function() { mover('#west_qe2'); };
area.onmouseout = function() { mout('#west_qe2'); };

area = document.getElementById('area3');
area.onmouseover = function() { mover('#edgar_industrial_park'); };
area.onmouseout = function() { mout('#edgar_industrial_park'); };

area = document.getElementById('area4');
area.onmouseover = function() { mover('#johnstone'); };
area.onmouseout = function() { mout('#johnstone'); };

area = document.getElementById('area5');
area.onmouseover = function() { mover('#golden_west'); };
area.onmouseout = function() { mout('#golden_west'); };

area = document.getElementById('area6');
area.onmouseover = function() { mover('#kentwood'); };
area.onmouseout = function() { mout('#kentwood'); };

area = document.getElementById('area7');
area.onmouseover = function() { mover('#glendale'); };
area.onmouseout = function() { mout('#glendale'); };

area = document.getElementById('area8');
area.onmouseover = function() { mover('#normandeau'); };
area.onmouseout = function() { mout('#normandeau'); };

area = document.getElementById('area9');
area.onmouseover = function() { mover('#northlands'); };
area.onmouseout = function() { mout('#northlands'); };

area = document.getElementById('area10');
area.onmouseover = function() { mover('#chiles_industrial_park'); };
area.onmouseout = function() { mout('#chiles_industrial_park'); };

area = document.getElementById('area11');
area.onmouseover = function() { mover('#riverside_heavy'); };
area.onmouseout = function() { mout('#riverside_heavy'); };

area = document.getElementById('area12');
area.onmouseover = function() { mover('#pines'); };
area.onmouseout = function() { mout('#pines'); };

area = document.getElementById('area13');
area.onmouseover = function() { mover('#garden_heights'); };
area.onmouseout = function() { mout('#garden_heights'); };

area = document.getElementById('area14');
area.onmouseover = function() { mover('#college_park'); };
area.onmouseout = function() { mout('#college_park'); };

area = document.getElementById('area15');
area.onmouseover = function() { mover('#timberlands'); };
area.onmouseout = function() { mout('#timberlands'); };

area = document.getElementById('area16');
area.onmouseover = function() { mover('#oriole_park'); };
area.onmouseout = function() { mout('#oriole_park'); };

area = document.getElementById('area17');
area.onmouseover = function() { mover('#highland_green'); };
area.onmouseout = function() { mout('#highland_green'); };

area = document.getElementById('area18');
area.onmouseover = function() { mover('#riverside_light'); };
area.onmouseout = function() { mout('#riverside_light'); };

area = document.getElementById('area19');
area.onmouseover = function() { mover('#riverside_meadows'); };
area.onmouseout = function() { mout('#riverside_meadows'); };

area = document.getElementById('area20');
area.onmouseover = function() { mover('#fairview'); };
area.onmouseout = function() { mout('#fairview'); };

area = document.getElementById('area21');
area.onmouseover = function() { mover('#west_park'); };
area.onmouseout = function() { mout('#west_park'); };

area = document.getElementById('area22');
area.onmouseover = function() { mover('#red_deer_college'); };
area.onmouseout = function() { mout('#red_deer_college'); };

area = document.getElementById('area23');
area.onmouseover = function() { mover('#south_hill'); };
area.onmouseout = function() { mout('#south_hill'); };

area = document.getElementById('area24');
area.onmouseover = function() { mover('#bower'); };
area.onmouseout = function() { mout('#bower'); };

area = document.getElementById('area25');
area.onmouseover = function() { mover('#westerner_park'); };
area.onmouseout = function() { mout('#westerner_park'); };

area = document.getElementById('area26');
area.onmouseover = function() { mover('#southbrook'); };
area.onmouseout = function() { mout('#southbrook'); };

area = document.getElementById('area27');
area.onmouseover = function() { mover('#waste_management_facility'); };
area.onmouseout = function() { mout('#waste_management_facility'); };

area = document.getElementById('area28');
area.onmouseover = function() { mover('#inglewood'); };
area.onmouseout = function() { mout('#inglewood'); };

area = document.getElementById('area29');
area.onmouseover = function() { mover('#vanier_woods'); };
area.onmouseout = function() { mout('#vanier_woods'); };

area = document.getElementById('area30');
area.onmouseover = function() { mover('#anders'); };
area.onmouseout = function() { mout('#anders'); };

area = document.getElementById('area31');
area.onmouseover = function() { mover('#lancaster'); };
area.onmouseout = function() { mout('#lancaster'); };

area = document.getElementById('area32');
area.onmouseover = function() { mover('#sunnybrook'); };
area.onmouseout = function() { mout('#sunnybrook'); };

area = document.getElementById('area33');
area.onmouseover = function() { mover('#mountview'); };
area.onmouseout = function() { mout('#mountview'); };

area = document.getElementById('area34');
area.onmouseover = function() { mover('#morrisroe'); };
area.onmouseout = function() { mout('#morrisroe'); };

area = document.getElementById('area35');
area.onmouseover = function() { mover('#eastview'); };
area.onmouseout = function() { mout('#eastview'); };

area = document.getElementById('area36');
area.onmouseover = function() { mover('#deer_park'); };
area.onmouseout = function() { mout('#deer_park'); };

area = document.getElementById('area37');
area.onmouseover = function() { mover('#grandview'); };
area.onmouseout = function() { mout('#grandview'); };

area = document.getElementById('area38');
area.onmouseover = function() { mover('#downtown'); };
area.onmouseout = function() { mout('#downtown'); };

area = document.getElementById('area39');
area.onmouseover = function() { mover('#woodlea'); };
area.onmouseout = function() { mout('#woodlea'); };

area = document.getElementById('area40');
area.onmouseover = function() { mover('#mitchener_hill'); };
area.onmouseout = function() { mout('#mitchener_hill'); };

area = document.getElementById('area41');
area.onmouseover = function() { mover('#clearview'); };
area.onmouseout = function() { mout('#clearview'); };

area = document.getElementById('area42');
area.onmouseover = function() { mover('#rosedale'); };
area.onmouseout = function() { mout('#rosedale'); };

area = document.getElementById('area43');
area.onmouseover = function() { mover('#wakasoo'); };
area.onmouseout = function() { mout('#wakasoo'); };

area = document.getElementById('area00');
area.onmouseover = function() { mover('#area00'); };
area.onmouseout = function() { mout('#area00'); };

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