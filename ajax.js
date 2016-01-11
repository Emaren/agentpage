var global_button; // where lists are shown it indicates where the the back button should  go (search, detail list, etc)

function getdivision(id,obj,area,factor)
{
	var partition=new Array('100000','200000','300000','400000','500000','600000','700000','800000','900000','1000000','2000000','3000000','4000000','5000000','6000000','7000000','8000000','9000000','10000000');
	var partition_price=new Array('$100,000','$200,000','$300,000','$400,000','$500,000','$600,000','$700,000','$800,000','$900,000','$1,000,000','$2,000,000','$3,000,000','$4,000,000','$5,000,000','$6,000,000','$7,000,000','$8,000,000','$9,000,000','$10,000,000');
    querypage='/getsdivision.php?id='+id;
    var ajaxRequest;  // The variable that makes Ajax possible!
    if(window.ActiveXObject)
       ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
    else
      if(window.XMLHttpRequest)
        ajaxRequest = new XMLHttpRequest();
      else
        return false;
	// Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
          try
            {
               var xmlDoc=document.implementation.createDocument("","",null);
               parser=new DOMParser();
               if (ajaxRequest.responseText.length >0)
               {
                 xmlDoc=parser.parseFromString(ajaxRequest.responseText,"text/xml");
				 var commercial_started=-1;
				   var gallery=document.getElementById('sdiv_pgallery');
				   gallery.innerHTML='';				 
                 car = xmlDoc.getElementsByTagName('info');
				 if (car[0].getAttribute('nohomes'))
				 {
				   info=document.getElementById('info_left_bg');
				   info2=document.getElementById('info_right_bg');
				   if (car[0].getAttribute('tpe')=='res')
				   {
				     info.className='residential';
				     info2.className='residential';
				   }
				   else
				   {
				     info.className='comm';
				     info2.className='comm';				   				   
				     info.style.background='url(images/blackstripe.png) repeat-x scroll 0 -10px #000';
				   }
				   if (car[0].getAttribute('nhomes')=='')
				     no_homes='&nbsp;';
				   else
				     no_homes=car[0].getAttribute('nhomes');
				   document.getElementById('nhomes').innerHTML=no_homes;
				   document.getElementById('nohomes2').innerHTML=car[0].getAttribute('nohomes');				   
				   document.getElementById('divisionname').innerHTML=car[0].getAttribute('name');
				   document.getElementById('pricerange').innerHTML=car[0].getAttribute('min_price')+' - '+car[0].getAttribute('max_price');
				   document.getElementById('taxrange').innerHTML=car[0].getAttribute('min_tax')+' - '+car[0].getAttribute('max_tax');				   
				   document.getElementById('agerange').innerHTML=car[0].getAttribute('min_age')+' - '+car[0].getAttribute('max_age');				 
                   		   if (car[0].getAttribute('convenant')!='')
                   		   {
                   		     document.getElementById('convenant').href=car[0].getAttribute('convenant');
                   		     document.getElementById('convenant').style.display='inline';
                   		   }
                   		   else
				     document.getElementById('convenant').style.display='none';                   		   
                   		   if (car[0].hasChildNodes())
                     		     desc=car[0].firstChild.nodeValue;
                   		   else
                    		     desc='';				 
				   document.getElementById('desc').innerHTML=desc;
				   amenities=car[0].getAttribute('amenities');
				   alist=amenities.split(',');
				   amen='';
				   for (i=0;i<alist.length;i++)
				      amen+=alist[i]+'<br>';
				   document.getElementById('amenities').innerHTML=amen;
				   var ir=document.getElementById('lists');
				   ir.innerHTML = "";
                   var listing = xmlDoc.getElementsByTagName('listing');
				   var price_level=-1;
				   var lastseen=-1;
				   if (listing.length>1)
                  	   document.getElementById('fwdimg').style.display='block';	 
				   else
                  	   document.getElementById('fwdimg').style.display='none';	 				   				   				   				   				   				   				   				   				   				   				   				   
				   for (i=0;i<listing.length;i++)
				   {
			           if (listing[i].getAttribute('listing_type')==1 && commercial_started==2)
					   {
						   d=document.createElement('div');
						   d.className='price_sort';
						   p1=document.createElement('p');
                           p1.innerHTML='';
						   p2=document.createElement('p');
						   p2.innerHTML='Commercial';
						   d.appendChild(p1);
						   d.appendChild(p2);
						   ir.insertBefore(d,ir.firstChild);
						   commercial_started=1;					   					   					   
					   }					   
					   else
  					     commercial_started=listing[i].getAttribute('listing_type');
				      if (listing[i].getAttribute('listing_type')==1)
					   {					    
				         var price=listing[i].getAttribute('price');
 					     price=price.replace('$','');
					     price=price.replace(/,/g,'');					   
					     price=price*1.0;
					     j=partition.length;
					     while (j>=0)
					     {
					       if (price>partition[j] && lastseen!=j)
						   {						   
						     lastseen=j;
						     j--;						   
						     break;
						   }
						   else
						   {
						     if (price>partition[j])
                               break;
						     else
						       j--;					   					   					   
					       }
					     } 
                         if ((lastseen!=price_level))
					     {
						   k=i-1;
						   if ((i>0 && listing[k].getAttribute('listing_type')==1))
						   {						   
						     d=document.createElement('div');
						     d.className='price_sort';
						     p1=document.createElement('p');
						     p1.innerHTML=partition_price[lastseen];
						     p2=document.createElement('p');
						     p2.innerHTML=partition_price[lastseen];
						     p3=document.createElement('p');
						     p3.style.marginRight='20px';
						     p3.innerHTML=partition_price[lastseen];
						     d.appendChild(p1);
						     d.appendChild(p2);
						     d.appendChild(p3);						   						   
						     ir.insertBefore(d,ir.firstChild);					   
					         price_level=lastseen;					   					   
					       }
						   else
						     price_level=lastseen;
						 }					   					   
				       }
					   var tbl=document.createElement("table");
                       if (listing[i].getAttribute('listing_type')!=2)				   
 					     tbl.className='listing_table';
					   else
					     tbl.className='listing_table comm';
					   tbl.onclick = (function() { var b=listing[i].getAttribute('id'); return function() { aj_GetDetail(b,'goback'); }})();
					   tbl.cellPadding='0';
					   tbl.cellSpacing='0';
				       var tblB = document.createElement("tbody");
					   var row = document.createElement("tr");
					   var cell = document.createElement("td");
					   cell.rowSpan=3; //image goes inhere
	 				   cell.width='141px';				  					   					   
					   var timg=document.createElement("img");
				       timg.src='/images/'+listing[i].getAttribute('img');
					   cell.appendChild(timg);					   
					   var cell2 = document.createElement("td");
					   cell2.rowSpan=3;
					   cell2.style.width='245px';
					   cell2.style.minWidth='245px';
					   cell2.style.maxWidth='245px';
					   cell2.style.verticalAlign='top';					   					   
					   cell2.className='large_price';
					   var p2=document.createElement('p');
					   p2.innerHTML=listing[i].getAttribute('price');
					   var p1=document.createElement('p');
					   p1.className='lt_size';
					   p1.innerHTML='Age - '+listing[i].getAttribute('age');
					   cell2.appendChild(p2);
					   cell2.appendChild(p1);
					   var cell3 = document.createElement("td");			  
 				       cell3.style.verticalAlign='middle';	
					   cell3.style.height='35px';				   					   
                       if (listing[i].getAttribute('listing_type')!=2)
  					     cell3.appendChild(document.createTextNode(listing[i].getAttribute('no_beds')+' Beds'));
					   else
					     cell3.appendChild(document.createTextNode(listing[i].getAttribute('acres')+' Acres'));						 
					   var row2 = document.createElement("tr");
					   var cell4= document.createElement("td");
					   cell4.style.verticalAlign='middle';		
					   cell4.style.height='35px';			   
                       if (listing[i].getAttribute('listing_type')!=2)					   					   
 					     cell4.appendChild(document.createTextNode(listing[i].getAttribute('no_baths')+' Baths'));					   
					   var row3 = document.createElement("tr");
					   var cell6=document.createElement("td");
					   cell6.style.verticalAlign='middle';		
					   cell6.style.height='35px';			   
                       if (listing[i].getAttribute('listing_type')!=2)					   
  					     cell6.appendChild(document.createTextNode(listing[i].getAttribute('sq')+' sq.ft.'));					   					  
					   row3.appendChild(cell6);					   
					   row2.appendChild(cell4);
					   row.appendChild(cell);
					   row.appendChild(cell2);	
					   row.appendChild(cell3);					   				   					   
                       tblB.appendChild(row);
					   tblB.appendChild(row2);
					   tblB.appendChild(row3);					   
					   tbl.appendChild(tblB);
					   ir.insertBefore(tbl,ir.firstChild);	
				   }
				   lastseen++;
			       d=document.createElement('div');
				   d.className='price_sort';
				   p1=document.createElement('p');
				   p1.innerHTML=partition_price[lastseen];
				   p2=document.createElement('p');
				   p2.innerHTML=partition_price[lastseen];
				   p3=document.createElement('p');
				   p3.style.marginRight='20px';
				   p3.innerHTML=partition_price[lastseen];
				   d.appendChild(p1);
				   d.appendChild(p2);
				   d.appendChild(p3);						   						   
				   ir.insertBefore(d,ir.firstChild);				   
				   var sgallery=xmlDoc.getElementsByTagName('simages');
			       for (j=0;j<sgallery.length;j++)				 
				   { 
				      a=document.createElement('a');
					  a.className='fncybx';
					  a.setAttribute('rel','gallery');
					  psrc=sgallery[j].getAttribute('src')					  
					  a.href='/images/'+psrc;
					  psrc=psrc.replace('sm1','sm4');					  
                      if (j==0)
 				       document.getElementById('pimg2').src='/images/'+psrc;
                      if (j==1)
 				       document.getElementById('pimg').src='/images/'+psrc;
 					  gallery.appendChild(a);								 				 				 				 				 
				   }				   
				   var schl=document.getElementById('school_container');
				   var schools = xmlDoc.getElementsByTagName('school');
				   var txt='';
				   for (i=0;i<schools.length;i++)
				   {
					  if (schools[i].hasChildNodes())
                        var name=schools[i].firstChild.nodeValue;
                      else
                        var name='';
					  link=schools[i].getAttribute('link');
					  var pos=name.indexOf('/');
					  var nme=name.substr(0,pos);
					  var grade=strrpos(name,' ');
					  var name2=name;
					  var tpe=name.substr(pos+1,(grade-pos));
					  var gr=name2.substr(grade);
                      var m=gr.indexOf('-');
					  var gr2=gr.substr(1,m-1)+' - ';
					  gr2+=gr.substr(m+1);					  
					  if (i==(schools.length-1) && (i/2)==Math.round(i/2))
					    txt=txt+'<div style="float: none;clear: both;margin: 0 auto;" onclick=\'window.open("'+link+'","_blank");\' class="school">';
					  else
                        txt=txt+'<div onclick=\'window.open("'+link+'","_blank");\' class="school">';
					  txt=txt+'<p style="float:left; max-width: 131px; text-align: center; letter-spacing: -1px; font-size: 14px;">'+nme+'<br /><span style="font-size: 11px;">'+tpe+'</span></p>';
					  txt=txt+'<p style="float:right; font-size: 19px; padding-top: 3px; letter-spacing: -1px;">'+gr2+'</p><div style="clear: both; height: 1px;">&nbsp;</div></div>';
				   }
				   schl.innerHTML=txt; 				   
				   showDiv2(obj,area,factor);
				 }
               }  
            }
            catch (e)
            {
              try
              {
                var xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
                xmlDoc.async=false;
                if (ajaxRequest.responseText.length >0)
                {
                  xmlDoc.loadXML(ajaxRequest.responseText);				
                  car = xmlDoc.getElementsByTagName('info');
				   var gallery=document.getElementById('sdiv_pgallery');
				   gallery.innerHTML='';                  
				 if (car[0].getAttribute('nohomes'))
				 {
				   info=document.getElementById('info_left_bg');
				   info2=document.getElementById('info_right');
				   if (car[0].getAttribute('tpe')=='res')
				   {
				     info.className='residential';
				     info2.className='residential';
				   }
				   else
				   {
				     info.className='comm';
				     info2.className='comm';				   				   
				     info.style.background='url(images/blackstripe.png) repeat-x scroll 0 -10px #000';
				   }
				   if (car[0].getAttribute('nhomes')=='')
				     no_homes='&nbsp;';
				   else
				     no_homes=car[0].getAttribute('nhomes');
				   document.getElementById('nhomes').innerHTML=no_homes;
				   document.getElementById('nohomes2').innerHTML=car[0].getAttribute('nohomes');				   
				   document.getElementById('divisionname').innerHTML=car[0].getAttribute('name');
				   document.getElementById('pricerange').innerHTML=car[0].getAttribute('min_price')+' - '+car[0].getAttribute('max_price');
				   document.getElementById('taxrange').innerHTML=car[0].getAttribute('min_tax')+' - '+car[0].getAttribute('max_tax');				   
				   document.getElementById('agerange').innerHTML=car[0].getAttribute('min_age')+' - '+car[0].getAttribute('max_age');				 
                   		   if (car[0].getAttribute('convenant')!='')
                   		   {
                   		     document.getElementById('convenant').href=car[0].getAttribute('convenant');
                   		     document.getElementById('convenant').style.display='inline';
                   		   }
                   		   else
				     document.getElementById('convenant').style.display='none';                   		                      		   
                   		   if (car[0].hasChildNodes())
                     		  	dsc=car[0].firstChild.nodeValue;
                  		   else
                  			dsc='';				 
				   document.getElementById('desc').innerHTML=dsc;
				   amnities=car[0].getAttribute('amenities');
				   alist=amnities.split(',');
				   amn='';
				   for (i=0;i<alist.length;i++)
				      amn+=alist[i]+'<br>';
				   document.getElementById('amenities').innerHTML=amn;
				   var ir=document.getElementById('lists');
				   ir.innerHTML = "";
                   var listing = xmlDoc.getElementsByTagName('listing');
				   var price_level=-1;
				   var lastseen=-1;
				   if (listing.length>1)
                  	   document.getElementById('fwdimg').style.display='block';	 
				   else
                  	   document.getElementById('fwdimg').style.display='none';	 				   				   				   				   				   				   				   				   				   				   				   				   
				   for (i=0;i<listing.length;i++)
				   {
				      if (listing[i].getAttribute('listing_type')==1)
					   {					    
				         var price=listing[i].getAttribute('price');
 					     price=price.replace('$','');
					     price=price.replace(/,/g,'');					   
					     price=price*1.0;
					     j=partition.length;
					     while (j>=0)
					     {
					       if (price>partition[j] && lastseen!=j)
						   {						   
						     lastseen=j;
						     j--;						   
						     break;
						   }
						   else
						   {
						     if (price>partition[j])
                               break;
						     else
						       j--;					   					   					   
					       }
					     } 
                         if ((lastseen!=price_level))
					     {
						   k=i-1;
						   if ((i>0 && listing[k].getAttribute('listing_type')==1))
						   {						   
						     d=document.createElement('div');
						     d.className='price_sort';
						     p1=document.createElement('p');
						     p1.innerHTML=partition_price[lastseen];
						     p2=document.createElement('p');
						     p2.innerHTML=partition_price[lastseen];
						     p3=document.createElement('p');
						     p3.style.marginRight='20px';
						     p3.innerHTML=partition_price[lastseen];
						     d.appendChild(p1);
						     d.appendChild(p2);
						     d.appendChild(p3);						   						   
						     ir.insertBefore(d,ir.firstChild);					   
					         price_level=lastseen;					   					   
					       }
						   else
						     price_level=lastseen;
						 }					   					   
				       }
					   var tbl=document.createElement("table");
                       if (listing[i].getAttribute('listing_type')!=2)				   
 					     tbl.className='listing_table';
					   else
					     tbl.className='listing_table comm';
					   tbl.onclick = (function() { var b=listing[i].getAttribute('id'); return function() { aj_GetDetail(b,'goback'); }})();
					   tbl.cellPadding='0';
					   tbl.cellSpacing='0';
				       var tblB = document.createElement("tbody");
					   var row = document.createElement("tr");
					   var cell = document.createElement("td");
					   cell.rowSpan=3; //image goes inhere
	 				   cell.width='141px';				  					   					   
					   var timg=document.createElement("img");
				       timg.src='/images/'+listing[i].getAttribute('img');
					   cell.appendChild(timg);					   
					   var cell2 = document.createElement("td");
					   cell2.rowSpan=3;
					   cell2.style.width='245px';
					   cell2.style.minWidth='245px';
					   cell2.style.maxWidth='245px';
					   cell2.style.verticalAlign='top';					   					   
					   cell2.className='large_price';
					   var p2=document.createElement('p');
					   p2.innerHTML=listing[i].getAttribute('price');
					   var p1=document.createElement('p');
					   p1.className='lt_size';
					   p1.innerHTML='Age - '+listing[i].getAttribute('age');
					   cell2.appendChild(p2);
					   cell2.appendChild(p1);
					   var cell3 = document.createElement("td");
					   cell3.style.verticalAlign='middle';	
					   cell3.style.height='35px';				   					   
                       if (listing[i].getAttribute('listing_type')!=2)
  					     cell3.appendChild(document.createTextNode(listing[i].getAttribute('no_beds')+' Beds'));
					   else
					     cell3.appendChild(document.createTextNode(listing[i].getAttribute('acres')+' Acres'));						 
					   var row2 = document.createElement("tr");
					   var cell4= document.createElement("td");
					   cell4.style.verticalAlign='middle';		
					   cell4.style.height='35px';			   					   
                       if (listing[i].getAttribute('listing_type')!=2)
  					     cell4.appendChild(document.createTextNode(listing[i].getAttribute('no_baths')+' Baths'));					   
					   var row3 = document.createElement("tr");
					   var cell6=document.createElement("td");
					   cell6.style.verticalAlign='middle';		
					   cell6.style.height='35px';			   
                       if (listing[i].getAttribute('listing_type')!=2)			   
					     cell6.appendChild(document.createTextNode(listing[i].getAttribute('sq')+' sq.ft.'));					   					  
					   row3.appendChild(cell6);					   
					   row2.appendChild(cell4);
					   row.appendChild(cell);
					   row.appendChild(cell2);	
					   row.appendChild(cell3);					   				   					   
                       tblB.appendChild(row);
					   tblB.appendChild(row2);
					   tblB.appendChild(row3);					   
					   tbl.appendChild(tblB);
					   ir.insertBefore(tbl,ir.firstChild);	
			           if (listing[i].getAttribute('listing_type')!=1 && commercial_started=='false')
					   {
						   d=document.createElement('div');
						   d.className='price_sort';
						   p1=document.createElement('p');
                           p1.innerHTML='';
						   p2=document.createElement('p');
						   p2.innerHTML='Commercial';
						   d.appendChild(p1);
						   d.appendChild(p2);
						   ir.insertBefore(d,ir.firstChild);
						   commercial_started='true';					   					   					   
					   }					   
				   }
				   lastseen++;
			       d=document.createElement('div');
				   d.className='price_sort';
				   p1=document.createElement('p');
				   p1.innerHTML=partition_price[lastseen];
				   p2=document.createElement('p');
				   p2.innerHTML=partition_price[lastseen];
				   p3=document.createElement('p');
				   p3.style.marginRight='20px';
				   p3.innerHTML=partition_price[lastseen];
				   d.appendChild(p1);
				   d.appendChild(p2);
				   d.appendChild(p3);						   						   
				   ir.insertBefore(d,ir.firstChild);				   
				   var sgallery=xmlDoc.getElementsByTagName('simages');
			       for (j=0;j<sgallery.length;j++)				 
				   { 
				      a=document.createElement('a');
					  a.className='fncybx';
					  a.setAttribute('rel','gallery');
					  psrc=sgallery[j].getAttribute('src')					  
					  a.href='/images/'+psrc;
					  psrc=psrc.replace('sm1','sm4');					  
                      if (j==0)
 				       document.getElementById('pimg2').src='/images/'+psrc;
                      if (j==1)
 				       document.getElementById('pimg').src='/images/'+psrc;
 					  gallery.appendChild(a);								 				 				 				 				 
				   }				   
				   var schl=document.getElementById('school_container');
				   var schools = xmlDoc.getElementsByTagName('school');
				   var txt='';
				   for (i=0;i<schools.length;i++)
				   {
					  if (schools[i].hasChildNodes())
                        var name=schools[i].firstChild.nodeValue;
                      else
                        var name='';
					  link=schools[i].getAttribute('link');
					  var pos=name.indexOf('/');
					  var nme=name.substr(0,pos);
					  var grade=strrpos(name,' ');
					  var name2=name;
					  var tpe=name.substr(pos+1,(grade-pos));
					  var gr=name2.substr(grade);
                      var m=gr.indexOf('-');
					  var gr2=gr.substr(1,m-1)+' - ';
					  gr2+=gr.substr(m+1);					  
					  if (i==(schools.length-1) && (i/2)==Math.round(i/2))
					    txt=txt+'<div style="float: none;clear: both;margin: 0 auto;" onclick=\'window.open("'+link+'","_blank");\' class="school">';
					  else
                        txt=txt+'<div onclick=\'window.open("'+link+'","_blank");\' class="school">';
					  txt=txt+'<p style="float:left; max-width: 131px; text-align: center;">'+nme+'<br /><span style="font-size: 11px;">'+tpe+'</span></p>';
					  txt=txt+'<p style="float:right; font-size: 20px; padding-top: 3px;">'+gr2+'</p><div style="clear: both; height: 1px;">&nbsp;</div></div>';
				   }
				   schl.innerHTML=txt; 				   
				   showDiv2(obj,area,factor);
				 }
                }
              }
              catch (e)
              {
                alert('error='+e.message); // do nothing
              }
            }
         }
	}
	ajaxRequest.open("GET", querypage, true);
	ajaxRequest.send(null);
}

function strrpos (haystack, needle, offset) 
{
    var i = -1;
    if (offset) {
        i = (haystack + '').slice(offset).lastIndexOf(needle);
        if (i !== -1) {
            i += offset;
        }
    } else {        i = (haystack + '').lastIndexOf(needle);
    }
    return i >= 0 ? i : false;
}

function aj_GetNextinList()
{
    querypage='/getnext.php';
    var ajaxRequest;  // The variable that makes Ajax possible!
    if(window.ActiveXObject)
       ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
    else
      if(window.XMLHttpRequest)
        ajaxRequest = new XMLHttpRequest();
      else
        return false;
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
          try
            {
               var xmlDoc=document.implementation.createDocument("","",null);
               parser=new DOMParser();
               if (ajaxRequest.responseText.length >0)
               {
                 xmlDoc=parser.parseFromString(ajaxRequest.responseText,"text/xml");			   
                 next = xmlDoc.getElementsByTagName('next');
			     id=next[0].getAttribute('id');
				 aj_GetDetail(id,global_button,true);
				 if (next[0].getAttribute('last')=='y')
				   document.getElementById('fwdimg').style.display='none';
				 else
		   		   document.getElementById('fwdimg').style.display='block';	 
               }  
            }
            catch (e)
            {
              try
             {
                var xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
                xmlDoc.async=false;
                if (ajaxRequest.responseText.length >0)
                {
                 xmlDoc.loadXML(ajaxRequest.responseText);
                 next = xmlDoc.getElementsByTagName('next');
			     id=next[0].getAttribute('id');
				 aj_GetDetail(id,global_button,true);
				 if (next[0].getAttribute('last')=='y')
				   document.getElementById('fwdimg').style.display='none';
				 else
		   		   document.getElementById('fwdimg').style.display='block';					   				   
				}				
              }
              catch (e)
              {
                alert(e.message); // do nothing
              }
            }
         }
	}
	ajaxRequest.open("GET", querypage, true);
	ajaxRequest.send(null);
}

function aj_GetDetail(id,back_button,smooth)
{
    global_button=back_button;
    querypage='/getlisting.php?id='+id;
	if (smooth=='undefined')
	  smooth=false;
	if (back_button=='goback_search')
	  document.getElementById('backimg').onclick=function () { goback_search(); };
    else		
	  if (back_button=='daily')
	  {
	    document.getElementById('backimg').onclick=function () { showDaily(); };
	    document.getElementById('daily_w').style.display='none';
	    document.getElementById('daily_grow').style.display='none';
		document.getElementById('fwdimg').style.display='none';	  	  	  	  
	  }
	  else
	    if (back_button!='fave')
  	      document.getElementById('backimg').onclick=function () { goback(); };		
  	    else
	    {
	      document.getElementById('backimg').onclick=function () { showMyList(); };
	      document.getElementById('fave_list').style.display='none';
	      document.getElementById('fave_map_div').style.display='none';
		  document.getElementById('fwdimg').style.display='none';	  	  
	    }
	document.getElementById('fwdimg').onclick=function () {  aj_GetNextinList(); };	  
    var ajaxRequest;  // The variable that makes Ajax possible!
    if(window.ActiveXObject)
       ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
    else
      if(window.XMLHttpRequest)
        ajaxRequest = new XMLHttpRequest();
      else
        return false;
	// Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
          try
            {
               var xmlDoc=document.implementation.createDocument("","",null);
               parser=new DOMParser();
               if (ajaxRequest.responseText.length >0)
               {
                 xmlDoc=parser.parseFromString(ajaxRequest.responseText,"text/xml");			   
				 hide_info_list();
                 gallery = xmlDoc.getElementsByTagName('gallery');				   
				 pg=document.getElementById('photogallery');
				while(pg.firstChild) 	
				    pg.removeChild(pg.firstChild);
				 if (gallery.length>0)
				 {
                   document.getElementById('houseimg').src='/images/'+gallery[0].getAttribute('img');
				   document.getElementById('loader').style.display='block';
                   document.getElementById('noimages').innerHTML=gallery.length+' photos';
			       for (j=0;j<gallery.length;j++)				 
				   { 
				      a=document.createElement('a');
					  a.className='fancybox';
					  a.setAttribute('rel','gallery');					  
					  a.href='/images/'+gallery[j].getAttribute('img');
 					  pg.appendChild(a);								 				 				 				 				 
				   }			   
			     }
				 else
				 {
                   document.getElementById('noimages').innerHTML='0 photos';				 				 
				   document.getElementById('houseimg').src='/images/thumb.jpg';				 
				   document.getElementById('loader').style.display='none';				  
				 }
				 document.getElementById('searchresults').style.display='none';			   
                 car = xmlDoc.getElementsByTagName('info');
  			     document.getElementById('hprice').innerHTML=car[0].getAttribute('price');
				 document.getElementById('hprice').style.verticalAlign='top';				 
				 document.getElementById('sdname').innerHTML=car[0].getAttribute('subdivision');				   
				 add=car[0].getAttribute('address');
				 add=nl2br(add);				   
				 document.getElementById('address').innerHTML=add;
				 document.getElementById('desc2').innerHTML=car[0].getAttribute('desc');
				 document.getElementById('beds').innerHTML=car[0].getAttribute('beds');
				 document.getElementById('beds').style.verticalAlign='middle';
				 document.getElementById('baths').innerHTML=car[0].getAttribute('baths');				 				 
				 document.getElementById('baths').style.verticalAlign='middle';
				 document.getElementById('size').innerHTML=car[0].getAttribute('size');
				 document.getElementById('size').style.verticalAlign='middle';				 
				 document.getElementById('age').innerHTML=car[0].getAttribute('age');
				 document.getElementById('age').style.verticalAlign='middle';				 				 
				 document.getElementById('garage').innerHTML=car[0].getAttribute('garage');				 
				 document.getElementById('taxes').innerHTML=car[0].getAttribute('taxes');
				 document.getElementById('mls').innerHTML=car[0].getAttribute('mls');
				 document.getElementById('rname').innerHTML=car[0].getAttribute('realtorname');
				 document.getElementById('remail').href='mailto:'+car[0].getAttribute('realtoremail');
				 document.getElementById('rimage').src=car[0].getAttribute('realtorimage');
				 document.getElementById('favlinks').href="javascript:addtofavorites("+id+");";
				 document.getElementById('favlinks2').href="javascript:addtofavorites("+id+");";				 
                 document.getElementById('bname').innerHTML=car[0].getAttribute('brokerage_name');
                 		 document.getElementById('bimage').src=car[0].getAttribute('brokerage_image');
                 document.getElementById('rname').innerHTML=car[0].getAttribute('realtorname');
				 if (car[0].getAttribute('personal_website'))
				 {
				   personalws=car[0].getAttribute('personal_website');
				   if (personalws.indexOf('http://')==-1)
				     personalws='http://'+personalws;
                   document.getElementById('rimagelink').href=personalws;				 
                   document.getElementById('rname').href=personalws;				 				 				 
                   document.getElementById('remail').href='mailto:'+car[0].getAttribute('realtoremail');
                   document.getElementById('rimage').src=car[0].getAttribute('realtorimage');
				   brokeragews=car[0].getAttribute('brokerage_website');
				   if (brokeragews.indexOf('http://')==-1)
				     brokeragews='http://'+brokeragews;				 
                   document.getElementById('blink').href=brokeragews;
                   document.getElementById('bname').href=brokeragews;				 				 
			     }
				 hide_info_list();				 
  		         w=GetWidth();
		         w=(w*.6);
		         w=w+490;				 				 
		 		 document.getElementById('info_left_grow').style.display='block';
				 document.getElementById('info_right_grow').style.display='block';
  		         document.getElementById('info_left_grow').style.top='749px';
  		         document.getElementById('info_right_grow').style.top='40px';
  		         document.getElementById('info_right_grow').style.left=w+'px';				 				 				 
  		         document.getElementById('info_left_grow').style.width='1px';
  		         document.getElementById('info_left_grow').style.height='1px';				 					
		         document.getElementById('info_left_grow').style.left='0px';				 						 
				 w=w-490+'px';
				 if (!smooth)
				 {
		           $('#info_right_grow').animate({ left:w,opacity: 0.8,width:'490px',height:"629px"},500,function () { showdetail();});				
		           $('#info_left_grow').animate({ top:"40px",left:"0px",opacity: 0.8,width:'490px',height:"629px"},500,(function () { var lat=car[0].getAttribute('lat'); var long=car[0].getAttribute('long'); return function () { showmap(lat,long); }})());				 								 	 
				 }
				 else
				 {
				   showdetail();
				   var lat=car[0].getAttribute('lat'); 
				   var long=car[0].getAttribute('long');
				   showmap(lat,long);				   				 				 
				 }
			   }			     
            }
            catch (e)
            {
              try
             {
				hide_info_list();
		        document.getElementById('searchresults').style.display='none';				
                var xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
                xmlDoc.async=false;
                if (ajaxRequest.responseText.length >0)
                {
                 xmlDoc.loadXML(ajaxRequest.responseText);
                 gallery = xmlDoc.getElementsByTagName('gallery');				   
				 pg=document.getElementById('photogallery');
 				 while(pg.firstChild) 	
				    pg.removeChild(pg.firstChild);

				 if (gallery.length>0)
				 {
                   document.getElementById('houseimg').src='/images/'+gallery[0].getAttribute('img');
				   document.getElementById('loader').style.display='block';
                   document.getElementById('noimages').innerHTML=gallery.length+' photos';				   				   
			       for (j=0;j<gallery.length;j++)				 
				   { 
				      a=document.createElement('a');
					  a.className='fancybox';
					  a.setAttribute('rel','gallery');
					  a.href='/images/'+gallery[j].getAttribute('img');
 					  pg.appendChild(a);								 				 				 				 				 
				   }			   
			     }
				 else
				 {
                   document.getElementById('noimages').innerHTML='0 photos';				 
				   document.getElementById('houseimg').src='/images/thumb.jpg';				 
				   document.getElementById('loader').style.display='none';				  
				 }								
                 car = xmlDoc.getElementsByTagName('info');			   
  			     document.getElementById('hprice').innerHTML=car[0].getAttribute('price');
				 document.getElementById('hprice').style.verticalAlign='top';				 
				 document.getElementById('sdname').innerHTML=car[0].getAttribute('subdivision');
				 add=car[0].getAttribute('address');
				 add=nl2br(add);				   				   
				 document.getElementById('address').innerHTML=add;
				 document.getElementById('desc2').innerHTML=car[0].getAttribute('desc');
				 document.getElementById('beds').innerHTML=car[0].getAttribute('beds');
				 document.getElementById('beds').style.verticalAlign='middle';
				 document.getElementById('baths').innerHTML=car[0].getAttribute('baths');				 				 
				 document.getElementById('baths').style.verticalAlign='middle';
				 document.getElementById('size').innerHTML=car[0].getAttribute('size');
				 document.getElementById('size').style.verticalAlign='bottom';				 
				 document.getElementById('age').innerHTML=car[0].getAttribute('age');
				 document.getElementById('age').style.verticalAlign='top';				 
				 document.getElementById('garage').innerHTML=car[0].getAttribute('garage');				 
				 document.getElementById('taxes').innerHTML=car[0].getAttribute('taxes');
				 document.getElementById('mls').innerHTML=car[0].getAttribute('mls');				 
				 document.getElementById('rname').innerHTML=car[0].getAttribute('realtorname');
				 document.getElementById('remail').href='mailto:'+car[0].getAttribute('realtoremail');
				 document.getElementById('rimage').src=car[0].getAttribute('realtorimage');
				 document.getElementById('favlinks').href="javascript:addtofavorites("+id+");";								 				 								 				 				 				 				 
				 document.getElementById('favlinks2').href="javascript:addtofavorites("+id+");";
		                 document.getElementById('bname').innerHTML=car[0].getAttribute('brokerage_name');
				 document.getElementById('bimage').src=car[0].getAttribute('brokerage_image');                 
                 		 document.getElementById('rname').innerHTML=car[0].getAttribute('realtorname');
                 		 document.getElementById('remail').href='mailto:'+car[0].getAttribute('realtoremail');
                 		 document.getElementById('rimage').src=car[0].getAttribute('realtorimage');
                 		 document.getElementById('rimagelink').href=car[0].getAttribute('personal_website');				 
                 		 document.getElementById('rname').href=car[0].getAttribute('personal_website');				 				 
                 		 document.getElementById('blink').href=car[0].getAttribute('brokerage_website');
                 		 document.getElementById('bname').href=car[0].getAttribute('brokerage_website');				 				 
 				 document.getElementById('info_left_grow').style.display='block';
				 document.getElementById('info_right_grow').style.display='block';
  		 	         document.getElementById('info_left_grow').style.top='749px';
  		  	         document.getElementById('info_left_grow').style.width='1px';
  		         	 document.getElementById('info_left_grow').style.height='1px';				 					
		         	 document.getElementById('info_left_grow').style.left='0px';				 						 				 						 
				 if (!smooth)
				 {
		           $('#info_right_grow').animate({ left:w,opacity: 0.8,width:'490px',height:"629px"},500,function () { showdetail();});				
		           $('#info_left_grow').animate({ top:"40px",left:"0px",opacity: 0.8,width:'490px',height:"629px"},500,(function () { var lat=car[0].getAttribute('lat'); var long=car[0].getAttribute('long'); return function () { showmap(lat,long); }})());				 								                  
				 }
				 else
				 {
				   showdetail();
				   var lat=car[0].getAttribute('lat'); 
				   var long=car[0].getAttribute('long');
				   showmap(lat,long);				   				 				 
				 }
				}				
              }
              catch (e)
              {
                alert(e.message); // do nothing
              }
            }
         }
	}
	ajaxRequest.open("GET", querypage, true);
	ajaxRequest.send(null);
}

function nl2br(text){
text=escape(text);
return unescape(text.replace(/(%5Cr%5Cn)|(%5Cn%5Cr)|%5Cr|%5Cn/g,'<br />'));
}

function addtofavorites(id)
{
    querypage='/addfav.php?id='+id;
    var ajaxRequest;  // The variable that makes Ajax possible!
    if(window.ActiveXObject)
       ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
    else
      if(window.XMLHttpRequest)
        ajaxRequest = new XMLHttpRequest();
      else
        return false;
	// Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
          try
            {
               var xmlDoc=document.implementation.createDocument("","",null);
               parser=new DOMParser();
               if (ajaxRequest.responseText.length >0)
               {
                 xmlDoc=parser.parseFromString(ajaxRequest.responseText,"text/xml");			   
                 car = xmlDoc.getElementsByTagName('action');
  			     if (car[0].getAttribute('id')=='200')
				 {
				   alert('Added to your favorites list');
				   var tbl=document.createElement('table');
				   tbl.onclick = (function() { var b=car[0].getAttribute('fid'); return function() { aj_GetDetail(b,'fave'); }})();				   
				   tbl.className="fave_table";
				   tbl.cellPadding="0";
				   tbl.cellSpacing="0";
				   tbl.width="95%";
				   tbl.style.margin="10px auto";
				   var tblB=document.createElement('tbody');
				   var tr1=document.createElement('tr');
                   var td1=document.createElement('td');
				   td1.rowSpan=2;
				   td1.style.textAlign='left';
				   td1.style.width="125";
				   var im=document.createElement('img');
				   im.style.width='125px';
				   if (car[0].getAttribute('image')=='.')
				     im.src='/images/thumb.jpg';
				   else
  				     im.src='/images/'+car[0].getAttribute('image');
				   td1.appendChild(im);
				   d1=document.createElement('div');
                   d1.style.padding='5px 0 0 0;';
				   im2=document.createElement('img');
				   im2.src='/images/failed.png';
				   im2.style.width='20px';
				   d1.appendChild(im2);
				   a4=document.createElement('a');
				   a4.onclick="return confirm('Are you sure you want to delete this favorite?')";
				   a4.className='del_fav';
				   a4.href="/GP_city7/map20a.php?fid="+car[0].getAttribute('fid');
				   a4.innerHTML='delete';
				   d1.appendChild(a4);
				   td1.appendChild(d1);
				   tr1.appendChild(td1);
                   var td2=document.createElement('td');
				   var p1=document.createElement('p');
				   p1.className='price';
				   p1.innerHTML='$'+car[0].getAttribute('price');
				   td2.appendChild(p1);
				   tr1.appendChild(td2);
                   tblB.appendChild(tr1);
				   var tr2=document.createElement('tr');
				   var td3=document.createElement('td');
				   var p2=document.createElement('p');
				   p2.innerHTML=car[0].getAttribute('sname');
				   td3.appendChild(p2);
				   tr2.appendChild(td3);
				   tblB.appendChild(tr2);
				   var tr3=document.createElement('tr');
				   var td4=document.createElement('td');
				   td4.colSpan='2';
				   var img2=document.createElement('img');
				   img2.src='/images/break.jpg';				   				   				   
				   td4.appendChild(img2);
				   tr3.appendChild(td4);
				   tblB.appendChild(tr3);
				   tbl.appendChild(tblB);
				   document.getElementById('favelist').appendChild(tbl);
				 }
				 else
				 {
                   if (car[0].hasChildNodes())
                     alert(car[0].firstChild.nodeValue);
                   else
                     alert('There was an unspecified error');
                 }
			   }  
            }
            catch (e)
            {
			  alert(e.message);
              try
              {
				hide_info_list();
                var xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
                xmlDoc.async=false;
                if (ajaxRequest.responseText.length >0)
                {
                  xmlDoc.loadXML(ajaxRequest.responseText);				
                  car = xmlDoc.getElementsByTagName('action');
   			      if (car[0].getAttribute('id')=='200')
				   alert('Added to your favorites list');
                  else
				 {
                   if (car[0].hasChildNodes())
                     alert(car[0].firstChild.nodeValue);
                   else
                     alert('There was an unspecified error');
                 }
				   
				}				
              }
              catch (e)
              {
                alert(e.message); // do nothing
              }
            }
         }
	}
	ajaxRequest.open("GET", querypage, true);
	ajaxRequest.send(null);
}

function aj_doSearch(beds,baths,age,garage,price_from,price_to,sdivision,ltype,ptype,sizefrom,sizeto,acresfrom,acresto,leaseprice_from,leaseprice_to,listingno)
{
    querypage='/getsearch.php?beds='+beds+'&baths='+baths+'&age='+age+'&garage='+garage+'&price_from='+price_from+'&price_to='+price_to+'&sdivision='+sdivision+'&ptype='+ptype+'&ltype='+ltype+'&sizefrom='+sizefrom;
	querypage+='&sizeto='+sizeto+'&acresfrom='+acresfrom+'&acresto='+acresto+'&leaseprice_from='+leaseprice_from+'&leaseprice_to='+leaseprice_to+'&listingno='+listingno;
    var ajaxRequest;  // The variable that makes Ajax possible!
    if(window.ActiveXObject)
       ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
    else
      if(window.XMLHttpRequest)
        ajaxRequest = new XMLHttpRequest();
      else
        return false;
	// Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
          try
            {
               var xmlDoc=document.implementation.createDocument("","",null);
               parser=new DOMParser();
			   xmlDoc=parser.parseFromString(ajaxRequest.responseText,"text/xml");			   
			   ir=document.getElementById('search_list');
			   ir.innerHTML = "";			   
               listing = xmlDoc.getElementsByTagName('listing');
			   document.getElementById('no_searchresults').innerHTML=listing.length+' Listings';				   
			   for (i=0;i<listing.length;i++)
			   {
				  var tbl=document.createElement("table");
				  tbl.className='listing_table';
				  tbl.onclick = (function() { var b=listing[i].getAttribute('id'); return function() { aj_GetDetail(b,'goback_search'); }})();					   
				  tbl.cellPadding='0';
				  tbl.cellSpacing='0';
				  var tblB = document.createElement("tbody");
				  var row = document.createElement("tr");
				  var cell = document.createElement("td");
				  cell.width='141';
				  cell.rowSpan=3; //image goes inhere
				  timg=document.createElement("img");
				  if (listing[i].getAttribute('img')=='')
				    timg.src='images/thumb.jpg';
				  else
				    timg.src='/images/'+listing[i].getAttribute('img');
				  cell.appendChild(timg);					   
				  var cell2 = document.createElement("td");
				  cell2.rowSpan=3;
				  cell2.style.width='245px';
				  cell2.style.minWidth='245px';
				  cell2.style.maxWidth='245px';
				  cell2.style.verticalAlign='top';
				  cell2.className='large_price';
				  var p1=document.createElement('p');
				  p1.innerHTML=listing[i].getAttribute('price');
				  cell2.appendChild(p1);
				  var p2=document.createElement('p');				  
				  p2.className='lt_size';
				  p2.innerHTML='Age - '+listing[i].getAttribute('age');
				  cell2.appendChild(p2);
				  var cell3 = document.createElement("td");
				  cell3.style.verticalAlign='middle';
				  cell3.style.height='35px';
				  cell3.appendChild(document.createTextNode(listing[i].getAttribute('no_beds')+' Beds'));
				  var row2 = document.createElement("tr");
				  var cell4= document.createElement("td");
				  cell4.style.verticalAlign='middle';
				  cell4.style.height='35px';
				  cell4.appendChild(document.createTextNode(listing[i].getAttribute('no_baths')+' Baths'));					   
				  var row3 = document.createElement("tr");
				  var cell6=document.createElement("td");
				  cell6.style.verticalAlign='middle';
				  cell6.style.height='35px';
				  cell6.appendChild(document.createTextNode(listing[i].getAttribute('sq')+' sq.ft.'));					   					  
				  row3.appendChild(cell6);					   
				  row2.appendChild(cell4);
				  row.appendChild(cell);
				  row.appendChild(cell2);	
				  row.appendChild(cell3);					   				   					   
                  tblB.appendChild(row);
				  tblB.appendChild(row2);
				  tblB.appendChild(row3);					   
				  tbl.appendChild(tblB);					   
				  ir.appendChild(tbl);				   
				}
				document.getElementById('searchresults').style.display='block';
				document.getElementById('searchresults2').style.display='block';								
				document.getElementById('search').style.display='none';
				mapOverlay = document.getElementById('overlay');		
				mapOverlay.style.display = 'block';
				mapOverlay.style.width = '100%';
				mapOverlay.style.height = '100%';	
				mapOverlay.style.minHeight = '1060px';
				mapOverlay.onclick = function() { hideMyList_search(); };														   												
            }
            catch (e)
            {
              try
              {
				hide_info_list();
                var xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
                xmlDoc.async=false;
                if (ajaxRequest.responseText.length >0)
                {
                  xmlDoc.loadXML(ajaxRequest.responseText);				
			      ir=document.getElementById('search_list');
			      ir.innerHTML = "";			   
                  listing = xmlDoc.getElementsByTagName('listing');
			   document.getElementById('no_searchresults').innerHTML=listing.length+' Listings';				  				   
			   for (i=0;i<listing.length;i++)
			   {
				  var tbl=document.createElement("table");
				  tbl.className='listing_table';
				  tbl.onclick = (function() { var b=listing[i].getAttribute('id'); return function() { aj_GetDetail(b,'goback_search'); }})();					   
				  tbl.cellPadding='0';
				  tbl.cellSpacing='0';
				  var tblB = document.createElement("tbody");
				  var row = document.createElement("tr");
				  var cell = document.createElement("td");
				  cell.width='141';
				  cell.rowSpan=3; //image goes inhere
				  timg=document.createElement("img");
				  if (listing[i].getAttribute('img')=='')
				    timg.src='images/thumb.jpg';
				  else
				    timg.src='/images/'+listing[i].getAttribute('img');
				  cell.appendChild(timg);					   
				  var cell2 = document.createElement("td");
				  cell2.rowSpan=3;
				  cell2.style.width='245px';
					   cell2.style.minWidth='245px';
					   cell2.style.maxWidth='245px';
				  cell2.style.verticalAlign='top';
				  cell2.className='large_price';
				  var p1=document.createElement('p');
				  p1.innerHTML=listing[i].getAttribute('price');
				  cell2.appendChild(p1);
				  var p2=document.createElement('p');				  
				  p2.className='lt_size';
				  p2.innerHTML='Age - '+listing[i].getAttribute('age');
				  cell2.appendChild(p2);
				  var cell3 = document.createElement("td");
				  cell3.style.verticalAlign='middle';
				  cell3.appendChild(document.createTextNode(listing[i].getAttribute('no_beds')+' Beds'));
				  var row2 = document.createElement("tr");
				  var cell4= document.createElement("td");
				  cell4.style.verticalAlign='middle';
				  cell4.appendChild(document.createTextNode(listing[i].getAttribute('no_baths')+' Baths'));					   
				  var row3 = document.createElement("tr");
				  var cell6=document.createElement("td");
				  cell6.style.verticalAlign='middle';
				  cell6.appendChild(document.createTextNode(listing[i].getAttribute('sq')+' sq.ft.'));					   					  
				  row3.appendChild(cell6);					   
				  row2.appendChild(cell4);
				  row.appendChild(cell);
				  row.appendChild(cell2);	
				  row.appendChild(cell3);					   				   					   
                  tblB.appendChild(row);
				  tblB.appendChild(row2);
				  tblB.appendChild(row3);					   
				  tbl.appendChild(tblB);					   
				  ir.appendChild(tbl);				   
				}
				document.getElementById('searchresults').style.display='block';
				document.getElementById('searchresults2').style.display='block';				
				document.getElementById('search').style.display='none';
				mapOverlay = document.getElementById('overlay');		
				mapOverlay.style.display = 'block';
				mapOverlay.style.width = '100%';
				mapOverlay.style.height = '100%';	
				mapOverlay.style.minHeight = '1060px';
				mapOverlay.onclick = function() { hideMyList_search(); };														   
				}				
              }
              catch (e)
              {
                alert(e.message); // do nothing
              }
            }
         }
	}
	ajaxRequest.open("GET", querypage, true);
	ajaxRequest.send(null);
}

function nl2br(text){
text=escape(text);
return unescape(text.replace(/(%5Cr%5Cn)|(%5Cn%5Cr)|%5Cr|%5Cn/g,'<br />'));
}
 
function getNewListings()
{
	var partition=new Array('100000','200000','300000','400000','500000','600000','700000','800000','900000','1000000','2000000','3000000','4000000','5000000','6000000','7000000','8000000','9000000','10000000');
	var partition_price=new Array('$100,000','$200,000','$300,000','$400,000','$500,000','$600,000','$700,000','$800,000','$900,000','$1,000,000','$2,000,000','$3,000,000','$4,000,000','$5,000,000','$6,000,000','$7,000,000','$8,000,000','$9,000,000','$10,000,000');
    querypage='/getnewlistings.php';
    var ajaxRequest;  // The variable that makes Ajax possible!
    if(window.ActiveXObject)
       ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
    else
      if(window.XMLHttpRequest)
        ajaxRequest = new XMLHttpRequest();
      else
        return false;
	// Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
          try
            {
               var xmlDoc=document.implementation.createDocument("","",null);
               parser=new DOMParser();
               if (ajaxRequest.responseText.length >0)
               {
                 xmlDoc=parser.parseFromString(ajaxRequest.responseText,"text/xml");
                 car = xmlDoc.getElementsByTagName('new');
				 if (car[0].getAttribute('rows'))
				 {
				   if (car[0].getAttribute('rows')=='')
				     no_homes='0';
				   else
				     no_homes=car[0].getAttribute('rows');
				   document.getElementById('no_homes2').innerHTML=no_homes+' New Listings';
				   document.getElementById('ddate').innerHTML=car[0].getAttribute('ddate');
 				   var commercial_started=-1;
				   var ir=document.getElementById('nlists');
				   ir.innerHTML = "";				   
                   var listing = xmlDoc.getElementsByTagName('listing');
				   var price_level=-1;
				   var lastseen=-1;
				   if (car[0].getAttribute('rows')>0)
				   {
				   for (i=0;i<listing.length;i++)
				   {
			           if (listing[i].getAttribute('listing_type')==1 && commercial_started==2)
					   {
						   d=document.createElement('div');
						   d.className='price_sort';
						   p1=document.createElement('p');
                           p1.innerHTML='';
						   p2=document.createElement('p');
						   p2.innerHTML='Commercial';
						   d.appendChild(p1);
						   d.appendChild(p2);
						   ir.insertBefore(d,ir.firstChild);
						   commercial_started=1;					   					   					   
					   }					   
					   else
  					     commercial_started=listing[i].getAttribute('listing_type');
				      if (listing[i].getAttribute('listing_type')==1)
					   {					    
				         var price=listing[i].getAttribute('price');
 					     price=price.replace('$','');
					     price=price.replace(/,/g,'');					   
					     price=price*1.0;
					     j=partition.length;
					     while (j>=0)
					     {
					       if (price>partition[j] && lastseen!=j)
						   {						   
						     lastseen=j;
						     j--;						   
						     break;
						   }
						   else
						   {
						     if (price>partition[j])
                               break;
						     else
						       j--;					   					   					   
					       }
					     } 
                         if ((lastseen!=price_level))
					     {
						   k=i-1;
						   if ((i>0 && listing[k].getAttribute('listing_type')==1))
						   {						   
						     d=document.createElement('div');
						     d.className='price_sort';
						     p1=document.createElement('p');
						     p1.innerHTML=partition_price[lastseen];
						     p2=document.createElement('p');
						     p2.innerHTML=partition_price[lastseen];
						     p3=document.createElement('p');
						     p3.style.marginRight='20px';
						     p3.innerHTML=partition_price[lastseen];
						     d.appendChild(p1);
						     d.appendChild(p2);
						     d.appendChild(p3);						   						   
						     ir.insertBefore(d,ir.firstChild);					   
					         price_level=lastseen;					   					   
					       }
						   else
						     price_level=lastseen;
						 }					   					   
				       }
					   var tbl=document.createElement("table");
                       if (listing[i].getAttribute('listing_type')!=2)				   
 					     tbl.className='listing_table';
					   else
					     tbl.className='listing_table comm';
					   tbl.onclick = (function() { var b=listing[i].getAttribute('id'); return function() { aj_GetDetail(b,'daily'); }})();
					   tbl.cellPadding='0';
					   tbl.cellSpacing='0';
				       var tblB = document.createElement("tbody");
					   var row = document.createElement("tr");
					   var cell = document.createElement("td");
					   cell.rowSpan=3; //image goes inhere
	 				   cell.width='141px';				  					   					   
					   var timg=document.createElement("img");
				       timg.src='/images/'+listing[i].getAttribute('img');
					   cell.appendChild(timg);					   
					   var cell2 = document.createElement("td");
					   cell2.rowSpan=3;
					   cell2.style.width='245px';
					   cell2.style.minWidth='245px';
					   cell2.style.maxWidth='245px';
					   cell2.style.verticalAlign='top';					   					   
					   cell2.className='large_price';
					   var p2=document.createElement('p');
					   p2.innerHTML=listing[i].getAttribute('price');
					   var p1=document.createElement('p');
					   p1.className='lt_size';
					   p1.innerHTML=''+listing[i].getAttribute('sname');
					   cell2.appendChild(p2);
					   cell2.appendChild(p1);
					   var cell3 = document.createElement("td");			  
 				       cell3.style.verticalAlign='middle';	
					   cell3.style.height='35px';				   					   
                       if (listing[i].getAttribute('listing_type')!=2)
  					     cell3.appendChild(document.createTextNode(listing[i].getAttribute('no_beds')+' Beds'));
					   else
					     cell3.appendChild(document.createTextNode(listing[i].getAttribute('acres')+' Acres'));						 
					   var row2 = document.createElement("tr");
					   var cell4= document.createElement("td");
					   cell4.style.verticalAlign='middle';		
					   cell4.style.height='35px';			   
                       if (listing[i].getAttribute('listing_type')!=2)					   					   
 					     cell4.appendChild(document.createTextNode(listing[i].getAttribute('no_baths')+' Baths'));					   
					   var row3 = document.createElement("tr");
					   var cell6=document.createElement("td");
					   cell6.style.verticalAlign='middle';		
					   cell6.style.height='35px';			   
                       if (listing[i].getAttribute('listing_type')!=2)					   
  					     cell6.appendChild(document.createTextNode(listing[i].getAttribute('sq')+' sq.ft.'));					   					  
					   row3.appendChild(cell6);					   
					   row2.appendChild(cell4);
					   row.appendChild(cell);
					   row.appendChild(cell2);	
					   row.appendChild(cell3);					   				   					   
                       tblB.appendChild(row);
					   tblB.appendChild(row2);
					   tblB.appendChild(row3);					   
					   tbl.appendChild(tblB);
					   ir.insertBefore(tbl,ir.firstChild);	
				   }
				   lastseen++;
			       d=document.createElement('div');
				   d.className='price_sort';
				   p1=document.createElement('p');
				   p1.innerHTML=partition_price[lastseen];
				   p2=document.createElement('p');
				   p2.innerHTML=partition_price[lastseen];
				   p3=document.createElement('p');
				   p3.style.marginRight='20px';
				   p3.innerHTML=partition_price[lastseen];
				   d.appendChild(p1);
				   d.appendChild(p2);
				   d.appendChild(p3);						   						   
				   ir.insertBefore(d,ir.firstChild);				   
				 }
				 showDaily();
				 }
               }  
            }
            catch (e)
            {
              try
              {
                var xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
                xmlDoc.async=false;
                if (ajaxRequest.responseText.length >0)
                {
                  xmlDoc.loadXML(ajaxRequest.responseText);				
                 car = xmlDoc.getElementsByTagName('new');
				 if (car[0].getAttribute('rows'))
				 {
				   if (car[0].getAttribute('rows')=='')
				     no_homes='0';
				   else
				     no_homes=car[0].getAttribute('rows');
				   document.getElementById('no_homes2').innerHTML=no_homes+' New Listings';
				   document.getElementById('ddate').innerHTML=car[0].getAttribute('ddate');
 				   var commercial_started=-1;
				   var ir=document.getElementById('nlists');
				   ir.innerHTML = "";				   
                   var listing = xmlDoc.getElementsByTagName('listing');
				   var price_level=-1;
				   var lastseen=-1;
				   if (car[0].getAttribute('rows')>0)
				   {
				   for (i=0;i<listing.length;i++)
				   {
			           if (listing[i].getAttribute('listing_type')==1 && commercial_started==2)
					   {
						   d=document.createElement('div');
						   d.className='price_sort';
						   p1=document.createElement('p');
                           p1.innerHTML='';
						   p2=document.createElement('p');
						   p2.innerHTML='Commercial';
						   d.appendChild(p1);
						   d.appendChild(p2);
						   ir.insertBefore(d,ir.firstChild);
						   commercial_started=1;					   					   					   
					   }					   
					   else
  					     commercial_started=listing[i].getAttribute('listing_type');
				      if (listing[i].getAttribute('listing_type')==1)
					   {					    
				         var price=listing[i].getAttribute('price');
 					     price=price.replace('$','');
					     price=price.replace(/,/g,'');					   
					     price=price*1.0;
					     j=partition.length;
					     while (j>=0)
					     {
					       if (price>partition[j] && lastseen!=j)
						   {						   
						     lastseen=j;
						     j--;						   
						     break;
						   }
						   else
						   {
						     if (price>partition[j])
                               break;
						     else
						       j--;					   					   					   
					       }
					     } 
                         if ((lastseen!=price_level))
					     {
						   k=i-1;
						   if ((i>0 && listing[k].getAttribute('listing_type')==1))
						   {						   
						     d=document.createElement('div');
						     d.className='price_sort';
						     p1=document.createElement('p');
						     p1.innerHTML=partition_price[lastseen];
						     p2=document.createElement('p');
						     p2.innerHTML=partition_price[lastseen];
						     p3=document.createElement('p');
						     p3.style.marginRight='20px';
						     p3.innerHTML=partition_price[lastseen];
						     d.appendChild(p1);
						     d.appendChild(p2);
						     d.appendChild(p3);						   						   
						     ir.insertBefore(d,ir.firstChild);					   
					         price_level=lastseen;					   					   
					       }
						   else
						     price_level=lastseen;
						 }					   					   
				       }
					   var tbl=document.createElement("table");
                       if (listing[i].getAttribute('listing_type')!=2)				   
 					     tbl.className='listing_table';
					   else
					     tbl.className='listing_table comm';
					   tbl.onclick = (function() { var b=listing[i].getAttribute('id'); return function() { aj_GetDetail(b,'daily'); }})();
					   tbl.cellPadding='0';
					   tbl.cellSpacing='0';
				       var tblB = document.createElement("tbody");
					   var row = document.createElement("tr");
					   var cell = document.createElement("td");
					   cell.rowSpan=3; //image goes inhere
	 				   cell.width='141px';				  					   					   
					   var timg=document.createElement("img");
				       timg.src='/images/'+listing[i].getAttribute('img');
					   cell.appendChild(timg);					   
					   var cell2 = document.createElement("td");
					   cell2.rowSpan=3;
					   cell2.style.width='245px';
					   cell2.style.minWidth='245px';
					   cell2.style.maxWidth='245px';
					   cell2.style.verticalAlign='top';					   					   
					   cell2.className='large_price';
					   var p2=document.createElement('p');
					   p2.innerHTML=listing[i].getAttribute('price');
					   var p1=document.createElement('p');
					   p1.className='lt_size';
					   p1.innerHTML='Age - '+listing[i].getAttribute('age');
					   cell2.appendChild(p2);
					   cell2.appendChild(p1);
					   var cell3 = document.createElement("td");			  
 				       cell3.style.verticalAlign='middle';	
					   cell3.style.height='35px';				   					   
                       if (listing[i].getAttribute('listing_type')!=2)
  					     cell3.appendChild(document.createTextNode(listing[i].getAttribute('no_beds')+' Beds'));
					   else
					     cell3.appendChild(document.createTextNode(listing[i].getAttribute('acres')+' Acres'));						 
					   var row2 = document.createElement("tr");
					   var cell4= document.createElement("td");
					   cell4.style.verticalAlign='middle';		
					   cell4.style.height='35px';			   
                       if (listing[i].getAttribute('listing_type')!=2)					   					   
 					     cell4.appendChild(document.createTextNode(listing[i].getAttribute('no_baths')+' Baths'));					   
					   var row3 = document.createElement("tr");
					   var cell6=document.createElement("td");
					   cell6.style.verticalAlign='middle';		
					   cell6.style.height='35px';			   
                       if (listing[i].getAttribute('listing_type')!=2)					   
  					     cell6.appendChild(document.createTextNode(listing[i].getAttribute('sq')+' sq.ft.'));					   					  
					   row3.appendChild(cell6);					   
					   row2.appendChild(cell4);
					   row.appendChild(cell);
					   row.appendChild(cell2);	
					   row.appendChild(cell3);					   				   					   
                       tblB.appendChild(row);
					   tblB.appendChild(row2);
					   tblB.appendChild(row3);					   
					   tbl.appendChild(tblB);
					   ir.insertBefore(tbl,ir.firstChild);	
				   }
				   lastseen++;
			       d=document.createElement('div');
				   d.className='price_sort';
				   p1=document.createElement('p');
				   p1.innerHTML=partition_price[lastseen];
				   p2=document.createElement('p');
				   p2.innerHTML=partition_price[lastseen];
				   p3=document.createElement('p');
				   p3.style.marginRight='20px';
				   p3.innerHTML=partition_price[lastseen];
				   d.appendChild(p1);
				   d.appendChild(p2);
				   d.appendChild(p3);						   						   
				   ir.insertBefore(d,ir.firstChild);				   
				   showDaily();
				 }
			 }
                }
              }
              catch (e)
              {
                alert('error='+e.message); // do nothing
              }
            }
         }
	}
	ajaxRequest.open("GET", querypage, true);
	ajaxRequest.send(null);
}
