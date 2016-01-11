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

div#grid_box td.main img#main {
width: auto;
min-width: 600px !important;
max-height: 600px !important;
display: block;
margin-top: 0px;
clear: both;
margin-bottom: 5px;
height: 450px;
max-height: 450px;
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
<img onclick="hide_gallery();" id="closeGallery" class="btns" style="position: relative; left: 585px; margin: 0 !important; top: 25px; z-index: 6000; display: block; width: 49px !important; min-width: 49px !important;height: 49px !important; border: none;" src="images/close_button.png">
<img id="main" src="images/testimg.gif" style="position: relative; top: -10px !important;"/>
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
<script type="text/javascript">

var ims=new Array('first','third','fifth','second','fourth','sixth');
var currentpict=0;

function makeMain(src,no)
{
  document.getElementById('main').src=src;
  document.getElementById(ims[no]).className='selectedphoto';
  document.getElementById(ims[currentpict]).className='';
  currentpict=no;
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
  document.getElementById('main').src=document.getElementById(ims[currentpict]).src;
}

function movePrev()
{
  document.getElementById(ims[currentpict]).className='';
  currentpict--;
  if (currentpict<0)
  {
    currentpict=5;
  }
  document.getElementById(ims[currentpict]).className='selectedphoto';
  document.getElementById('main').src=document.getElementById(ims[currentpict]).src;
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
  hght=getHeight();
  mapOverlay.style.height = hght+'px';
  mapOverlay.onclick = function() { hide_gallery(); };
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
    document.getElementById('main').src=imgs[startat].href; // show the first image
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
  if (m==0)
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