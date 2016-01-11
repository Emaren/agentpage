<style type="text/css">

div#grid_box table td {
  text-align: center;
  width: auto;
  padding: 10px;
}

div#grid_box td img {
border: none;
background: #fff;
padding: 10px;
cursor: pointer;
cursor: hand;
}

div#grid_box td.thumb img {
width: 200px;
box-shadow: 0px 2px 5px #000000;
max-height: 150px;
border: 3px solid #fff;
}

div#grid_box td.main p {
background: #fff;
border: 3px solid #fff;
box-shadow: 0px 2px 5px #000000;
padding: 10px 0 0 0;
margin: 0px;
}

div#grid_box td.main img#main {
display: block;
clear: both;
box-shadow: none;
margin: auto;
}

div#grid_box td.main {
width: auto;
min-width: 600px !important;
min-height: 450px !important;
height: 450px;
max-height: 450px;
width: 600px;
}

img#main {
box-shadow: 0px 2px 5px #000000;
}

.selectedphoto {
border: 3px solid #666 !important;
}

ul#grid li img {
  vertical-align: middle;
}

.btns {
width: 28px !important;
height: 28px !important;
min-width: 28px !important;
border: none !important;
cursor: pointer;
cursor: hand;
background: none !important;
}

</style>


<div id="grid_box" style="width: 1176px;">
<table cellpadding="0" cellspacing="0" style="float:left;">

<tr>
<td class="thumb" valign="middle"><img class="selectedphoto" onmouseover="makeMain(this.src,0)" id="first" src="images/testimg.gif"/></td>

<td class="main" align="center" rowspan="3" valign="middle">
<img onclick="hide_gallery();" id="closeGallery" class="btns" style="position: relative; left: 95%; margin: 0 !important; top: 35px; z-index: 6000; display: block; width: 49px !important; min-width: 49px !important;height: 49px !important; border: none;" src="images/close_button.png">
<p align="center" style="height:460px;width:620px;"><img id="main" src="images/testimg.gif" style="position: relative; top: -10px !important;"/></p>
<img class="btns" src="images/sm_prevset.png" style="padding: 0;" id="previmg" onclick="prev_button();" />
<img class="btns" src="images/sm_back.png" style="padding: 0;" id="prevpict" onclick="movePrev();" />
<img id="nextpict" class="btns" src="images/sm_next.png" style="padding: 0;" onclick="moveNext();" />
<img class="btns" onclick="more_button();" id="moreimg" style="padding: 0;" src="images/sm_nextset.png" />
</td>

<td class="thumb" valign="middle"><img onmouseover="makeMain(this.src,3)" id="second" src="images/testimg.gif"/></td>
</tr>

<tr>
<td class="thumb" valign="middle"><img onmouseover="makeMain(this.src,1);" id="third" src="images/testimg.gif"/></td>
<td class="thumb" valign="middle"><img onmouseover="makeMain(this.src,4);" id="fourth" src="images/testimg.gif"/></td>
</tr>

<tr>
<td class="thumb" valign="middle"><img onmouseover="makeMain(this.src,2);" id="fifth" src="images/testimg.gif"/></td>
<td class="thumb" valign="middle"><img onmouseover="makeMain(this.src,5);" id="sixth" src="images/testimg.gif"/></td>
</tr>

</table>
<div style="clear:both;"></div>
</div>
<img id="tempimg" style="display:none;">
<script type="text/javascript">

var ims=new Array('first','third','fifth','second','fourth','sixth');
var currentpict=0;

function makeMain(src,no)
{
  src=src.replace('_sm1','');
  loadtemp(src);
  document.getElementById(ims[no]).className='selectedphoto';
  document.getElementById(ims[currentpict]).className='';
  currentpict=no;
}

function loadtemp(src)
{
  document.getElementById('tempimg').removeAttribute('height');
  document.getElementById('tempimg').removeAttribute('width');
  document.getElementById('tempimg').src=src;
  document.getElementById('tempimg').onload=function () {resize();};
}

function resize()
{
 img2=document.getElementById('tempimg');
 var aspect = img2.width / img2.height;
 if (img2.width>600)
 {
   img2.height= img2.width / aspect;
   img2.width=600;
  }
  if (img2.height > 450)
  {
     aspect = img2.width / img2.height;
     img2.width = img2.height * aspect;
     img2.height=450;
  }
  document.getElementById('main').src=img2.src;
  if (img2.width==0 && img2.naturalHeight>0)
	document.getElementById('main').width=img2.naturalWidth;
  else
    document.getElementById('main').width=img2.width;
  if (img2.width==0 && img2.naturalWidth>0)
    document.getElementById('main').height=img2.naturalHeight;
  else
    document.getElementById('main').height=img2.height;
}

function moveNext()
{
  document.getElementById(ims[currentpict]).className='';
  currentpict++;
  if (currentpict==6)
  {
    currentpict=0;
  }
  else
    if (document.getElementById(ims[currentpict]).style.display=='none')
      currentpict=0;
  document.getElementById(ims[currentpict]).className='selectedphoto';
  newname=document.getElementById(ims[currentpict]).src;
  newname=newname.replace('_sm1','');
  loadtemp(newname);
}

function movePrev()
{
  document.getElementById(ims[currentpict]).className='';
  currentpict--;
  if (currentpict<0)
  {
    currentpict=5;
	for (j=currentpict;j>0;j--)
	{
	   if (document.getElementById(ims[j]).style.display=='none')
	    currentpict--;
	   else
	     break;
	}
  }
  document.getElementById(ims[currentpict]).className='selectedphoto';
  newname=document.getElementById(ims[currentpict]).src;
  newname=newname.replace('_sm1','');
  loadtemp(newname);
}

function setup_gallery()
{
  w=GetWidth();
  woffset=0;
  if (w>1200)
    w=Math.round((w-1200)/2);
  else
    w=125;
  document.getElementById('grid_box').style.display='block';
  document.getElementById('grid_box').style.left=w+'px';
  mapOverlay = document.getElementById('overlay');
  mapOverlay.style.zIndex=1001;
  mapOverlay.className='dark_overlay';
  mapOverlay.style.display = 'block';
  mapOverlay.style.width = '100%';
  var html=document.getElementsByTagName('html');
  hght=getHeight();
  mapOverlay.style.height = hght+'px';
  mapOverlay.onclick = function() { hide_gallery();hideDetail(); };
  display_picts(0);
}

function display_picts(offset)
{
  pg=document.getElementById('photogallery');
  imgs=pg.getElementsByTagName('a');
  cnt=imgs.length;
  startat=offset;
  document.getElementById('gallery_offset').value=offset;
  if (offset>0)
  {
    document.getElementById('previmg').style.visibility='visible';
  }
  if (offset>cnt)
  {
    document.getElementById('moreimg').style.visibility='hidden';
  }
  else
    document.getElementById('moreimg').style.visibility='visible';
  if (cnt>0)
  {
    newname=imgs[startat].href;
    newname=newname.replace('_sm1','');
    loadtemp(newname);
  }
  k=0;
  to=offset+6;
  if (to>cnt)
    to=cnt;
  for (j=startat;j<to;j++)
  {
    document.getElementById(ims[k]).src=imgs[startat+k].href;
    document.getElementById(ims[k]).style.display='block';
	k++;
  }
  if (j>=cnt)
  {
    document.getElementById('moreimg').style.visibility='hidden';
  }
  else
  {
    document.getElementById('moreimg').style.visibility='visible';
  }
  for (i=k;i<6;i++)
    document.getElementById(ims[i]).style.display='none';
}

function more_button()
{
  offset=document.getElementById('gallery_offset').value*1.0;
  pg=document.getElementById('photogallery');
  imgs=pg.getElementsByTagName('a');
  cnt=imgs.length;
  m=offset+6;
  if (m<=(cnt+5))
    display_picts(m);
}

function prev_button()
{
  offset=document.getElementById('gallery_offset').value*1.0;
  pg=document.getElementById('photogallery');
  imgs=pg.getElementsByTagName('a');
  cnt=imgs.length;
  m=offset-6;
  if (m>=0)
    display_picts(m);
  else
    document.getElementById('previmg').style.visibility='hidden';
}

function hide_gallery()
{
   document.getElementById('overlay').style.zIndex=21;
   document.getElementById('overlay').className='';
   document.getElementById('overlay').onclick=function () { hideDetail(); };
   document.getElementById('grid_box').style.display='none';

}
</script>