<?
   include('../config.php');
   $municipalityid=1;
   
   if ($_GET['fid']!='' && $_SESSION['uid']!='')
   {
     $sql='delete from tb_user_listings where userid='.$_SESSION['uid'].' and listingid='.$_GET['fid'];
	 $res=mysql_query($sql);        
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
	
	function showDiv2(obj,area)
	{
		document.getElementById(area).onmouseover = '';
		document.getElementById(area).onmouseout = '';
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
		obj2=obj;
		are2=area;
	}

    function goback()
	{
	  hideDetail();
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

    function displayservices()
	{
	  hideall_popups();
	  document.getElementById('services_grow').style.display='block';
      $('#services_grow').animate({ opacity: 0.8,width:"1300px"},500,function () { make_services_appear(); });	
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

<body style="background: url(images/blackgrad2.jpg) no-repeat scroll 0 0 #fff;">

<!--START MRN-->

<div id="all">

<!--START NAV-->

<div>
<img src="images/mylist.png" class="hover" style="position: absolute; left: 104px; top: 23px; z-index:5;" onclick="showMyList();" />
<img src="images/mysearch.png" class="hover" style="position: absolute; left: 1050px; top: 19px; z-index:5;" onclick="showSearch();" />
</div>

<!--END NAV-->

<!--START MAP-->

<div id="gp_contain" style="position:relative; z-index: 0; background: url(images/testriver.png) no-repeat scroll 281px 4px transparent;">

<img src="images/bear_creek_highlands.png" border="0" usemap="#m9" width="131" height="140" class="thumb" id="bear_creek_highlands" style="position:absolute; top: 17px; left: 199px; z-index: 5;" />
<map name="m9" id="m9">
  <area shape="poly" coords="-1,2,-1,135,123,137,129,131,129,5,122,2" id="area9" href="javascript:void(0)" onclick="showDiv('#bear_creek_highlands','area9',4)" />
</map>
<img src="images/arbour_hills.png" border="0" usemap="#m8" class="thumb" width="188" height="78" id="arbour_hills" style="position:absolute; top: 17px; left: 331px; z-index: 5;" />
<map name="m8" id="m8">
  <area shape="poly" coords="3,2,6,73,184,73,189,67,189,8,183,2" id="area8" href="javascript:void(0)" onclick="showDiv('#arbour_hills','area8',2)" />
</map>
<img src="images/northgate.png" border="0" usemap="#m29" class="thumb" id="northgate" width="137" height="224" style="position:absolute; top: 5px; left: 520px; z-index: 5;" />
<map name="m29" id="m29">
  <area shape="poly" coords="1,12,53,12,63,1,64,13,136,13,136,26,136,48,136,63,136,77,135,128,98,128,93,139,93,217,71,206,70,83,3,84" id="area29" href="javascript:void(0)" onclick="showDiv('#northgate','area29',29)" />
</map>
<img src="images/lakeland.png" border="0" usemap="#m27" width="92" height="45" class="thumb" id="lakeland" style="position:absolute; top: 89px; left: 659px; z-index: 5;" />
<map name="m27" id="m27">
  <area shape="poly" coords="85,1,91,6,87,13,45,12,21,41,14,36,1,19,0,7,6,2" id="area27" href="javascript:void(0)" onclick="showDiv('#lakeland','area27',26)" />
</map>
<img src="images/hidden_valley.png" width="78" height="202" border="0" usemap="#m43" class="thumb" id="hidden_valley" style="position: absolute; top: 95px; left: 396px; z-index: 5;" />
<map name="m43" id="m43">
  <area shape="poly" coords="3,1,3,175,3,184,4,199,63,200,64,172,70,157,77,147,65,111,67,1" id="area43" href="javascript:void(0)" onclick="showDiv('#hidden_valley','area43',21)" />
</map>
<img src="images/royal_oaks.png" width="62" height="150" border="0" usemap="#m44" class="thumb" id="royal_oaks" style="position: absolute; top: 95px; left: 462px; z-index: 5;" />
<map name="m44" id="m44">
  <area shape="poly" coords="2,0,2,109,13,146,24,134,60,131,61,1" id="area44" href="javascript:void(0)" onclick="showDiv('#royal_oaks','area44',37)" />
</map>
<img src="images/northridge.png" width="62" height="137" border="0" usemap="#m45" class="thumb" id="northridge" style="position: absolute; top: 94px; left: 525px; z-index: 5;" />
<map name="m45" id="m45">
  <area shape="poly" coords="2,3,3,119,3,134,43,133,55,127,65,116,66,2" id="area45" href="javascript:void(0)" onclick="showDiv('#northridge','area45',30)" /> 
</map>
<img src="images/crystal_ridge.png" height="96" width="69" border="0" usemap="#m22" class="thumb" id="crystal_ridge" style="position:absolute; top: 133px; left: 616px; z-index: 5;" />
<map name="m22" id="m22">
  <area shape="poly" coords="2,3,2,20,2,91,16,93,53,92,66,62,67,50,67,34,65,13,63,13,56,7,48,3,40,4" id="area22" href="javascript:void(0)" onclick="showDiv('#crystal_ridge','area22',16)" />
</map>
<img src="images/crystal_lake_estates.png" border="0" usemap="#m20" width="107" height="137" class="thumb" id="crystal_lake_estates" style="position:absolute; top: 93px; left: 672px; z-index: 5;" />
<map name="m20" id="m20">
  <area shape="poly" coords="13,34,17,25,25,18,30,16,32,11,45,11,53,12,61,13,73,14,78,11,79,2,105,3,107,130,102,134,3,134,11,110,12,98,14,88,14,77,13,70,13,61,11,52,7,43" id="area20" href="javascript:void(0)" onclick="showDiv('#crystal_lake_estates','area20',17)" />
</map>
<img src="images/copperwood.png" border="0" usemap="#m14" class="thumb" width="56" height="67" id="copperwood" style="position:absolute; top: 163px; left: 780px; z-index: 5;" />
<map name="m14" id="m14">
  <area shape="poly" coords="2,2,2,48,2,50,3,53,2,64,55,64,55,2" id="area14" href="javascript:void(0)" onclick="showDiv('#copperwood','area14',11)" />
</map>
<img src="images/airport.png" border="0" usemap="#m7" class="thumb" height="180" width="258" id="airport" style="position:absolute; top: 185px; left: 8px; z-index: 5; background: transparent;" />
<map name="m7" id="m7">
  <area shape="poly" coords="3,12,3,68,10,71,68,72,68,177,74,182,256,181,257,176,256,11,68,10,68,3,54,4,38,3,26,11" id="area7" href="javascript:void(0)" onclick="showDiv('#airport','area7',1)" />
</map>
<img src="images/westgate.png" width="129" height="69" border="0" usemap="#m40" class="thumb" id="westgate" style="position:absolute; top: 296px; left: 267px; z-index: 5;" />
<map name="m40" id="m40">
  <area shape="rect" coords="-2,1,128,66" id="area40" href="javascript:void(0)" onclick="showDiv('#westgate','area40',45)" />
</map>
<img src="images/gateway.png" height="69" width="62" border="0" usemap="#m23" class="thumb" id="gateway" style="position:absolute; top: 296px; left: 397px; z-index: 5;" />
<map name="m23" id="m23">
  <area shape="rect" coords="4,2,62,64" id="area23" href="javascript:void(0)" onclick="showDiv('#gateway','area23',20)" />
</map>
<img src="images/avondale.png" width="119" height="100" border="0" usemap="#m42" class="thumb" id="avondale" style="position: absolute; top: 229px; left: 469px; z-index: 5;" />
<map name="m42" id="m42">
  <area shape="poly" coords="3,26,10,14,19,8,30,4,104,2,104,69,119,69,119,99,70,98,69,92,62,93,56,86,51,85,41,78,39,72,25,70,19,64,14,63,11,50,11,33,7,35" id="area42" href="javascript:void(0)" onclick="showDiv('#avondale','area42',3)" />
</map>
<img src="images/vla.png" width="41" height="100" border="0" usemap="#m47" class="thumb" id="vla" style="position: absolute; top: 229px; left: 574px; z-index: 5;" />
<map name="m47" id="m47">
  <area shape="poly" coords="15,2,2,2,3,65,18,65,18,100,41,100,41,3,28,3" id="area47" href="javascript:void(0)" onclick="showDiv('#vla','area47',50)" />
</map>
<img src="images/mountview.png" border="0" usemap="#m28" class="thumb" id="mountview" width="100" height="68" style="position:absolute; top: 228px; left: 616px; z-index: 5;" />
<map name="m28" id="m28">
  <area shape="rect" coords="3,4,100,63" id="area28" href="javascript:void(0)" onclick="showDiv('#mountview','area28',28)" />
</map>
<img src="images/crystal_heights.png" border="0" usemap="#m19" class="thumb" id="crystal_heights" width="58" height="105" style="position:absolute; top: 228px; left: 717px; z-index: 5;" />
<map name="m19" id="m19">
  <area shape="poly" coords="2,3,2,98,6,104,15,103,26,98,41,89,57,84,58,6,54,2" id="area19" href="javascript:void(0)" onclick="showDiv('#crystal_heights','area19',18)" />
</map>
<img src="images/trumpeter_village.png" border="0" usemap="#m37" height="36" width="69" class="thumb" id="trumpeter_village" style="position:absolute; top: 295px; left: 776px; z-index: 5;" />
<map name="m37" id="m37">
  <area shape="rect" coords="2,2,66,31" id="area37" href="javascript:void(0)" onclick="showDiv('#trumpeter_village','area37',67)" />
</map>
<img src="images/carriage_lane.png" width="69" height="142" border="0" usemap="#m11" class="thumb" id="carriage_lane" style="position:absolute; top: 230px; left: 905px; z-index: 5;" />
<map name="m11" id="m11">
  <area shape="rect" coords="1,3,67,140"  id="area11" href="javascript:void(0)" onclick="showDiv('#carriage_lane','area11',6)" />
</map>
<img src="images/college_park.png" border="0" usemap="#m13" width="79" height="114" class="thumb" id="college_park" style="position:absolute; top: 291px; left: 460px; z-index: 5;" />
<map name="m13" id="m13">
  <area shape="poly" coords="2,4,3,110,19,106,78,105,73,97,72,79,65,61,61,49,64,41,68,31,58,24,51,31,46,28,40,26,35,23,28,15,17,17,12,17,10,11,8,6" id="area13" href="javascript:void(0)" onclick="showDiv('#college_park','area13',10)"  />
</map>
<img src="images/central_business_district.png" width="129" height="86" border="0" usemap="#m48" class="thumb" id="central_business_district" style="position: absolute; top: 322px; left: 523px; z-index: 5;" />
<map name="m48" id="m48">
  <area shape="poly" coords="8,5,2,16,14,67,114,68,120,82,126,83,127,46,117,45,99,9,14,9" id="area48" href="javascript:void(0)" onclick="showDiv('#central_business_district','area48',7)" />
</map>
<img src="images/hillside.png" border="0" usemap="#m25" height="70" width="100" class="thumb" id="hillside" style="position:absolute; top: 296px; left: 616px; z-index: 5;" />
<map name="m25" id="m25">
  <area shape="poly" coords="2,3,2,16,10,35,28,68,96,68,101,62,100,2" id="area25" href="javascript:void(0)" onclick="showDiv('#hillside','area25',23)" />
</map>
<img src="images/ivy_lake_estates.png" width="59" height="54" border="0" usemap="#m26" class="thumb" id="ivy_lake_estates" style="position:absolute; top: 312px; left: 717px; z-index: 5;" />
<map name="m26" id="m26">
  <area shape="poly" coords="3,20,1,47,8,51,54,51,59,47,57,2,44,8,21,21" id="area26" href="javascript:void(0)" onclick="showDiv('#ivy_lake_estates','area26',25)" />
</map>
<img src="images/crystal_landing.png" border="0" usemap="#m21" class="thumb" width="67" height="47" id="crystal_landing" style="position:absolute; top: 328px; left: 778px; z-index: 5;" />
<map name="m21" id="m21">
  <area shape="rect" coords="1,2,65,43" id="area21" href="javascript:void(0)" onclick="showDiv('#crystal_landing','area21',15)" />
</map>

<img src="images/road1.png" style="position: absolute; top: 361px; left: 207px; z-index: 10;" />
<img src="images/road2.png" style="position: absolute; top: 361px; left: 642px; z-index: 10;" />

<img src="images/vision_west.png" width="129" height="67" border="0" usemap="#m38" class="thumb" id="vision_west" style="position:absolute; top: 370px; left: 206px; z-index: 5;" />
<map name="m38" id="m38">
  <area shape="rect" coords="2,2,129,62" id="area38" href="javascript:void(0)" onclick="showDiv('#vision_west','area38',61)" />
</map>
<img src="images/richmond_industrial.png" height="184" width="124" border="0" usemap="#m32" class="thumb" id="richmond_industrial" style="position:absolute; top: 370px; left: 336px; z-index: 5;" />
<map name="m32" id="m32">
  <area shape="poly" coords="5,2,6,117,6,134,48,133,48,151,70,150,69,187,126,188,126,6,120,0" id="area32" href="javascript:void(0)" onclick="showDiv('#richmond_industrial','area32',35)" />
</map>
<img src="images/smith.png" border="0" width="61" height="74" usemap="#m35" class="thumb" id="smith" style="position:absolute; top: 365px; left: 653px; z-index: 5;" />
<map name="m35" id="m35">
  <area shape="poly" coords="2,2,2,25,6,35,17,72,62,72,62,2" id="area35" href="javascript:void(0)" onclick="showDiv('#smith','area35',40)" />
</map>
<img src="images/cobblestone.png" border="0" usemap="#m12" class="thumb" width="65" height="70" id="cobblestone" style="position:absolute; top: 369px; left: 716px; z-index: 5;" />
<map name="m12" id="m12">
  <area shape="rect" coords="0,1,65,72" id="area12" href="javascript:void(0)" onclick="showDiv('#cobblestone','area12',9)" />
</map>
<img src="images/canfor.png" width="93" height="106" border="0" usemap="#m10" class="thumb" id="canfor" style="position:absolute; top: 399px; left: 460px; z-index: 5;" />
<map name="m10" id="m10">
  <area shape="poly" coords="4,9,2,101,5,103,81,102,75,92,80,89,87,89,87,82,90,72,85,71,85,63,89,55,78,57,74,49,77,38,84,26,80,18,77,9,76,2,71,4,65,4,47,4,14,4" id="area10" href="javascript:void(0)" onclick="showDiv('#canfor','area10',63)" />
</map>
<img src="images/swanavon.png" border="0" usemap="#m1" height="116" width="51"  class="thumb" id="swanavon" style="position:absolute; top: 391px; left: 535px; z-index: 5;" />
<map name="m1" id="m1">
  <area shape="poly" coords="3,1,53,2,53,112,10,111,5,104,13,102,15,89,20,81,16,77,17,61,14,57,6,61,4,56,7,48,15,35,8,22,3,14" id="area1" href="javascript:void(0)" onclick="showDiv('#swanavon','area1',44)"  />
</map>
<img src="images/highland_park.png" border="0" usemap="#m2" height="116" width="87"  class="thumb" id="highland_park" style="position:absolute; top: 391px; left: 585px; z-index: 5;" />
<map name="m2" id="m2">
  <area shape="poly" coords="1,0,2,108,9,112,80,112,82,107,47,4,46,3,39,2,36,2,35,2,32,2" href="javascript:void(0)" id="area2" onclick="showDiv('#highland_park','area2',22)"  />
</map>
<img src="images/railtown.png" height="86" width="43" border="0" usemap="#m49" class="thumb" id="railtown" style="position:absolute; top: 437px; left: 671px; z-index: 5;" />
<map name="m49" id="m49">
  <area shape="poly" coords="-1,0,31,80,43,80,43,2" id="area49" href="javascript:void(0)" onclick="showDiv('#railtown','area49',34)" />
</map>
<img src="images/creekside.png" border="0" usemap="#m18" height="78" width="33" class="thumb" id="creekside" style="position:absolute; top: 438px; left: 716px; z-index: 6;" />
<map name="m18" id="m18">
  <area shape="poly" coords="2,2,1,57,34,79,32,2" id="area18" href="javascript:void(0)" onclick="showDiv('#creekside','area18',19)" />
</map>
<img src="images/riverstone.png" width="65" height="135" border="0" usemap="#m33" class="thumb" id="riverstone" style="position:absolute; top: 438px; left: 716px; z-index: 5;" />
<map name="m33" id="m33">
  <area shape="poly" coords="37,2,37,73,32,79,8,62,3,62,1,92,16,131,66,131,65,2" id="area33" href="javascript:void(0)" onclick="showDiv('#riverstone','area33',36)" />
</map>
<img src="images/westpointe.png" height="87" width="63" border="0" usemap="#m41" class="thumb" id="westpointe" style="position:absolute; top: 500px; left: 336px; z-index: 5;" />
<map name="m41" id="m41">
  <area shape="poly" coords="2,3,2,82,5,85,64,85,62,22,40,23,39,2" id="area41" href="javascript:void(0)" onclick="showDiv('#westpointe','area41',46)" />
</map>
<img src="images/mission_heights.png" border="0" usemap="#m3" height="139" width="128" class="thumb" id="mission_heights" style="position:absolute; top: 504px; left: 460px; z-index: 5;" />
<map name="m3" id="m3">
  <area shape="poly" coords="2,2,1,138,114,137,117,130,112,128,117,115,127,121,126,110,123,104,126,100,113,102,109,95,119,92,108,90,105,84,110,81,99,78,97,72,103,68,99,61,94,61,93,53,88,52,88,42,93,40,93,26,86,19,80,3" id="area3" href="javascript:void(0)" onclick="showDiv('#mission_heights','area3',27)"  />
</map>
<img src="images/southview.png" border="0" usemap="#m6" height="93" width="42"  class="thumb" id="southview" style="position:absolute; top: 503px; left: 543px; z-index: 5;" />
<map name="m6" id="m6">
  <area shape="poly" coords="2,2,39,1,42,7,40,87,36,89,31,86,31,72,29,56,20,51,14,49,20,38,21,32,16,33,16,22,3,13" id="area6" href="javascript:void(0)" onclick="showDiv('#southview','area6',41)"  />
</map>
<img src="images/patterson_place.png" border="0" usemap="#m4" height="68" width="109"  class="thumb" id="patterson_place" style="position:absolute; top: 503px; left: 586px; z-index: 5;" />
<map name="m4" id="m4">
  <area shape="poly" coords="2,2,3,65,7,68,102,69,105,63,86,6,84,2" id="area4" href="javascript:void(0)" onclick="showDiv('#patterson_place','area4',32)"  />
</map>
<img src="images/pinnacle_ridge.png" width="124" height="98" border="0" usemap="#m31" class="thumb" id="pinnacle_ridge" style="position:absolute; top: 551px; left: 335px; z-index: 5;" />
<map name="m31" id="m31">
  <area shape="poly" coords="1,36,1,90,6,95,59,95,59,89,127,90,128,2,70,2,70,37" id="area31" href="javascript:void(0)" onclick="showDiv('#pinnacle_ridge','area31',33)" />
</map>
<img src="images/south_patterson.png" border="0" usemap="#m5" height="73" width="132"  class="thumb" id="south_patterson" style="position:absolute; top: 570px; left: 584px; z-index: 5;" />
<map name="m5" id="m5">
  <area shape="poly" coords="2,1,2,17,13,33,18,52,15,70,127,69,131,60,110,7,110,4,97,2" id="area5" href="javascript:void(0)" onclick="showDiv('#south_patterson','area5',42)"  />
</map>
<img src="images/countryside_north.png" width="52" height="72" border="0" usemap="#m16" class="thumb" id="countryside_north" style="position:absolute; top: 573px; left: 729px; z-index: 5;" />
<map name="m16" id="m16">
  <area shape="poly" coords="4,4,19,43,19,71,48,72,50,68,51,2" id="area16" href="javascript:void(0)" onclick="showDiv('#countryside_north','area16',13)" />
</map>
<img src="images/signature_falls.png" width="62" height="72" border="0" usemap="#m34" class="thumb" id="signature_falls" style="position:absolute; top: 573px; left: 783px; z-index: 5;" />
<map name="m34" id="m34">
  <area shape="rect" coords="2,1,62,72" id="area34" href="javascript:void(0)" onclick="showDiv('#signature_falls','area34',39)" />
</map>
<img src="images/obrien_lake.png" border="0" height="75" width="64" usemap="#m30" class="thumb" id="obrien_lake" style="position:absolute; top: 641px; left: 396px; z-index: 5;" />
<map name="m30" id="m30">
  <area shape="rect" coords="2,2,63,72" id="area30" href="javascript:void(0)" onclick="showDiv('#obrien_lake','area30',31)" />
</map>
<img src="images/grande_banks.png" width="109" height="74" border="0" usemap="#m24" class="thumb" id="grande_banks" style="position:absolute; top: 641px; left: 461px; z-index: 5;" />
<map name="m24" id="m24">
  <area shape="poly" coords="2,2,2,67,6,70,18,69,40,70,63,71,62,65,66,59,65,54,71,53,76,51,78,41,78,36,82,31,92,30,87,24,95,22,97,13,107,4,89,4" id="area24" href="javascript:void(0)" onclick="showDiv('#grande_banks','area24',62)" />
</map>
<img src="images/nothing.png" style="position:absolute; top: 643px; left: 460px; width: 188px; height: 276px;" />
<img src="images/country_club.png" width="119" height="70" border="0" usemap="#m15" class="thumb" id="country_club" style="position:absolute; top: 642px; left: 599px; z-index: 5;" />
<map name="m15" id="m15">
  <area shape="poly" coords="4,4,112,1,117,6,118,60,114,67,33,67,35,56,25,48,21,51,9,38,9,24,13,20,7,18,3,10" id="area15" href="javascript:void(0)" onclick="showDiv('#country_club','area15',12)" />
</map>
<img src="images/countryside_south.png" height="73" width="47" border="0" usemap="#m17" class="thumb" id="countryside_south" style="position:absolute; top: 644px; left: 734px; z-index: 6;" />
<map name="m17" id="m17">
  <area shape="rect" coords="2,2,46,71" id="area17" href="javascript:void(0)" onclick="showDiv('#countryside_south','area17',14)" />
</map>
<img src="images/summerside.png" border="0" usemap="#m36" class="thumb" width="97" height="143" id="summerside" style="position:absolute; top: 644px; left: 733px; z-index: 5;" />
<map name="m36" id="m36">
  <area shape="poly" coords="51,2,51,72,1,73,2,134,7,140,79,139,86,127,83,119,91,113,88,101,96,93,96,6,92,2" id="area36" href="javascript:void(0)" onclick="showDiv('#summerside','area36',43)" />
</map>
<img src="images/wedgewood.png" height="72" width="75" border="0" usemap="#m50" class="thumb" id="wedgewood" style="position:absolute; top: 777px; left: 644px; z-index: 5;" />
<map name="m50" id="m50">
  <area shape="poly" coords="15,4,7,13,5,21,2,25,2,38,6,40,17,41,26,44,34,49,41,58,42,64,49,69,70,69,73,65,73,3"  id="area50" href="javascript:void(0)" onclick="showDiv('#wedgewood','area50',48)" />
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
?>
<table id="twitter_table" cellpadding="0" cellspacing="0">

<tr>
<td height="145" valign="bottom" align="center"><a href="http://www.century21gp.com" target="_blank" class="logo_link"><img src="images/logoplaceholder.png" /></a><br /><span style="font-family: geneva, tahoma, helvetica, arial, sans-serif; font-size: 12px; color: #666;">Grande Prairie Realty Inc.</span></td>
<td valign="bottom" align="center"><img src="images/woman.png" width="175" /></td>
<td valign="bottom"><? include('twitter3.php'); ?></td>
</tr>

<tr>
<td></td>
<td valign="top" align="center"><p style="color: #fff; font-size: 36px; margin: 0; font-weight: 600; font-family: 'Myriad Pro', Arial, Helvetica, Sans-serif; text-align: center;text-shadow: 0px 1px 0px #444; line-height: 35px;"><a href="" class="realtor_name">Shyla Paige,<br />My REALTOR&reg;</a></p></td>
<td></td>
</tr>

</table>
<a href="http://www.cityofgp.com" target="_blank"><img src="images/cityofgrandeprairie.png" style=" position:absolute; top: 475px; left:1017px; width: 381px;" /></a>
<a href="/gp_county/map2.php"><img src="images/tocounty.png" class="hover" style="position:absolute; top: 682px; left: 1180px; width: 111px; height: 99px;" id="area00" href="javascript:void(0)" /></a>
<a href="javascript:displayservices();"><img src="images/myserv_bl.png" class="hover" style="position:absolute; top: 442px; left: 61px;" /></a>
</div>


<!--END POPUPS-->



</div>
<!--START FOOTER-->

<table cellpadding="0" cellspacing="0" class="mrn_footer" style="width: 1420px; margin: 0 0 0 -72px; position: absolute; top: 1000px;">
<tr>
<td align="left" width="670">
<p style="font-family: 'Myriad Pro', Helvetica, Arial, Sans-serif; font-size: 18px; letter-spacing: 0px; color: #000; text-align: center;">&#169;2011 Emaren Technologies Inc. All Rights Reserved.<br />Patent Pending.</p>
</td>
<td align="right">
<a href="/edituser.php?id=<? echo $_SESSION['uid'];?>" style="font-family: 'Myriad Pro', Helvetica, Arial, Sans-serif; font-size: 18px; color: #000; text-align: center; font-weight: bold;">My Settings</a>
</td>
</table>

<!--END FOOTER-->

<!--END MRN-->

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

area = document.getElementById('area00');
area.onmouseover = function() { mover('#area00'); };
area.onmouseout = function() { mout('#area00'); };

</script>

<script src="http://twitter.com/javascripts/blogger.js" type="text/javascript"></script>
<script src="http://twitter.com/statuses/user_timeline/div1webdesign.json?callback=twitterCallback2&count=5" type="text/javascript"></script> 

<a name="bottom" style="position:absolute; top: 100%;">&nbsp;</a>


</body>
</html>