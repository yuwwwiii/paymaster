<?
header("content-type: application/x-javascript");
//<script>
?>

//FORM VALIDATION
//Usage: onsubmit="return validate(this,'loan');"
function validate(theForm,id) 
{
	var error = "";
	var forms = new Array();

	forms['empl'] = [
	 new Array('emp_idnum','Employee ID',new Array('isNotEmpty'))
	,new Array('pi_fname','First Name',new Array('isNotEmpty'))
	,new Array('pi_mname','Middle Name',new Array('isNotEmpty'))
	,new Array('pi_lname','Last Name',new Array('isNotEmpty'))
	,new Array('pi_bdate','Birth Date',new Array('isValidDate'))
	,new Array('emp_hiredate','Hire Date',new Array('isValidDate'))
	
	];
	
	forms['loan'] = [
	 new Array('loan_voucher_no','Voucher No',new Array('isNotEmpty'))
	,new Array('loan_datepromissory','Date of Promissory Note',new Array('isValidDate'))
	,new Array('loan_dategrant','Date Granted',new Array('isValidDate'))
	,new Array('loan_principal','Loan principal',new Array('isNotEmpty'))
	];
	
	forms['bank_info'] = [
	 new Array('bankiemp_acct_name','Account name',new Array('isNotEmpty'))
	,new Array('bankiemp_acct_no','Account #',new Array('isNotEmpty'))
	];
	
	forms['salary_info'] = [
	 new Array('salarytype_name','Salary type',new Array('isNotEmpty'))
	,new Array('salaryinfo_basicrate','Basic rate',new Array('isNotEmpty'))
	,new Array('salaryinfo_effectdate','Effective date',new Array('isValidDate'))
	];
	
	for(var x = 0; x < forms[id].length; x++){
	var docid = forms[id][x][0];
	document.getElementById(docid).style.borderColor = '#CCCCCC';
		for(var y=0; y < forms[id][x][2].length; y++){
			result = this[forms[id][x][2][y]](theForm[docid]);
			error += result==true?"":forms[id][x][1]+" "+result;
		}
	}
      
  if (error != "") {
    alert("" + error);
    return false;
  }
}

function isValidDate(fld,desc) {
	var addtl = "";
	var error = "";
	var validformat=/^(19|20)\d\d-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/ //valid date format, yyyy-mm-dd
	var date = fld.value.split('-');
	
	
    if (fld.value == "" || !validformat.test(fld.value)) {
	   fld.style.borderColor = '#FF0000';
       error = " is not in valid format." + addtl + "\n";
	   return error;
    }
	else
	{
	return true;
	}
    
}

function isNotEmpty(fld,desc) {
	var error = "";

    if (fld.value == " ") {
	   fld.style.borderColor = '#FF0000';
       error = " is required.\n";
	   return error;
    }
	else
	{
	return true;
	}
    
}

function isNumeric(fld,desc,addtl) {
    var error = "";
 
    if (!isNaN(fld.value)) {
        fld.style.borderColor = '#FF0000';
        error = "must be numeric.\n";
		return error;
	}
	else
	{
	return true;
	}
} 

function isZero(fld,desc,addtl) {
    var error = "";
 
    if ((fld.value*1)==0) {
        fld.style.borderColor = '#FF0000';
        error = " is required.\n";
		return error;
	}
	else
	{
	return true;
	}
}  

function getAge(source,dest)
	{
	currDay   = <?php print date('d');?>;
	currMonth = <?php print date('m');?>;
	currYear  = <?php print date('Y');?>;
	
	var Dt = document.getElementById(source).value;
	
	//birthDay,birthMonth,birthYear
	var Dtsplit = new Array();
	Dtsplit=Dt.split("-");
	
	birthYear = Dtsplit[0]
	birthMonth = Dtsplit[1]
	birthDay   = Dtsplit[2]
	
	age = parseInt(currYear) - parseInt(birthYear);
	if(parseInt(currMonth) < parseInt(birthMonth)) age = age - 1;
	else if(parseInt(currMonth) == parseInt(birthMonth)){
		if(parseInt(currDay) < parseInt(birthDay)) age = age - 1;
		}
		
	if(!isNaN(age))document.getElementById(dest).value=age;
	}


/*End Tabular Menu*/
function validateCalendar(fld) {
    var validformat=/^(19|20)\d\d-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/ //Basic check for format validity
	if (!validformat.test(fld))
	{
	alert('Invalid Date Format.');
	}
}

function autotab(original,destination){
	if (original.getAttribute&&original.value.length==original.getAttribute("maxlength"))
	document.getElementById(destination).focus();
} 

function replace(str){
	return str.replace(/'/, "\'");
}

function display(id,val){
	document.getElementById(id).style.display=val;
}

function whatPercent(a,b)
{
	if(b>0)
	{
		var c = a/b;
		d = parseFloat(c)*100;
		return roundf(d);
	}
	
	return '0.00';
}
			
function percent(n)
{
	return roundf(n/100);
}
		
function roundf(f)
{
	n  = Math.round(f*100)/100
	if(n.toFixed) return n.toFixed(2);
	else return n;
}
		
function is_num(n,id)
{
	if(!isNaN(n) && n != ''){return true; }
	else{
	//	if(id){ document.getElementById(id).value=0; }
	return false;
	}
}
		
function makereadonly(id,make)
{
document.getElementById(id).disabled=make;
}	
	
function cancel(location)
{
	window.location=location;
	//window.location.reload();
}

/* Asynchronous Javascript and HTML 
*/
function jah(target,param,job) { 

    // native XMLHttpRequest object
    // document.getElementById(target).innerHTML = "<img src='images/loading.gif'>Updating...";
    if (window.XMLHttpRequest) {
        req = new XMLHttpRequest();
        req.onreadystatechange = function() {jahDone(target,job);};
        req.open("POST", "external/index.php", true);
		req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        req.send(param);
    // IE/Windows ActiveX version
    } else if (window.ActiveXObject) {
        req = new ActiveXObject("Microsoft.XMLHTTP");
        if (req) {
            req.onreadystatechange = function() {jahDone(target,job);};
            req.open("POST", "external/index.php", true);
			req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            req.send(param);
        }
    }
}    

function jahDone(target,job) {
    // only if req is "loaded"
    if (req.readyState == 4) {
        // only if "OK"
        if (req.status == 200 & job==true) {
		    eval(req.responseText);
        } 
		
		else if(req.status == 200 & job==false){
			document.getElementById(target).innerHTML=req.responseText;
        }
		
		else {
            document.getElementById(target).innerHTML="Error:\n" +
                req.statusText;
        }
    }
}



function getNumofMonths(sel,target,readid) 
		{
			currMonth = '<?php print date('F');?>';
			currYear  = <?php print date('Y');?>;
		
			
	
			if(readid != '')makereadonly(readid,false);
			var selection = new Array();
			
			
			selection['loanytd'] = ['syear','smonth','eyear','emonth'];
			selection['loan'] = ['syear','smonth','eyear','emonth']; //Start Year, Start Month, End Year, End Month
			selection['ben'] = ['bensyear','bensmonth','beneyear','benemonth'];
			
			var convertMonths= [];
				convertMonths["January"] = 0;
				convertMonths["February"] = 1;
				convertMonths["March"]=2;
				convertMonths["April"]=3;
				convertMonths["May"]=4;
				convertMonths["June"]=5;
				convertMonths["July"]=6;
				convertMonths["August"]=7;
				convertMonths["September"]=8;
				convertMonths["October"]=9;
				convertMonths["November"]=10;
				convertMonths["December"]=11;
				
				if(sel=='eMonth'){
				return convertMonths[eMonth];
				}
							
				if(sel=='loanytd')
				{
				sYear = currYear;
				sMonth = currMonth;
				
				eYear  = document.getElementById(selection[sel][0]).value;
				eMonth = document.getElementById(selection[sel][1]).value;
				}
				
				else
				{
				sYear  = document.getElementById(selection[sel][0]).value;
				sMonth = document.getElementById(selection[sel][1]).value;
				
				eYear  = document.getElementById(selection[sel][2]).value;
				eMonth = document.getElementById(selection[sel][3]).value;
				}
								
				var startDate=new Date(sYear,convertMonths[sMonth]);//start date
				startmonth=startDate.getMonth();startyear = startDate.getFullYear();

				var endDate=new Date(eYear,convertMonths[eMonth]);//end date
				endmonth=endDate.getMonth();endyear = endDate.getFullYear()

				if(sel=='loanytd')
				{
				//Start year = current year 2009
				//End year = start year 2008
				
				ytd = endyear<startyear?(((endyear - startyear)*12)-startmonth)+endmonth:0;
				if(ytd==0){  if(startmonth>endmonth) ytd = endmonth-startmonth;	}
				else numMonths=0;
				
				numMonths = (endyear==startyear&&startmonth==endmonth)?1:ytd;
				numMonths = (ytd==0&&numMonths!=1)?0:-(ytd)+1;
				//alert(numMonths);
				}

				else
				{
				calculate = endyear>startyear?(((endyear - startyear)*12)-startmonth)+endmonth:false;
				if(!calculate){	calculate = endmonth - startmonth;	}
				
				for(var y=0; y<4; y++)
					{
					document.getElementById(selection[sel][y]).style.borderColor = '#CCCCCC'; //clear previous
					}		
			
					if(calculate < 0)
					{
						if(sYear > eYear)
						{
						flag = 4;
							for(var y=0; y<flag; y++)
							{
							document.getElementById(selection[sel][y]).style.borderColor ='#FF0000';
							}
						}
						else
						{
							document.getElementById(selection[sel][1]).style.borderColor ='#FF0000';
							document.getElementById(selection[sel][3]).style.borderColor ='#FF0000';
						}
						
						alert('End date must be greater than or equal to start date.');
						makereadonly(readid,true);
						calculate = 0;
						
						
						
						
					}
					else if(sYear > eYear)
					{	
						alert('End date must be greater than or equal to start date.');
						makereadonly(readid,true);
						calculate = 0;			
						document.getElementById(selection[sel][0]).style.borderColor = '#FF0000';
						document.getElementById(selection[sel][2]).style.borderColor = '#FF0000';		
						
					}
					

					
					numMonths = calculate>=0?calculate+1:calculate;
					
					if(target != '')
					{
					document.getElementById(target).value = numMonths;
					}	
				}
			
			
			
			
			return numMonths;
		}
		
		
		function tick(id,sel)
		{
			divisor = 0;
			var selection = new Array();
			selection['loan'] = ['pp1','pp2','pp3','pp4','pp5'];
			selection['ben'] = ['ben1','ben2','ben3','ben4','ben5'];
			
			selection['sss'] = ['SSS_1','SSS_2','SSS_3','SSS_4','SSS_5'];
			selection['phic'] = ['PHIC_1','PHIC_2','PHIC_3','PHIC_4','PHIC_5'];
			selection['hdmf'] = ['HDMF_1','HDMF_2','HDMF_3','HDMF_4','HDMF_5'];
			selection['ecola'] = ['ECOLA_1','ECOLA_2','ECOLA_3','ECOLA_4','ECOLA_5'];
			for(var x=0; x<5; x++)//count the number of pay periods
			{
			if(document.getElementById(selection[sel][x]).checked==true) divisor += 1;
			}
						
			if(divisor==0)
			{
			alert('At least one pay period must be selected.');
			document.getElementById(id).checked=true
			divisor = 1
			}
			return divisor;
		}
		
		function disable(id,start,end,status)
		{
			inv = status==true?false:true; 
			for(var x=start; x<end; x++)
			{
			document.getElementById(id+'_'+x).disabled = status;
			document.getElementById(id+'_'+x).checked = inv;
			}
		}

		function addOption(selID,value,text)
		{
			var newOption = document.createElement('OPTION');
			opener.document.getElementById(selID).appendChild(newOption);
			newOption.value = value;
			newOption.selected = 'selected';
			newOption.text = text;		
		}
		
		function isIE()
		{
			 var ua = window.navigator.userAgent
			 var msie = ua.indexOf ( "MSIE " )
			  if ( msie > 0 )      // If Internet Explorer, return version number
				 return true
			  else                 // If another browser, return 0
				 return false
		}