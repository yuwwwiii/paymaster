// JavaScript Document
// JS Calendar
var calendar = null; // remember the calendar object so that we reuse
// it and avoid creating another

function convertDate(ddate,splitby,format){

	var splitdate=ddate;
	if(format==1){
		splitdate_= splitdate.split(splitby);
		converteddate= splitdate_[1] + "/" + splitdate_[2] + "/"  + splitdate_[0];
	}else if(format==2){
		splitdate_= splitdate.split(splitby);
		converteddate= splitdate_[2] + "/" + splitdate_[0] + "/"  + splitdate_[1];
	}else{
		converteddate = ddate;
	}

	return converteddate;
}

// This function gets called when an end-user clicks on some date
function selected(cal, date) {		
	
	cal.sel.value = convertDate(date,'-',0);//convertDate(date,'-',1); // just update the value of the input field
}

// And this gets called when the end-user clicks on the _selected_ date,
// or clicks the "Close" (X) button.  It just hides the calendar without
// destroying it.
function closeHandler(cal) {
	cal.hide();			// hide the calendar

	// don't check mousedown on document anymore (used to be able to hide the
	// calendar when someone clicks outside it, see the showCalendar function).
	Calendar.removeEvent(document, "mousedown", checkCalendar);
}

// This gets called when the user presses a mouse button anywhere in the
// document, if the calendar is shown.  If the click was outside the open
// calendar this function closes it.
function checkCalendar(ev) {
	var el = Calendar.is_ie ? Calendar.getElement(ev) : Calendar.getTargetElement(ev);
	for (; el != null; el = el.parentNode)
	// FIXME: allow end-user to click some link without closing the
	// calendar.  Good to see real-time stylesheet change :)
	if (el == calendar.element || el.tagName == "A") break;
	if (el == null) {
		// calls closeHandler which should hide the calendar.
		calendar.callCloseHandler(); Calendar.stopEvent(ev);
	}
}

// This function shows the calendar under the element having the given id.
// It takes care of catching "mousedown" signals on document and hiding the
// calendar if the click was outside.
function showCalendar(id, va, source, dest) {

	var el = document.getElementById(id);
	if (calendar != null) {
		// we already have one created, so just update it.
		calendar.hide();		// hide the existing calendar
		calendar.parseDate(convertDate(el.value,'/',0)); // set it to a new date
	} else {
		// first-time call, create the calendar
		var cal = new Calendar(true, null, selected, closeHandler, source ,dest);
		calendar = cal;		// remember the calendar in the global
		cal.setRange(1900, 2070);	// min/max year allowed
		calendar.create();		// create a popup calendar
		calendar.parseDate(convertDate(el.value,'/',0)); // set it to a new date
	}
	calendar.sel = el;		// inform it about the input field in use
	calendar.showAtElement(el);	// show the calendar next to the input field

	// catch mousedown on the document
	Calendar.addEvent(document, "mousedown", checkCalendar);
	return false;
}

function showCalendar2(id, va) {

	var el = document.getElementById(id);
	if (calendar != null) {
		// we already have one created, so just update it.
		calendar.hide();		// hide the existing calendar
		calendar.parseDate(convertDate(el.value,'/',0)); // set it to a new date
	} else {
		// first-time call, create the calendar
		var cal = new Calendar(true, null, selected, closeHandler);
		calendar = cal;		// remember the calendar in the global
		calendar.dateFormat = Calendar._TT["HRIS_DATE_FORMAT"];
		cal.setRange(1900, 2070);	// min/max year allowed
		calendar.create();		// create a popup calendar
		calendar.parseDate(convertDate(el.value,'/',0)); // set it to a new date
	}
	calendar.sel = el;		// inform it about the input field in use
	calendar.showAtElement(el);	// show the calendar next to the input field

	// catch mousedown on the document
	Calendar.addEvent(document, "mousedown", checkCalendar);
	return false;
}

//-------------------------------------------------------------------------------------------------------------


function ajaxSend(ajaxMethod, ajaxUrl, ajaxDiv, ajaxOutput)
{ 
	function ajaxObject(){ 
		if (document.all && !window.opera){ 
			obj = new ActiveXObject("Microsoft.XMLHTTP");
		}else{ 
			obj = new XMLHttpRequest();
		}
		return obj;
	}

	var ajaxHttp = ajaxObject(); 
	ajaxHttp.open(ajaxMethod, ajaxUrl); 
	ajaxHttp.onreadystatechange = function(){ 
		if(ajaxHttp.readyState == 4){ 
			var ajaxResponse = ajaxHttp.responseText; 
			if (ajaxOutput == "innerHTML"){ 
				document.getElementById(ajaxDiv).innerHTML = ajaxResponse;
			}else if (ajaxOutput == "value"){ 
				document.getElementById(ajaxDiv).value = ajaxResponse;
			}
		}
	}
	
	ajaxHttp.send(null);
}
// this function is the same as the ajaxSend above, only that it appends another innerHtml to the existing one.
function ajaxSend_Appends(ajaxMethod, ajaxUrl, ajaxDiv, ajaxOutput)
{ 
	function ajaxObject(){ 
		if (document.all && !window.opera){ 
			obj = new ActiveXObject("Microsoft.XMLHTTP");
		}else{ 
			obj = new XMLHttpRequest();
		}
		return obj;
	}

	var ajaxHttp = ajaxObject(); 
	ajaxHttp.open(ajaxMethod, ajaxUrl); 
	ajaxHttp.onreadystatechange = function(){ 
		if(ajaxHttp.readyState == 4){ 
			var ajaxResponse = ajaxHttp.responseText; 
			if (ajaxOutput == "innerHTML"){ 
				document.getElementById(ajaxDiv).innerHTML += ajaxResponse;
			}else if (ajaxOutput == "value"){ 
				document.getElementById(ajaxDiv).value += ajaxResponse;
			}
		}
	}
	
	ajaxHttp.send(null);
}
//----------------------------------------------------------------------------------------------------
var prevBg="";
var prevFg="";

document.onmouseover = new Function("doMMove(1)");
document.onmouseout = new Function("doMMove(0)");

function doMMove(X){
	try{
		if(document.all){
			objFire =window.event.srcElement.parentElement;
//		}else{
//			objFire = document.event.srcElement.parentElement;
//		}
//		alert(objFire);
			if(objFire.className=="divTblListTR"&&objFire.tagName=="TR"){
				if(X){
					prevBg = objFire.style.backgroundColor;
					prevFg = objFire.style.color;
					objFire.style.backgroundColor="#ffffaa";
					objFire.style.color="#000000";
				}else{
					objFire.style.backgroundColor=prevBg;
					objFire.style.color=prevFg;
				}
			}else{
				objFire = objFire.parentElement;
				if(objFire.className=="divTblListTR"&&objFire.tagName=="TR"){
					if(X){
						prevBg = objFire.style.backgroundColor;
						prevFg = objFire.style.color;
						objFire.style.backgroundColor="#ffffaa";
						objFire.style.color="#000000";
					}else{
						objFire.style.backgroundColor=prevBg;
						objFire.style.color=prevFg;
					}
				}
			}
		}
	}catch(e){;}
}


/**
 * Listbox function helper
 */

function move(fbox, tbox) {
	var arrFbox = new Array();
	var arrTbox = new Array();
	var arrLookup = new Array();
	var i;
	for (i = 0; i < tbox.options.length; i++) {
		arrLookup[tbox.options[i].text] = tbox.options[i].value;
		arrTbox[i] = tbox.options[i].text;
	}
	var fLength = 0;
	var tLength = arrTbox.length;
	for(i = 0; i < fbox.options.length; i++) {
		arrLookup[fbox.options[i].text] = fbox.options[i].value;
		if (fbox.options[i].selected && fbox.options[i].value != "") {
			arrTbox[tLength] = fbox.options[i].text;
			tLength++;
		}
		else {
			arrFbox[fLength] = fbox.options[i].text;
			fLength++;
		}
	}
	arrFbox.sort();
	//arrTbox.sort();
	fbox.length = 0;
	tbox.length = 0;
	var arr = new Array();
	var c;
	var tCell = tbox.parentNode;
	for(c = 0; c < arrFbox.length; c++) {
		var no = new Option();
		no.value = arrLookup[arrFbox[c]];
		no.text = arrFbox[c];
		fbox[c] = no;
	}
	for(c = 0; c < arrTbox.length; c++) {
		var no = new Option();
		no.value = arrLookup[arrTbox[c]];
		arr[c] = arrLookup[arrTbox[c]];
		no.text = arrTbox[c];
		//no.selected = true;
		tbox[c] = no;

   }
   		var hidElement = document.createElement('input');
		hidElement.setAttribute('type','hidden');
		hidElement.setAttribute('name','huser_type');
		hidElement.setAttribute('value',arr);
		
		tCell.appendChild(hidElement);
//   alert(tbox.parentNode);
}
// This function shows openwindows.
// It takes care of "window.open" url, windowname,attribs.
// popup attribs.
function openwindow(url,windowname,attribs){
	try{
 	if(attribs==''){
 		var default_attribs = 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=550,height=450,left=600,top=150';
 	}else{
 		var default_attribs = attribs;
 	}
	var xwin = window.open(url,windowname,default_attribs );
	}catch(e){
		alert(e.description);
	}
}

/**
 * @note used to select all text in text fields
 * @param $var_ = paramiter
 * @return
 */
function select_all(var_){
	var text_val=eval(var_);
	text_val.focus();
	text_val.select();
}