function getdivision(id,obj,area)
{

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
                 car = xmlDoc.getElementsByTagName('info');
				 if (car[0].getAttribute('nohomes'))
				 {
				   document.getElementById('nohomes').innerHTML=car[0].getAttribute('nohomes')+' Homes';
				   document.getElementById('nohomes2').innerHTML=car[0].getAttribute('nohomes')+' Homes';				   
				   document.getElementById('divisionname').innerHTML=car[0].getAttribute('name');
				   document.getElementById('pricerange').innerHTML=car[0].getAttribute('min_price')+' - '+car[0].getAttribute('max_price');
				   document.getElementById('taxrange').innerHTML=car[0].getAttribute('min_tax')+' - '+car[0].getAttribute('max_tax');				   
				   document.getElementById('agerange').innerHTML=car[0].getAttribute('min_age')+' - '+car[0].getAttribute('max_age');				 
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
				   ir=document.getElementById('lists');
				   ir.innerHTML = "";				   
                   listing = xmlDoc.getElementsByTagName('listing');				   
				   for (i=0;i<listing.length;i++)
				   {
				       var tbl=document.createElement("table");
					   tbl.className='listing_table';
					   tbl.onclick='aj_GetDetail('+listing[i].getAttribute('id')+');';
					   tbl.cellPadding='0';
					   tbl.cellSpacing='0';
				       var tblB = document.createElement("tbody");
					   var row = document.createElement("tr");
					   var cell = document.createElement("td");
					   cell.rowSpan=3; //image goes inhere
					   timg=document.createElement("img");
					   timg.style.border='1px solid #fff;';
					   if (listing[i].getAttribute('img')=='')
					      timg.src='images/thumb.jpg';
					   else
					      timg.src='/images/'+listing[i].getAttribute('img');
					   cell.appendChild(timg);					   
					   var cell2 = document.createElement("td");
					   cell2.rowSpan=2;
					   cell2.className='large_price';
					   cell2.appendChild(document.createTextNode(listing[i].getAttribute('price')));
					   var cell3 = document.createElement("td");
					   cell3.appendChild(document.createTextNode('Beds - '+listing[i].getAttribute('no_beds')));
					   var row2 = document.createElement("tr");
					   var cell4= document.createElement("td");
					   cell4.appendChild(document.createTextNode('Baths - '+listing[i].getAttribute('no_baths')));					   
					   var row3 = document.createElement("tr");
					   var cell5=document.createElement("td");
					   cell5.appendChild(document.createTextNode('Age - '+listing[i].getAttribute('age')));
					   var cell6=document.createElement("td");
					   cell6.appendChild(document.createTextNode('Size - '+listing[i].getAttribute('sq')+' sq.ft.'));					   					  
                       row3.appendChild(cell5);
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
				   showDiv2(obj,area);
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
				 if (car[0].getAttribute('nohomes'))
				 {
				   document.getElementById('nohomes').innerHTML=car[0].getAttribute('nohomes')+' Homes';
				   document.getElementById('nohomes2').innerHTML=car[0].getAttribute('nohomes')+' Homes';				   				   
				   document.getElementById('divisionname').innerHTML=car[0].getAttribute('name');
				   document.getElementById('pricerange').innerHTML=car[0].getAttribute('min_price')+' - '+car[0].getAttribute('max_price');
				   document.getElementById('taxrange').innerHTML=car[0].getAttribute('min_tax')+' - '+car[0].getAttribute('max_tax');				   
				   document.getElementById('agerange').innerHTML=car[0].getAttribute('min_age')+' - '+car[0].getAttribute('max_age');				 
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
				   ir=document.getElementById('lists');
				   ir.innerHTML = "";				   
                   listing = xmlDoc.getElementsByTagName('listing');				   
				   for (i=0;i<listing.length;i++)
				   {
				       var tbl=document.createElement("table");
					   tbl.className='listing_table';
					   tbl.onclick='aj_GetDetail('+listing[i].getAttribute('id')+');';					   
					   tbl.cellPadding='0';
					   tbl.cellSpacing='0';
				       var tblB = document.createElement("tbody");
					   var row = document.createElement("tr");
					   var cell = document.createElement("td");
					   cell.rowSpan=3; //image goes inhere
					   timg=document.createElement("img");
					   timg.style.border='1px solid #fff;';
					   if (listing[i].getAttribute('img')=='')
					      timg.src='images/thumb.jpg';
					   else
					      timg.src='/images/'+listing[i].getAttribute('img');
					   cell.appendChild(timg);					   
					   var cell2 = document.createElement("td");
					   cell2.rowSpan=2;
					   cell2.className='large_price';
					   cell2.appendChild(document.createTextNode(listing[i].getAttribute('price')));
					   var cell3 = document.createElement("td");
					   cell3.appendChild(document.createTextNode('Beds - '+listing[i].getAttribute('no_beds')));
					   var row2 = document.createElement("tr");
					   var cell4= document.createElement("td");
					   cell4.appendChild(document.createTextNode('Baths - '+listing[i].getAttribute('no_baths')));					   
					   var row3 = document.createElement("tr");
					   var cell5=document.createElement("td");
					   cell5.appendChild(document.createTextNode('Age - '+listing[i].getAttribute('age')));
					   var cell6=document.createElement("td");
					   cell6.appendChild(document.createTextNode('Size - '+listing[i].getAttribute('sq')+' sq.ft.'));					   					  
                       row3.appendChild(cell5);
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
				   showDiv2(obj,area);				   
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
