//currentSubTab = "iddetailtab";
function refresh()
{
     location.href = location.href;
     //currentSubTab = "idattachementtab";
     return false;
				  /*
				  setTimeout(function () {
        location.reload()
    }, 10);*/
}//alert('working');
function tabDes(elId)
{
				  //alert('el_id: ' + elId);
				  if (elId === 'txtDes'){
				  				  var txtDes = document.getElementById(elId);
				  				  var divDes = document.getElementById("divDes");
				  				  var toolsDes = document.getElementById("toolsDes");
				  				  txtDes.classList.remove("hide");
				  				  txtDes.value = divDes.innerHTML;
				  				  //alert(divDes.innerHTML);
				  				  toolsDes.classList.add("hide");
				  				  divDes.classList.add("hide");
				  }else{
				  				  var divDes = document.getElementById(elId);
				  				  var txtDes = document.getElementById("txtDes");
				  				  var toolsDes = document.getElementById("toolsDes");
				  				  divDes.classList.remove("hide");
				  				  toolsDes.classList.remove("hide");
				  				  //alert(txtDes.value);
				  				  divDes.innerHTML = txtDes.value;
				  				  txtDes.classList.add("hide");
				  }
}


function changePwd(idChange)
{
				  //alert("change");
				  document.getElementById(idChange).checked = true;
}
function reloadAddId(idVal)
{
				var url = new URL(window.location.href);
				if (url.searchParams.has('id')) {
								// Construct URLSearchParams object instance from current URL querystring.
								var queryParams = new URLSearchParams(window.location.search);
								// Set new or modify existing parameter value.
								queryParams.set("id", idVal);
								// Replace current querystring with the new one.
								history.replaceState(null, null, "?"+queryParams.toString());
								location.href = location.href;
				}else{
								location.href = location.href + "&id=" + idVal;
				}
}

function reloadNewURL(id,idVal)
{
				  //alert(id);
				var url = new URL(window.location.href);
				if (url.searchParams.has(id)) {
								// Construct URLSearchParams object instance from current URL querystring.
								var queryParams = new URLSearchParams(window.location.search);
								// Set new or modify existing parameter value.
								queryParams.set(id, idVal);
								// Replace current querystring with the new one.
								history.replaceState(null, null, "?"+queryParams.toString());
								location.href = location.href;
				}else{
								location.href = location.href + "&" + id + "=" + idVal;
				}
}
function updateTheme(condition, fields,values) {
    //fields
    var arrFields = fields.split(',');
    var arrValues = values.split(',');
				  //alert("updated theme: " + arrFields[0] + arrValues[0]);
				  let xhr = new XMLHttpRequest();
				  let json = JSON.stringify({
				  				  table: "themes",
				  				  whereclause: condition,
				  				  fields: arrFields,
				  				  values: arrValues
				  });
				  xhr.open("POST", 'ajaxml.php',true);
				  xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
				  xhr.onreadystatechange = function() { //Call a function when the state changes.
        if(xhr.readyState == 4 && xhr.status == 200) { // complete and no errors
            //alert(xhr.responseText); // some processing here, or whatever you want to do with the response
        }
    };
				  xhr.send(json);
}
function getTotalFr(quantity,unit_price)
{
				  var qty = commaToDot(quantity);
				  var price = commaToDot(unit_price);
				  var total = parseFloat(qty).toFixed(2) * parseFloat(price).toFixed(2);
				  return dotToComma(total.toString());
}

function commaToDot(string)
{
				  return string.replace(",",".");
}
function dotToComma(string)
{
				  return string.replace(".",",");
}
/* Start  Upload file */
function uploadFile(el,loc='equ') {
    //alert("location given: " + loc);
		   var files = document.getElementById(el.id).files;
		   if(files.length > 0 ){
		   		    var formData = new FormData();
         formData.append("file", files[0]);
		        //if (loc && loc.trim().length) {
		   				   formData.append("loc", loc);
		        //}
//alert(loc);
         var xhttp = new XMLHttpRequest();

         // Set POST method and ajax file path
         xhttp.open("POST", "js/uploadImage.php", true);

         // call on request changes state
         xhttp.onreadystatechange = function() {
              if (this.readyState == 4 && this.status == 200) {

                   var response = this.responseText;
                   //alert(response);
                   if(response == 1){
                        alert("Upload successfully.");
                   }else{
                        alert("File not uploaded.");
                   }
              }
         };

         // Send request with data
         xhttp.send(formData);

    }else{
         alert("Please select a file");
    }
	//window.location.reload();
     //currentSubTab = "idattachementtab";
     //refresh();
}
function getFileName(el,txtUpload,loadImage = '',loc='equ'){
    //alert("upload");
	//alert(loc + "    input File: " + el.value);
    var fullPath = el.value;
    if (fullPath) {
        var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
        var filename = fullPath.substring(startIndex);
        if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
            filename = filename.substring(1);
        }
        //alert(filename);
        document.getElementById(txtUpload).value = filename; 
        if (loadImage && loadImage.trim().length) {  
            //...
            var imgPath = document.getElementById(loadImage).src.substr(0,document.getElementById(loadImage).src.lastIndexOf('/'));
            //alert( imgPath+'/'+filename);
            document.getElementById(loadImage).src = imgPath+'/'+filename; 
        }
        uploadFile(el,loc);
    }
    
}

/*
*/
function showModal(idModal)
{
		  	// Get the modal
				  //alert(idModal);
   var modal = document.getElementById(idModal);
		  modal.style.display = "block";
}
function hideModal(idModal)
{
		  	// Get the modal
   var modal = document.getElementById(idModal);
		  modal.style.display = "none";
}

function changeURL(tag)
{
				if(window.location.href.indexOf("?") > -1) {
    if(window.location.href.indexOf("&"+tag) > -1){

        var url = window.location.href.replace("&"+tag,"")+"&"+tag;
    }
    else
    {
        var url = window.location.href+"&"+tag;
    }
}else{
    if(window.location.href.indexOf("?"+tag) > -1){

        var url = window.location.href.replace("?"+tag,"")+"?"+tag;
    }
    else
    {
        var url = window.location.href+"?"+tag;
    }
}
  window.location = url;
}
function printDoc(divID)
{
    //alert('printing: ' + divID);
				  //Get the HTML of div
				  var divElements = document.getElementById(divID).innerHTML;
				  //Get the HTML of whole page
				  var oldPage = document.body.innerHTML;
				  var newWin = window.open("");
				  var style = "<style>";
				  style    +=  " body{ -webkit-print-color-adjust:exact !important; print-color-adjust:exact !important;} ";
				  style    +=  " table { width: 100%; margin-left: auto; margin-right: auto; }";
				  style    +=  " tr th { height: 2em; background-color: #D0D3D6; white-space: nowrap; padding: .5em; color: #2E4053; margin: 0px;}";
				  style    +=  " .txt-right { text-align: right; } .txt-left { text-align: left; } .txt-center { text-align: center; }";
				  style    +=  "@media print {";
				  style    +=  "  table { width: 100%; margin-left: auto; margin-right: auto; }";
				  style    +=  "  tr th { border:1px solid #2E4053 !important; height: 2em; background-color: #D0D3D6 !important; white-space: nowrap; padding: .5em; color: #2E4053; margin: 0px;}";
				  style    +=  "  tr td { padding: .5em; border:1px solid #2E4053 !important;}";
				  style    +=  "  tr td.empty-top { padding: .5em; border-top:1px solid #2E4053 !important; }";
				  style    +=  "  tr td.empty-total { padding: .5em; border:none !important;}";
				  style    +=  " .txt-right { text-align: right; } .txt-left { text-align: left; } .txt-center { text-align: center; }";
				  style    +=  " .print-header { padding: 5px; overflow: auto; border: 1px solid #000; position: fixed; top: 10px; left: 0; right: 0; height: auto; }";
				  style    +=  " .print-container{ margin: 430px 0 0 0; padding: 0; }";
				  style    +=  " .print-container tr td.empty-total { padding: .5em; border: none;}";
				  style    +=  " .print-footer { padding: 5px; overflow: auto; border: 1px solid #000; position: fixed; bottom: 20px; left: 0; right: 0; height: auto; }";
				  style    +=  " .print-footer > table { width: 100%; margin: 0 auto; }";
		  		style    +=  "} ";
				  style    +=  "</style>";
				  
				  //Reset the page's HTML with div's HTML only
				  newWin.document.write("<html>");
				  newWin.document.write("<head>");
				  newWin.document.write("<title>");
				  newWin.document.write("ActivityReport");
				  newWin.document.write("</title>");
				  newWin.document.write("<link rel='stylesheet' href='" + window.location.pathname.replace("index.php/","") +"/css/admin.css'>");
				  newWin.document.write(style);
				  newWin.document.write("</head><body>");
				  newWin.document.write(divElements);
				  newWin.document.write("</body>");  
				  //document.body.innerHTML ="<html><head><title></title></head><body>" + divElements + "</body>";
				  //Print Page
				  //window.print();
				  newWin.print();
				  //Restore orignal HTML
				  document.body.innerHTML = oldPage;
				/*
				var newWin= window.open("");
        newWin.document.write(divElements.innerHTML);
        newWin.print();
        newWin.close();
				*/
}

const body = document.querySelector('body'),
      sidebar = body.querySelector('.sidebar'),
      toggle = body.querySelector(".toggle");
      //searchBtn = body.querySelector(".search-box"),
      //modeSwitch = body.querySelector(".toggle-switch"),
     // modeText = body.querySelector(".mode-text");
/*
toggle.addEventListener("click", () => {
				//alert('working');
				sidebar.classList.toggle("close");
				if ( sidebar.classList.contains("close")) {
								//alert("closeNav");
								changeURL("nav=no");
				}
});
*/
//let buttonClicked = false;
let numberClicked = 0;
toggle.addEventListener('click', function handleClick() {
				//alert('Submit button is clicked');
				//sidebar.classList.toggle("close");
 //if (buttonClicked) {
  //alert('Submit button has already clicked');
 //}
				  var sidebarId = document.getElementById("id_sidebar");
				  //alert(sidebarId.value);
				  if (sidebarId.value == "close")
				  {
				  				  sidebarId.value = "open";
				  				  var user = document.getElementById("id_user").value;
				  				  var whereClause = "[id_user="+user+"]";
				  				  updateTheme(whereClause,'nav','open');
        
				  }else{
				  				  sidebarId.value = "close";
				  				  var user = document.getElementById("id_user").value;
				  				  var whereClause = "[id_user="+user+"]";
				  				  updateTheme(whereClause,'nav','close');
        
				  }
				  sidebar.classList.toggle("close");
				  /*
				if((numberClicked %2 == 0) )
				{
								  document.getElementById("id_sidebar").value = "close";
								  sidebar.classList.toggle("close");
								  var user = document.getElementById("id_user").value;
								  var whereClause = "[@id="+user+"]";
								  updateTheme(whereClause,'nav','close');
                
								  //alert('Nav is opened!');
								  //changeURL('nav=no');
								  //sidebar.classList.toggle("close");
				}else{
								  //changeURL('nav=y');
								  var user = document.getElementById("id_user").value;
								  var whereClause = "[@id="+user+"]";
								  updateTheme(whereClause,'id_lang',"");
                
				}
				  */
				numberClicked++;
				//buttonClicked = true;
});

loadTheme();
function loadTheme()
{
				  var lang = document.getElementById("lang");
				  var user = document.getElementById("id_user").value;
				  //var whereClause = "[@id="+user+"]"
				  //updateTheme(whereClause,'id_lang',this.value);
                
				  //var sidebar  = document.getElementsByClassName("sidebar");
				  //alert("Language: " + lang.value + " nav : " + sidebar);
				  var sidebarId = document.getElementById("id_sidebar");
				  //alert(sidebarId.value);
				  if (sidebarId.value == "close")
				  {
				  				  sidebar.classList.toggle("close");
				  				  document.getElementById("id_sidebar").value = "close";
				  }
				  //sidebar.classList.toggle("close");
}
function updateTheme(condition, fields,values) {
    //fields
    var arrFields = fields.split(',');
    var arrValues = values.split(',');
				  //alert("updated theme: " + arrFields[0] + arrValues[0]);
				  let xhr = new XMLHttpRequest();
				  let json = JSON.stringify({
				  				  table: "themes",
				  				  whereclause: condition,
				  				  fields: arrFields,
				  				  values: arrValues
				  });
				  xhr.open("POST", 'ajaxml.php',true);
				  xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
				  xhr.onreadystatechange = function() { //Call a function when the state changes.
        if(xhr.readyState == 4 && xhr.status == 200) { // complete and no errors
            //alert(xhr.responseText); // some processing here, or whatever you want to do with the response
        }
    };
				  xhr.send(json);
}

function openTab(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" activetab", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " activetab";
}

// Get the element with id="defaultOpen" and click on it
//document.getElementById("defaultTab").click();
//document.querySelector(".defaultTab").click();
let arrow = document.querySelectorAll(".arrow");
  for (var i = 0; i < arrow.length; i++) {
    arrow[i].addEventListener("click", (e)=>{
   let arrowParent = e.target.parentElement.parentElement;//selecting main parent of arrow
   arrowParent.classList.toggle("showMenu");
    });
  }
//alert(nav);
//document.querySelector(".toggle").click();

document.querySelector(".defaultTab").click();
if (currentSubTab){
   document.getElementById(currentSubTab).click();
}
//alert(currentSubTab);