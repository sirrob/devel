function CreateControl(src,width,height,url)
{
  if(url!="") src = src+"?clickTAG="+url
  document.write('<object type="application/x-shockwave-flash" width="'+width+'" height="'+height+'" data="'+src+'"><param name="type" value="application/x-shockwave-flash" /><param name="movie" value="'+src+'" /><param name="quality" value="high" /><param name="codebase" value="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" /><param name="wmode" value="transparent" /><param name="always" value="allowscriptaccess" /><param name="pluginspage" value="http://www.macromedia.com/go/getflashplayer" />');
  document.writeln('</object>');
}
function CreateControlIndex(src,width,height,url)
{
  if(url!="") src = src+"?clickTAG="+url
  document.write('<object type="application/x-shockwave-flash" width="'+width+'" height="'+height+'" data="'+src+'"><param name="type" value="application/x-shockwave-flash" /><param name="movie" value="'+src+'" /><param name="quality" value="high" /><param name="codebase" value="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" /><param name="wmode" value="transparent" /><param name="always" value="allowscriptaccess" /><param name="pluginspage" value="http://www.macromedia.com/go/getflashplayer" />');
  document.writeln('</object>');
}

function my_kontakt(email,lang) {
  if(lang==undefined || lang=='') lang='pl';
  popup('pop_kontakt.php?i='+email+'&l='+lang,'kontakt',"width=640,height=470,scrollbars=yes,resizable=yes,status=yes");
}

function m_ulubione() {
  window.external.AddFavorite('http://gomez.pl', 'gomez.pl');
}
 
function m_polec(typ,tytul,url, langid) {
  window.open(Purl+'pop_friend.php?k='+typ+'&t='+tytul+'&i='+url+'&langid='+langid,'polec','width=655,height=490,left=20,top=10,scrollbars=yes,resizable=yes,status=yes');
} 
 
function m_zapytaj(typ,tytul,url) {
  window.open(Purl+'pop_product_ask.php?k='+typ+'&t='+tytul+'&i='+url,'polec','width=655,height=490,left=20,top=10,scrollbars=yes,resizable=yes,status=yes');
} 
 
function m_sklep_foto(id,nr) {
  window.open('pop_product_img.php?n='+nr+'&i='+id,'prodfoto','width=655,height=490,left=20,top=10,scrollbars=yes,resizable=yes,status=yes');
}

function m_obserwuj(pid,uid)
{
    if(uid==0) {
		if(Plang == 'pl') alert('Musisz się zalogować aby móc dodać produkt do obserwowanych');
		else alert('You have to log in to add products to the Observed tab');
	}
    else
    {
    $.ajax({
          type: "POST",
          url: Purl+"processing.php",
          data: 'obserwowane=y&pid='+pid+'&uid='+uid,
          success: function(html){
               //$("#szska").html(html);
               alert(html);
          },
          complete: function(){
               // NFFix();
          }
    });
    
    }
}

function conv2num(ret) {
  ret = ret*100;
  ret = (ret) + "";	
  if (ret.length<=1) ret = "0.0" + ret;
  else if (ret.length<=2) ret = "0." + ret;
  else ret = ret.substr(0,ret.length-2) + "." + ret.substr(ret.length-2,2);
  return ret;
}

function reset_form(obj) {
  var el=document.getElementById(obj);  
  for(i=0; i<el.elements.length; i++){
    if(el.elements[i].type == 'text') el.elements[i].value='';
    if(el.elements[i].type == 'select-one') el.elements[i].selectedIndex='0';
    if(el.elements[i].type == 'select-multiple') el.elements[i].selectedIndex='-1';
  }
} 

function externalLinks() { 
 if (!document.getElementsByTagName) return; 
 var anchors = document.getElementsByTagName("a"); 
 for (var i=0; i<anchors.length; i++) { 
   var anchor = anchors[i]; 
   if (anchor.getAttribute("href") && 
       anchor.getAttribute("rel") == "external") 
     anchor.target = "_blank"; 
 } 
} 

function ge(id){
  return document.getElementById(id); 
}

function my_pict(url,w,h) {
  popup('pop_image.php?i='+url,'foto',"width="+h+",height="+h+",scrollbars=yes,resizable=yes,status=yes");
} 

function m_pop(obj) {
  obj.target = "_blank"
}

function m_shop_druk(template,id) {
  popup('pop_druk.php?i='+id,'druk');
}
 
function m_druk(typ,id,langid) {
  switch(typ) {
    case "produkt": adres="pop_product"; break;
  }
  popup(Purl+adres+'.php?i='+id+'&langid='+langid,'druk');
}
 
function m_hide(id){
  var obj = document.getElementById(id);
  if(obj.style.display=='block') { 
    obj.style.display='none';
  } else {
    obj.style.display='block'; 
  }
} 

var active='';
function m_wybierz(id) {
  if(active==id) {
    m_hide(id);
    active='';
  } else if(active=='') {
    m_hide(id);    
    active=id;
  } else {
    m_hide(active);
    m_hide(id);
    active=id;
  }
}
function my_plik(pre,form,pole,tryb) {
  //Tryb = 'get' pobiera nazwe, inny tylko zarzadza
  window.open("admin/plikman.php?i="+form+","+pole+","+tryb,"pliki","location=yes,status=yes,scrollbars=yes,resizable=yes,width=730,height=600,top=10");
}

window.onload = externalLinks;

var PopupWindows = new Array();
PopupWindows["standard"] = "width=400,height=550,scrollbars=yes,resizable=yes,status=yes"; 
PopupWindows["pomoc"] = "width=800,height=550,left=20,top=10,scrollbars=yes,resizable=yes,status=yes"; 
PopupWindows["druk"] = "width=700,height=600,left=20,top=10,scrollbars=yes,resizable=yes,status=yes,menubar=1"; 
PopupWindows["panel"] = "width=760,height=750,left=20,top=10,scrollbars=no,resizable=no,status=yes"; 
PopupWindows["panel2"] = "width=990,height=750,left=20,top=10,scrollbars=yes,resizable=yes,status=yes"; 
PopupWindows["panel3"] = "width=1000,height=750,left=20,top=5,scrollbars=yes,resizable=yes,status=yes"; 
function popup(url, name, parameter) {
  if (name == "" || name == null) {
		name = "standard";
	}
	if (PopupWindows[name] != null) {
		parameter = PopupWindows[name];
	}
	else if (parameter == "" || parameter == null) {
		parameter = PopupWindows["standard"];
	}

	var width, height, left = null, top = null;
	temp = parameter.split(",");
	for (var i = 0; i < temp.length; i++) {
		values = temp[i].split("=");
		if (values[0] == "width") width = parseInt(values[1]);
		if (values[0] == "height") height = parseInt(values[1]);
		if (values[0] == "left") left = parseInt(values[1]);
		if (values[0] == "top") top = parseInt(values[1]);
	}
	if (left == null) {
		left = Math.round((screen.width - width) / 2);
	}
	if (top == null) {
		top = Math.round((screen.height - height) / 3);
	}
	if (left != null) {
		parameter += ",screenX="+left+",left="+left;
	}
	if (top != null) {
		parameter += ",screenY="+top+",top="+top;
	}
//alert(url+ " " +name+ " "+parameter);
	var popuphandler = 	window.open(url, name, parameter);
	if (popuphandler != null) {
		popuphandler.window.focus();
	}	else {
		if(Plang == 'pl') alert('Nie można otworzyć wymaganego okna!');
		else alert('Cannot open necessary window!');
	}
} 
