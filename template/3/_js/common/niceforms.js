/*#############################################################
Name: Niceforms
Version: 2.0
Author: Lucian Slatineanu
URL: http://www.emblematiq.com/projects/niceforms/

Feel free to use and modify but please keep this copyright intact.
#################################################################*/

//Theme Variables - edit these to match your theme
var imagesPath = "../../images/niceforms/standard/";
var selectRightWidthSimple = 3;
var selectRightWidthScroll = 0;
var selectMaxHeight = 300;
var textareaTopPadding = 10;
var textareaSidePadding = 10;

//Global Variables
var NF = new Array();
var isIE = false;
var resizeTest = 1;

//Initialization function
function NFInit() {
	try {
		document.execCommand('BackgroundImageCache', false, true);
	} catch(e) {}
	if(!document.getElementById) {return false;}
	//alert("click me first");
	NFDo('start');
}

function NFDo(what) {
	var niceforms = document.getElementsByTagName('form');
	var identifier = new RegExp('(^| )'+'niceform'+'( |$)');
	if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)) {
		var ieversion=new Number(RegExp.$1);
		if(ieversion < 7) {return false;} //exit script if IE6
		isIE = true;
	}
	for(var q = 0; q < niceforms.length; q++) {if(identifier.test(niceforms[q].className)) {
		if(what == "start") { //Load Niceforms
			NF[q] = new niceform(niceforms[q]);
			niceforms[q].start();
		}
		else { //Unload Niceforms
                    //alert(niceforms[q].unload());
                    //if((niceforms[q] != 'undefined') && (niceforms[q] != ''))
                    //{
                        niceforms[q].unload();
			NF[q] = "";
                    //}
		}
	}}
}
function NFFix() {
	NFDo('stop');
	NFDo('start');
}
function niceform(nf) {
	nf._inputText = new Array();nf._inputRadio = new Array();nf._inputCheck = new Array();nf._inputSubmit = new Array();nf._inputFile = new Array();nf._textarea = new Array();nf._select = new Array();nf._multiselect = new Array();
	nf.add_inputText = function(obj) {this._inputText[this._inputText.length] = obj;inputText(obj);}
	nf.add_inputRadio = function(obj) {this._inputRadio[this._inputRadio.length] = obj;inputRadio(obj);}
	nf.add_inputCheck = function(obj) {this._inputCheck[this._inputCheck.length] = obj;inputCheck(obj);}
	nf.add_inputSubmit = function(obj) {this._inputSubmit[this._inputSubmit.length] = obj;inputSubmit(obj);}
	nf.add_inputFile = function(obj) {this._inputFile[this._inputFile.length] = obj;inputFile(obj);}
	nf.add_textarea = function(obj) {this._textarea[this._textarea.length] = obj;textarea(obj);}
	nf.add_select = function(obj) {this._select[this._select.length] = obj;selects(obj);}
	nf.add_multiselect = function(obj) {this._multiselect[this._multiselect.length] = obj;multiSelects(obj);}
	nf.start = function() {
		//Separate and assign elements
		var allInputs = this.getElementsByTagName('input');
		for(var w = 0; w < allInputs.length; w++) {
			switch(allInputs[w].type) {
				case "text": case "password": {this.add_inputText(allInputs[w]);break;}
				case "radio": {this.add_inputRadio(allInputs[w]);break;}
				case "checkbox": {this.add_inputCheck(allInputs[w]);break;}
				case "submit": case "reset": case "button": {this.add_inputSubmit(allInputs[w]);break;}
				case "file": {this.add_inputFile(allInputs[w]);break;}
			}
		}
		var allButtons = this.getElementsByTagName('button');
		for(var w = 0; w < allButtons.length; w++) {
			this.add_inputSubmit(allButtons[w]);
		}
		var allTextareas = this.getElementsByTagName('textarea');
		for(var w = 0; w < allTextareas.length; w++) {
			this.add_textarea(allTextareas[w]);
		}
		var allSelects = this.getElementsByTagName('select');
		for(var w = 0; w < allSelects.length; w++) {
			if(allSelects[w].size == "1") {this.add_select(allSelects[w]);}
			else {this.add_select(allSelects[w]);}//{this.add_multiselect(allSelects[w]);}
		}
		//Start
		for(w = 0; w < this._inputText.length; w++) {this._inputText[w].init();}
		for(w = 0; w < this._inputRadio.length; w++) {this._inputRadio[w].init();}
		for(w = 0; w < this._inputCheck.length; w++) {this._inputCheck[w].init();}
		for(w = 0; w < this._inputSubmit.length; w++) {this._inputSubmit[w].init();}
		for(w = 0; w < this._inputFile.length; w++) {this._inputFile[w].init();}
		for(w = 0; w < this._textarea.length; w++) {this._textarea[w].init();}
		for(w = 0; w < this._select.length; w++) {this._select[w].init(w);}
		for(w = 0; w < this._multiselect.length; w++) {this._multiselect[w].init(w);}
	}
	nf.unload = function() {
		//Stop
		for(w = 0; w < this._inputText.length; w++) {this._inputText[w].unload();}
		for(w = 0; w < this._inputRadio.length; w++) {this._inputRadio[w].unload();}
		for(w = 0; w < this._inputCheck.length; w++) {this._inputCheck[w].unload();}
		for(w = 0; w < this._inputSubmit.length; w++) {this._inputSubmit[w].unload();}
		for(w = 0; w < this._inputFile.length; w++) {this._inputFile[w].unload();}
		for(w = 0; w < this._textarea.length; w++) {this._textarea[w].unload();}
		for(w = 0; w < this._select.length; w++) {this._select[w].unload();}
		for(w = 0; w < this._multiselect.length; w++) {this._multiselect[w].unload();}
                
	}
}
function inputText(el) { //extent Text inputs
	el.oldClassName = el.className;
	el.left = document.createElement('img');
	el.left.src = imagesPath + "0.png";
	el.left.className = "NFTextLeft";
	el.right = document.createElement('img');
	el.right.src = imagesPath + "0.png";
	el.right.className = "NFTextRight";
	el.dummy = document.createElement('div');
	el.dummy.className = "NFTextCenter";
	el.onfocus = function() {
		this.dummy.className = "NFTextCenter NFh";
		this.left.className = "NFTextLeft NFh";
		this.right.className = "NFTextRight NFh";
                //alert('focus');
                if(el.id=='login-input')
                {
                    if(el.value==' - podaj swój e-mail - ') el.value='';
                }
                
                if(el.id=='passowrd-input')
                {//alert(el.value);
                    if(el.value=='**********') el.value='';
                }
                
                if(el.id=='rejmail')
                {
                    if(el.value==' - podaj swój e-mail - ') el.value='';
                }
                
                if(el.id=='rejpass')
                {//alert(el.value);
                    if(el.value=='**********') el.value='';
                }
                
                if(el.id=='ko_email')
                {
                    if(el.value=='wpisz...') el.value='';
                }
                
                
                if(el.id=='ko_year')
                {
                    if(el.value=='RRRR') el.value='';
                }
                
                if(el.id=='ko_month')
                {
                    if(el.value=='MM') el.value='';
                }
                
                if(el.id=='ko_day')
                {
                    if(el.value=='DD') el.value='';
                }
                
                if(el.id=='ol_haslo') if(el.value=='wpisz...') el.value='';
                if(el.id=='ko_haslo') if(el.value=='wpisz...') el.value='';
                if(el.id=='re_haslo') if(el.value=='wpisz...') el.value='';
                if(el.id=='ko_nazwa') if(el.value=='wpisz...') el.value='';
                if(el.id=='ko_firma') if(el.value=='wpisz...') el.value='';
                if(el.id=='ko_nip') if(el.value=='wpisz...') el.value='';
                if(el.id=='ko_miasto') if(el.value=='wpisz...') el.value='';               
                if(el.id=='ko_kod') if(el.value=='wpisz...') el.value='';
                if(el.id=='ko_ulica') if(el.value=='wpisz...') el.value='';
                if(el.id=='ko_ulica_dom') if(el.value=='wpisz...') el.value='';
                if(el.id=='ko_ulica_lok') if(el.value=='wpisz...') el.value='';
                if(el.id=='ko_telefon') if(el.value=='wpisz...') el.value='';
                
                
	}
	el.onblur = function() {
		this.dummy.className = "NFTextCenter";
		this.left.className = "NFTextLeft";
		this.right.className = "NFTextRight";
                //alert('blur'+el.id);
                if(el.id=='login-input')
                {
                    if(el.value=='') el.value=' - podaj swój e-mail - ';
                }
                
                if(el.id=='passowrd-input')
                {//alert(el.value);
                    if(el.value=='') el.value='**********';
                }
                
                if(el.id=='rejmail')
                {
                    if(el.value=='') el.value=' - podaj swój e-mail - ';
                }
                
                if(el.id=='rejpass')
                {//alert(el.value);
                    if(el.value=='') el.value='**********';
                }
                
                if(el.id=='ko_email')
                {//alert(el.value);
                    if(el.value=='') el.value='wpisz...';
                }
                
                if(el.id=='ko_year')
                {
                    if(el.value=='') el.value='RRRR';
                }
                
                if(el.id=='ko_month')
                {
                    if(el.value=='') el.value='MM';
                }
                
                if(el.id=='ko_day')
                {
                    if(el.value=='') el.value='DD';
                }
                
                if(el.id=='ol_haslo') if(el.value=='') el.value='wpisz...';
                if(el.id=='ko_haslo') if(el.value=='') el.value='wpisz...';
                if(el.id=='re_haslo') if(el.value=='') el.value='wpisz...';
                if(el.id=='ko_nazwa') if(el.value=='') el.value='wpisz...';
                if(el.id=='ko_firma') if(el.value=='') el.value='wpisz...';
                if(el.id=='ko_nip') if(el.value=='') el.value='wpisz...';
                if(el.id=='ko_miasto') if(el.value=='') el.value='wpisz...';               
                if(el.id=='ko_kod') if(el.value=='') el.value='wpisz...';
                if(el.id=='ko_ulica') if(el.value=='') el.value='wpisz...';
                if(el.id=='ko_ulica_dom') if(el.value=='') el.value='wpisz...';
                if(el.id=='ko_ulica_lok') if(el.value=='') el.value='wpisz...';
                if(el.id=='ko_telefon') if(el.value=='') el.value='wpisz...';
                
	}
	el.init = function() {
		this.parentNode.insertBefore(this.left, this);
		this.parentNode.insertBefore(this.right, this.nextSibling);
		this.dummy.appendChild(this);
		this.right.parentNode.insertBefore(this.dummy, this.right);
		this.className = "NFText";
                
                if(el.id=='ko_email')
                {
                    if(el.value=='') el.value='wpisz...';
                }
                
                if(el.id=='ol_haslo') if(el.value=='') el.value='wpisz...';
                if(el.id=='ko_haslo') if(el.value=='') el.value='wpisz...';
                if(el.id=='re_haslo') if(el.value=='') el.value='wpisz...';
                if(el.id=='ko_nazwa') if(el.value=='') el.value='wpisz...';
                if(el.id=='ko_firma') if(el.value=='') el.value='wpisz...';
                if(el.id=='ko_nip') if(el.value=='') el.value='wpisz...';
                if(el.id=='ko_miasto') if(el.value=='') el.value='wpisz...';               
                if(el.id=='ko_kod') if(el.value=='') el.value='wpisz...';
                if(el.id=='ko_ulica') if(el.value=='') el.value='wpisz...';
                if(el.id=='ko_ulica_dom') if(el.value=='') el.value='wpisz...';
                if(el.id=='ko_ulica_lok') if(el.value=='') el.value='wpisz...';
                if(el.id=='ko_telefon') if(el.value=='') el.value='wpisz...';
                
	}
//	el.unload = function() {
//		this.parentNode.parentNode.appendChild(this);
//		this.parentNode.removeChild(this.left);
//		this.parentNode.removeChild(this.right);
//		this.parentNode.removeChild(this.dummy);
//		this.className = this.oldClassName;
//	}
}
function inputRadio(el) { //extent Radio buttons
	el.oldClassName = el.className;
	el.dummy = document.createElement('div');
	if(el.checked) {el.dummy.className = "NFRadio NFh";}
	else {el.dummy.className = "NFRadio";}
	el.dummy.ref = el;
	if(isIE == false) {el.dummy.style.left = findPosX(el) + 'px';el.dummy.style.top = findPosY(el) + 'px';}
	else {el.dummy.style.left = findPosX(el) + 4 + 'px';el.dummy.style.top = findPosY(el) + 4 + 'px';}
	el.dummy.onclick = function() {
		if(!this.ref.checked) {
			var siblings = getInputsByName(this.ref.name);
			for(var q = 0; q < siblings.length; q++) {
				siblings[q].checked = false;
				siblings[q].dummy.className = "NFRadio";
			}
			this.ref.checked = true;
			this.className = "NFRadio NFh";
                        
                        if(this.ref.name == 'brands'){
                            var str = window.location.toString();
                            var idx = str.indexOf("?");
                            //alert(this.ref.value);
                            if(idx >0) str = str.substr(0,idx);
                            window.location=str+'?brands='+this.ref.value;
                        }
                        if(this.ref.name == 'kolor'){
                            var str = window.location.toString();
                            var idx = str.indexOf("?");
                            //alert(this.ref.value);
                            if(idx >0) str = str.substr(0,idx);
                            window.location=str+'?kolor='+this.ref.value;
                        }
                        if(this.ref.name == 'size'){
                            var str = window.location.toString();
                            var idx = str.indexOf("?");
                            //alert(this.ref.value);
                            if(idx >0) str = str.substr(0,idx);
                            window.location=str+'?size='+this.ref.value;
                        }
                        
                        if(this.ref.name == 'faktura'){
                            var str = window.location.toString();
                            var idx = str.indexOf("?");
                            //alert(this.ref.value);
                            if(idx >0) str = str.substr(0,idx);
                            window.location=str+'?faktura='+this.ref.value;
                        }
                        
                        if(this.ref.name == 'fason'){
                            var str = window.location.toString();
                            var idx = str.indexOf("?");
                            //alert(this.ref.value);
                            if(idx >0) str = str.substr(0,idx);
                            window.location=str+'?fason='+this.ref.value;
                        }
                        
                        if(el.id == 'do2'){
                            //alert('qwe');
                            document.getElementById('dadres').style.display='block';
                            document.getElementById('do_uwagi').style.width='350px';
                            document.getElementById('do_uwagi').style.height='100px';
                            NFFix();
                        }
                        
                        if(el.id == 'do1'){
                            //alert('qwe');
                            document.getElementById('dadres').style.display='none';
                        }
		}
	}
	el.onclick = function() {
		if(this.checked) {
			var siblings = getInputsByName(this.name);
			for(var q = 0; q < siblings.length; q++) {
				siblings[q].dummy.className = "NFRadio";
			}
			this.dummy.className = "NFRadio NFh";
		}
	}
	el.onfocus = function() {this.dummy.className += " NFfocused";}
	el.onblur = function() {this.dummy.className = this.dummy.className.replace(/ NFfocused/g, "");}
	el.init = function() {
		this.parentNode.insertBefore(this.dummy, this);
		el.className = "NFhidden";
	}
	el.unload = function() {
		this.parentNode.removeChild(this.dummy);
		this.className = this.oldClassName;
	}
}
function inputCheck(el) { //extend Checkboxes
	el.oldClassName = el.className;
	el.dummy = document.createElement('img');
	el.dummy.src = imagesPath + "0.png";
	if(el.checked) {el.dummy.className = "NFCheck NFh";}
	else {el.dummy.className = "NFCheck";}
	el.dummy.ref = el;
	if(isIE == false) {el.dummy.style.left = findPosX(el) + 'px';el.dummy.style.top = findPosY(el) + 'px';}
	else {el.dummy.style.left = findPosX(el) + 4 + 'px';el.dummy.style.top = findPosY(el) + 4 + 'px';}
	el.dummy.onclick = function() {
		if(!this.ref.checked) {
			this.ref.checked = true;
			this.className = "NFCheck NFh";
                        if(el.id=='brands')
                        {
                            var str = window.location.toString();
                            var idx = str.indexOf("?");

                            if(idx >0) str = str.substr(0,idx);
                            window.location=str+'?brands='+el.value;
                            //alert(el.id+'--'+el.value+'--'+str);
                        }
                        
                        if(el.id=='kolor')
                        {
                            var str = window.location.toString();
                            var idx = str.indexOf("?");

                            if(idx >0) str = str.substr(0,idx);
                            window.location=str+'?kolor='+el.value;
                            //alert(el.id+'--'+el.value+'--'+str);
                        }
                        
                        if(el.id=='size')
                        {
                            var str = window.location.toString();
                            var idx = str.indexOf("?");

                            if(idx >0) str = str.substr(0,idx);
                            window.location=str+'?size='+el.value;
                            //alert(el.id+'--'+el.value+'--'+str);
                        }
                        
                        if(el.id=='faktura')
                        {
                            var str = window.location.toString();
                            var idx = str.indexOf("?");

                            if(idx >0) str = str.substr(0,idx);
                            window.location=str+'?faktura='+el.value;
                            //alert(el.id+'--'+el.value+'--'+str);
                        }
                        
                        if(el.id=='fason')
                        {
                            var str = window.location.toString();
                            var idx = str.indexOf("?");

                            if(idx >0) str = str.substr(0,idx);
                            window.location=str+'?fason='+el.value;
                            //alert(el.id+'--'+el.value+'--'+str);
                        }
                        
                        if(el.id=='subkat')
                        {
                            var str = window.location.toString();
                            var idx = str.indexOf("?");

                            if(idx >0) str = str.substr(0,idx);
                            window.location=str+'?subkat='+el.value;
                        }
		}
		else {
			this.ref.checked = false;
			this.className = "NFCheck";
                        
                        if(el.id=='brands')
                        {
                            var str = window.location.toString();
                            var idx = str.indexOf("?");

                            if(idx >0) str = str.substr(0,idx);
                            window.location=str+'?brands='+el.value;
                            //alert(el.id+'--'+el.value+'--'+str);
                        }
                        
                        if(el.id=='kolor')
                        {
                            var str = window.location.toString();
                            var idx = str.indexOf("?");

                            if(idx >0) str = str.substr(0,idx);
                            window.location=str+'?kolor='+el.value;
                            //alert(el.id+'--'+el.value+'--'+str);
                        }
                        
                        if(el.id=='size')
                        {
                            var str = window.location.toString();
                            var idx = str.indexOf("?");

                            if(idx >0) str = str.substr(0,idx);
                            window.location=str+'?size='+el.value;
                            //alert(el.id+'--'+el.value+'--'+str);
                        }
                        
                        if(el.id=='faktura')
                        {
                            var str = window.location.toString();
                            var idx = str.indexOf("?");

                            if(idx >0) str = str.substr(0,idx);
                            window.location=str+'?faktura='+el.value;
                            //alert(el.id+'--'+el.value+'--'+str);
                        }
                        
                        if(el.id=='fason')
                        {
                            var str = window.location.toString();
                            var idx = str.indexOf("?");

                            if(idx >0) str = str.substr(0,idx);
                            window.location=str+'?fason='+el.value;
                            //alert(el.id+'--'+el.value+'--'+str);
                        }
                        
                        if(el.id=='subkat')
                        {
                            var str = window.location.toString();
                            var idx = str.indexOf("?");

                            if(idx >0) str = str.substr(0,idx);
                            window.location=str+'?subkat='+el.value;
                        }
                        
		}
	}
	el.onclick = function() {
		if(this.checked) {this.dummy.className = "NFCheck NFh";}
		else {this.dummy.className = "NFCheck";}
	}
	el.onfocus = function() {this.dummy.className += " NFfocused";}
	el.onblur = function() {this.dummy.className = this.dummy.className.replace(/ NFfocused/g, "");}
	el.init = function() {
		this.parentNode.insertBefore(this.dummy, this);
		el.className = "NFhidden";
	} 
	el.unload = function() {
		this.parentNode.removeChild(this.dummy);
		this.className = this.oldClassName;
	}
}
function inputSubmit(el) { //extend Buttons
	el.oldClassName = el.className;
	el.left = document.createElement('img');
	el.left.className = "NFButtonLeft";
	el.left.src = imagesPath + "0.png";
	el.right = document.createElement('img');
	el.right.src = imagesPath + "0.png";
	el.right.className = "NFButtonRight";
	el.onmouseover = function() {
		this.className = "NFButton NFh";
		this.left.className = "NFButtonLeft NFh";
		this.right.className = "NFButtonRight NFh";
	}
	el.onmouseout = function() {
		this.className = "NFButton";
		this.left.className = "NFButtonLeft";
		this.right.className = "NFButtonRight";
	}
	el.init = function() {
		this.parentNode.insertBefore(this.left, this);
		this.parentNode.insertBefore(this.right, this.nextSibling);
		this.className = "NFButton";
	}
	el.unload = function() {
		this.parentNode.removeChild(this.left);
		this.parentNode.removeChild(this.right);
		this.className = this.oldClassName;
	}
}
function inputFile(el) { //extend File inputs
	el.oldClassName = el.className;
	el.dummy = document.createElement('div');
	el.dummy.className = "NFFile";
	el.file = document.createElement('div');
	el.file.className = "NFFileNew";
	el.center = document.createElement('div');
	el.center.className = "NFTextCenter";
	el.clone = document.createElement('input');
	el.clone.type = "text";
	el.clone.className = "NFText";
	el.clone.ref = el;
	el.left = document.createElement('img');
	el.left.src = imagesPath + "0.png";
	el.left.className = "NFTextLeft";
	el.button = document.createElement('img');
	el.button.src = imagesPath + "0.png";
	el.button.className = "NFFileButton";
	el.button.ref = el;
	el.button.onclick = function() {this.ref.click();}
	el.init = function() {
		var top = this.parentNode;
		if(this.previousSibling) {var where = this.previousSibling;}
		else {var where = top.childNodes[0];}
		top.insertBefore(this.dummy, where);
		this.dummy.appendChild(this);
		this.center.appendChild(this.clone);
		this.file.appendChild(this.center);
		this.file.insertBefore(this.left, this.center);
		this.file.appendChild(this.button);
		this.dummy.appendChild(this.file);
		this.className = "NFhidden";
		this.relatedElement = this.clone;
	}
	el.unload = function() {
		this.parentNode.parentNode.appendChild(this);
		this.parentNode.removeChild(this.dummy);
		this.className = this.oldClassName;
	}
	el.onchange = el.onmouseout = function() {this.relatedElement.value = this.value;}
	el.onfocus = function() {
		this.left.className = "NFTextLeft NFh";
		this.center.className = "NFTextCenter NFh";
		this.button.className = "NFFileButton NFh";
	}
	el.onblur = function() {
		this.left.className = "NFTextLeft";
		this.center.className = "NFTextCenter";
		this.button.className = "NFFileButton";
	}
	el.onselect = function() {
		this.relatedElement.select();
		this.value = '';
	}
}
function textarea(el) { //extend Textareas
	el.oldClassName = el.className;
	el.height = el.offsetHeight - textareaTopPadding;
	el.width = el.offsetWidth - textareaSidePadding;
	el.topLeft = document.createElement('img');
	el.topLeft.src = imagesPath + "0.png";
	el.topLeft.className = "NFTextareaTopLeft";
	el.topRight = document.createElement('div');
	el.topRight.className = "NFTextareaTop";
	el.bottomLeft = document.createElement('img');
	el.bottomLeft.src = imagesPath + "0.png";
	el.bottomLeft.className = "NFTextareaBottomLeft";
	el.bottomRight = document.createElement('div');
	el.bottomRight.className = "NFTextareaBottom";
	el.left = document.createElement('div');
	el.left.className = "NFTextareaLeft";
	el.right = document.createElement('div');
	el.right.className = "NFTextareaRight";
	el.init = function() {
		var top = this.parentNode;
		if(this.previousSibling) {var where = this.previousSibling;}
		else {var where = top.childNodes[0];}
		top.insertBefore(el.topRight, where);
		top.insertBefore(el.right, where);
		top.insertBefore(el.bottomRight, where);
		this.topRight.appendChild(this.topLeft);
		this.right.appendChild(this.left);
		this.right.appendChild(this);
		this.bottomRight.appendChild(this.bottomLeft);
		el.style.width = el.topRight.style.width = el.bottomRight.style.width = el.width + 'px';
		el.style.height = el.left.style.height = el.right.style.height = el.height + 'px';
		this.className = "NFTextarea";
	}
	el.unload = function() {
		this.parentNode.parentNode.appendChild(this);
		this.parentNode.removeChild(this.topRight);
		this.parentNode.removeChild(this.bottomRight);
		this.parentNode.removeChild(this.right);
		this.className = this.oldClassName;
		this.style.width = this.style.height = "";
	}
	el.onfocus = function() {
		this.topLeft.className = "NFTextareaTopLeft NFh";
		this.topRight.className = "NFTextareaTop NFhr";
		this.left.className = "NFTextareaLeftH";
		this.right.className = "NFTextareaRightH";
		this.bottomLeft.className = "NFTextareaBottomLeft NFh";
		this.bottomRight.className = "NFTextareaBottom NFhr";
	}
	el.onblur = function() {
		this.topLeft.className = "NFTextareaTopLeft";
		this.topRight.className = "NFTextareaTop";
		this.left.className = "NFTextareaLeft";
		this.right.className = "NFTextareaRight";
		this.bottomLeft.className = "NFTextareaBottomLeft";
		this.bottomRight.className = "NFTextareaBottom";
	}
}
function selects(el) { //extend Selects
	el.oldClassName = el.className;
	el.dummy = document.createElement('div');
	el.dummy.className = "NFSelect";
	el.dummy.style.width = el.offsetWidth + 'px';
	el.dummy.ref = el;
	el.left = document.createElement('img');
	el.left.src = imagesPath + "0.png";
	el.left.className = "NFSelectLeft";
	el.right = document.createElement('div');
	el.right.className = "NFSelectRight";
	el.txt = document.createTextNode(el.options[0].text);
	el.bg = document.createElement('div');
	el.bg.className = "NFSelectTarget";
	el.bg.style.display = "none";
	el.opt = document.createElement('ul');
	el.opt.className = "NFSelectOptions";
	//el.dummy.style.left = findPosX(el) + 'px';
	//el.dummy.style.top = findPosY(el) + 'px';
	el.opts = new Array(el.options.length);
	el.init = function(pos) {
		this.dummy.appendChild(this.left);
		this.right.appendChild(this.txt);
		this.dummy.appendChild(this.right);
		this.bg.appendChild(this.opt);
		this.dummy.appendChild(this.bg);
		for(var q = 0; q < this.options.length; q++) {
			this.opts[q] = new option(this.options[q], q);
			this.opt.appendChild(this.options[q].li);
			this.options[q].lnk.onclick = function() {
				this._onclick();
				this.ref.dummy.getElementsByTagName('div')[0].innerHTML = this.ref.options[this.pos].text;
				this.ref.options[this.pos].selected = "selected";
				for(var w = 0; w < this.ref.options.length; w++) {this.ref.options[w].lnk.className = "";}
				this.ref.options[this.pos].lnk.className = "NFOptionActive";
			}
		}
		if(this.options.selectedIndex) {
			this.dummy.getElementsByTagName('div')[0].innerHTML = this.options[this.options.selectedIndex].text;
			this.options[this.options.selectedIndex].lnk.className = "NFOptionActive";
		}
		this.dummy.style.zIndex = 999 - pos;
		this.parentNode.insertBefore(this.dummy, this);
		this.className = "NFhidden";
	}
	el.unload = function() {
		this.parentNode.removeChild(this.dummy);
		this.className = this.oldClassName;
                
	}
	el.dummy.onclick = function() {
		var allDivs = document.getElementsByTagName('div');for(var q = 0; q < allDivs.length; q++) {if((allDivs[q].className == "NFSelectTarget") && (allDivs[q] != this.ref.bg)) {allDivs[q].style.display = "none";}}
		if(this.ref.bg.style.display == "none") {this.ref.bg.style.display = "block";}
		else {this.ref.bg.style.display = "none";}
		if(this.ref.opt.offsetHeight > selectMaxHeight) {
			this.ref.bg.style.width = this.ref.offsetWidth - selectRightWidthScroll + 33 + 'px';
			this.ref.opt.style.width = this.ref.offsetWidth - selectRightWidthScroll + 'px';
		}
		else {
			this.ref.bg.style.width = this.ref.offsetWidth - selectRightWidthSimple + 33 + 'px';
			this.ref.opt.style.width = this.ref.offsetWidth - selectRightWidthSimple + 'px';
		}
	}
	el.bg.onmouseout = function(e) {
		if (!e) var e = window.event;
		e.cancelBubble = true;
		if (e.stopPropagation) e.stopPropagation();
		var reltg = (e.relatedTarget) ? e.relatedTarget : e.toElement;
                
                
                if((reltg.className=='product-image-container') || (reltg.className=='NFSelectTarget')) 
                {
                    this.style.display = "none";
                    //alert(reltg.className);
                }
                
                
		if((reltg.nodeName == 'A') || (reltg.nodeName == 'LI') || (reltg.nodeName == 'UL')) return;
		if((reltg.nodeName == 'DIV') || (reltg.className == 'NFSelectTarget')) return;
                else{this.style.display = "none";}
                
		
                
                //alert(this.style);
	}
	el.dummy.onmouseout = function(e) {
		if (!e) var e = window.event;
		e.cancelBubble = true;
		if (e.stopPropagation) e.stopPropagation();
		var reltg = (e.relatedTarget) ? e.relatedTarget : e.toElement;
		if((reltg.nodeName == 'A') || (reltg.nodeName == 'LI') || (reltg.nodeName == 'UL')) return;
		if((reltg.nodeName == 'DIV') || (reltg.className == 'NFSelectTarget')) return;
		else{this.ref.bg.style.display = "none";}
                
	}
        //el.onclick = function(){alert('qweqwe')}
        
	el.onfocus = function() {this.dummy.className += " NFfocused";}
	el.onblur = function() {this.dummy.className = this.dummy.className.replace(/ NFfocused/g, "");}
	el.onkeydown = function(e) {
		if (!e) var e = window.event;
		var thecode = e.keyCode;
		var active = this.selectedIndex;
		switch(thecode){
			case 40: //down
				if(active < this.options.length - 1) {
					for(var w = 0; w < this.options.length; w++) {this.options[w].lnk.className = "";}
					var newOne = active + 1;
					this.options[newOne].selected = "selected";
					this.options[newOne].lnk.className = "NFOptionActive";
					this.dummy.getElementsByTagName('div')[0].innerHTML = this.options[newOne].text;
				}
				return false;
				break;
			case 38: //up
				if(active > 0) {
					for(var w = 0; w < this.options.length; w++) {this.options[w].lnk.className = "";}
					var newOne = active - 1;
					this.options[newOne].selected = "selected";
					this.options[newOne].lnk.className = "NFOptionActive";
					this.dummy.getElementsByTagName('div')[0].innerHTML = this.options[newOne].text;
				}
				return false;
				break;
			default:
				break;
		}
	}
}
function multiSelects(el) { //extend Multiple Selects
	el.oldClassName = el.className;
	el.height = el.offsetHeight;
	el.width = el.offsetWidth;
	el.topLeft = document.createElement('img');
	el.topLeft.src = imagesPath + "0.png";
	el.topLeft.className = "NFMultiSelectTopLeft";
	el.topRight = document.createElement('div');
	el.topRight.className = "NFMultiSelectTop";
	el.bottomLeft = document.createElement('img');
	el.bottomLeft.src = imagesPath + "0.png";
	el.bottomLeft.className = "NFMultiSelectBottomLeft";
	el.bottomRight = document.createElement('div');
	el.bottomRight.className = "NFMultiSelectBottom";
	el.left = document.createElement('div');
	el.left.className = "NFMultiSelectLeft";
	el.right = document.createElement('div');
	el.right.className = "NFMultiSelectRight";
	el.init = function() {
		var top = this.parentNode;
		if(this.previousSibling) {var where = this.previousSibling;}
		else {var where = top.childNodes[0];}
		top.insertBefore(el.topRight, where);
		top.insertBefore(el.right, where);
		top.insertBefore(el.bottomRight, where);
		this.topRight.appendChild(this.topLeft);
		this.right.appendChild(this.left);
		this.right.appendChild(this);
		this.bottomRight.appendChild(this.bottomLeft);
		el.style.width = el.topRight.style.width = el.bottomRight.style.width = el.width + 'px';
		el.style.height = el.left.style.height = el.right.style.height = el.height + 'px';
		el.className = "NFMultiSelect";
	}
	el.unload = function() {
		this.parentNode.parentNode.appendChild(this);
		this.parentNode.removeChild(this.topRight);
		this.parentNode.removeChild(this.bottomRight);
		this.parentNode.removeChild(this.right);
		this.className = this.oldClassName;
		this.style.width = this.style.height = "";
	}
	el.onfocus = function() {
		this.topLeft.className = "NFMultiSelectTopLeft NFh";
		this.topRight.className = "NFMultiSelectTop NFhr";
		this.left.className = "NFMultiSelectLeftH";
		this.right.className = "NFMultiSelectRightH";
		this.bottomLeft.className = "NFMultiSelectBottomLeft NFh";
		this.bottomRight.className = "NFMultiSelectBottom NFhr";
	}
	el.onblur = function() {
		this.topLeft.className = "NFMultiSelectTopLeft";
		this.topRight.className = "NFMultiSelectTop";
		this.left.className = "NFMultiSelectLeft";
		this.right.className = "NFMultiSelectRight";
		this.bottomLeft.className = "NFMultiSelectBottomLeft";
		this.bottomRight.className = "NFMultiSelectBottom";
	}
}
function option(el, no) { //extend Options
	el.li = document.createElement('li');
	el.lnk = document.createElement('a');
	el.lnk.href = "javascript:;";
	el.lnk.ref = el.parentNode;
	el.lnk.pos = no;
	el.lnk._onclick = el.onclick || function () {
            if(this.ref.oldClassName == "NFOnChange") {
//insert your code here
                if(el.lnk.ref.id == 'top-sort-type')
                {
                    var str = window.location.toString();
                    var idx = str.indexOf("?");

                    if(idx >0) str = str.substr(0,idx);
                    window.location=str+'?o='+el.lnk.innerHTML;
                } 
                //alert(el.lnk.ref.id);
                
                if(el.lnk.ref.id == 'top-page-items')
                {
                    var str = window.location.toString();
                    var idx = str.indexOf("?");

                    if(idx >0) str = str.substr(0,idx);
                    //window.location=str+'?ppp='+el.lnk.innerHTML;
                    window.location=str+'?ppp='+el.value;
                }
                
                if(el.lnk.ref.id == 'fkategoria')
                {
                    var str = window.location.toString();
                    var idx = str.indexOf("?");

                    if(idx >0) str = str.substr(0,idx);
                    //window.location=str+'?cat='+el.value;
                    window.location='sklep,'+el.value+'.html';
//                    alert(el.value);
                }
                
                
                
                if(el.lnk.ref.id == 'szspd')
                {
                    $.ajax({
                        type: "POST",
                        url: "processing.php",
                        data: 'search_kat=' + el.value+'&ckatid='+ckatid,
                        success: function(html){
                            $("#szska").html(html);
                            //alert(html);
                        },
                        complete: function(){
                            NFFix();
                        }
                    });
                    
                    
                    $.ajax({
                        type: "POST",
                        url: "processing.php",
                        data: 'getProducts=' + el.value+'&spd='+el.value+'&ska='+$('#szska').val()+'&sro='+$('#szsro').val()+'&sna='+$('#szsna').val()+'&ckatid='+ckatid,
                        success: function(html){
                            $(".ramka").html(html);
                            //alert(html);
                        },
                        complete: function(){
                            //NFFix();
                            tooltip_go();
                        }
                    });
                }
                //szsro
                if(el.lnk.ref.id == 'szska')
                {
                    $.ajax({
                        type: "POST",
                        url: "processing.php",
                        data: 'search_ro=' + el.value+'&search_rokat='+$('#szspd').val()+'&ckatid='+ckatid,
                        success: function(html){
                            $("#szsro").html(html);
                            //$(".ramka").html(html);
                            //alert(html);
                        },
                        complete: function(){
                            NFFix();
                        }
                    });
                    
                    
                    $.ajax({
                        type: "POST",
                        url: "processing.php",
                        data: 'getProducts=' + el.value+'&spd='+$('#szspd').val()+'&ska='+el.value+'&sro='+$('#szsro').val()+'&sna='+$('#szsna').val()+'&ckatid='+ckatid,
                        success: function(html){
                            $(".ramka").html(html);
                            //alert(html);
                        },
                        complete: function(){
                            //NFFix();
                            tooltip_go();
                        }
                    });
                }
                
                
                //szsro
                if(el.lnk.ref.id == 'szsro')
                {           
                    $.ajax({
                        type: "POST",
                        url: "processing.php",
                        data: 'getProducts=' + el.value+'&spd='+$('#szspd').val()+'&ska='+$('#szska').val()+'&sro='+el.value+'&sna='+$('#szsna').val()+'&ckatid='+ckatid,
                        success: function(html){
                            $(".ramka").html(html);
                            //alert(html);
                        },
                        complete: function(){
                            //NFFix();
                            tooltip_go();
                        }
                    });
                }
                
                if(el.lnk.ref.id == 'do_id')
                {
                    //alert('jest-'+el.value);
                    var obj = document.getElementById('koszyk');
                    var suma=0;   
                    for(i=0; i<obj.elements.length; i++)
                    {
                        if(obj.elements[i].id.substr(0,7)=='ko_ile_') 
                        {
                            id = obj.elements[i].id.substr(7);
                            if(isNaN(obj.elements[i].value) || obj.elements[i].value.indexOf(".")>=0 || obj.elements[i].value.indexOf("-")>=0) 
                            {
                                obj.elements[i].className="iloscerror";
                                alert('Nieprawidłowy format ilości produktów');
                                error = true;
                                obj.elements[i].focus();
                                return;
                            } else 
                            {
                                obj.elements[i].className="ilosc";
                                error = false;
                                wartosc = (1*obj.elements[i].value)*(1*document.getElementById('cena_'+id).value)*100;
                                wartosc = Math.round(wartosc);
                                suma=Math.round(suma+wartosc);
                                //wartosc = (wartosc/100);
                                document.getElementById('wartosc_'+id).innerHTML = conv2num2(wartosc.toString())+' zł';
                            }
                        }
                    }
                    var do_koszt = 0;
                    //if(document.getElementById("do_id").selectedIndex!=0) 
                    if(el.value != 0)
                    {
                        //dostawa = document.getElementById("do_id").options[document.getElementById("do_id").selectedIndex].value;
                        dostawa = el.value;
                        if(suma<GRATIS[dostawa]) 
                        {
                            koszt = Math.round(KOSZT[dostawa]*100);
                            suma = Math.round(suma + koszt);
                            
                            do_koszt = koszt;
                        }
                    }

                    document.getElementById('suma').innerHTML = '<strong>'+conv2num2(suma.toString())+' zł</strong>';
                    document.getElementById('wartosc_do').innerHTML = conv2num2(do_koszt.toString())+' zł';
                }
                
                if(el.lnk.ref.id == 'salewm')
                {
                    location="sale," + el.value + '.htm';
                }
               //alert(el.id);
                
                //alert(document.getElementById('top-sort-type').onchange);
                //alert(event);
                //document.getElementById('top-sort-type').onchange.call(this);
                //for(i=0;el.length;i++) alert(el[i]);

                //alert(el.lnk);
                //alert(el.lnk.innerHTML);
//                alert(el.lnk.ref.name+' '+el.lnk.ref.innerHTML+' '+el.lnk.ref.form+' '+el.lnk.ref.value+' '+el.lnk.ref.type+' '+el.lnk.ref.options+' '+el.lnk.ref.item+' '+el.lnk.ref.id);
//                for(var propertyName in el.lnk.ref) {//name , innerHTML, form, value, type, options, item, id
//                   //alert(propertyName);
//                }
   // propertyName is what you want
   // you can get the value like this: myObject[propertyName]
                
            }
            //alert('asdasd');
        };
	el.txt = document.createTextNode(el.text);
	el.lnk.appendChild(el.txt);
	el.li.appendChild(el.lnk);
}

//Get Position
function findPosY(obj) {
	var posTop = 0;
	do {posTop += obj.offsetTop;} while (obj = obj.offsetParent);
	return posTop;
}
function findPosX(obj) {
	var posLeft = 0;
	do {posLeft += obj.offsetLeft;} while (obj = obj.offsetParent);
	return posLeft;
}
//Get Siblings
function getInputsByName(name) {
	var inputs = document.getElementsByTagName("input");
	var w = 0;var results = new Array();
	for(var q = 0; q < inputs.length; q++) {if(inputs[q].name == name) {results[w] = inputs[q];++w;}}
	return results;
}

//Add events
var existingLoadEvent = window.onload || function () {};
var existingResizeEvent = window.onresize || function() {};
window.onload = function () {
    existingLoadEvent();
    NFInit();
}
window.onresize = function() {
	if(resizeTest != document.documentElement.clientHeight) {
		existingResizeEvent();
		NFFix();
	}
	resizeTest = document.documentElement.clientHeight;
}



function userminilogin()
{
    $('#login-form').submit();
} 