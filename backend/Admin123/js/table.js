//alert("working");
function action(id=0,tblName="myTable")
{
				var idVal = 0;
				//alert("Action : " + id + " tblName: " + tblName);
				var table = document.getElementById(tblName);
				var nRow  = table.rows.length;
				var nCol  = table.rows[0].cells.length;
				//var checkBoxes = document.querySelectorAll('[id^="chk"]');
				//Reference the CheckBoxes in Table.
				var checkBoxes = table.getElementsByTagName("INPUT");
				//alert(checkBoxes.length);
				//Loop through the CheckBoxes.
				for (var i = 0; i < checkBoxes.length; i++) {
								if (checkBoxes[i].checked) {
												idVal = checkBoxes[i].value;
												//alert(checkBoxes[i].value);
								}
				}
				//alert("Action : " + id + " idVal : " + idVal);
				var url = new URL(window.location.href);
				if (url.searchParams.has('tp') && url.searchParams.has('id')) {
								// Construct URLSearchParams object instance from current URL querystring.
								var queryParams = new URLSearchParams(window.location.search);
								// Set new or modify existing parameter value.
								queryParams.set("tp", id);
								queryParams.set("id", idVal);
								// Replace current querystring with the new one.
								history.replaceState(null, null, "?"+queryParams.toString());
								location.href = location.href;
				}else{
								location.href = location.href + "&id="+idVal+"&act=form&tp=" + id;
				}
}
function autocomplete(inp, arr) {
				//alert(inp);
  /*the autocomplete function takes two arguments,
  the text field element and an array of possible autocompleted values:*/
  var currentFocus;
  /*execute a function when someone writes in the text field:*/
  inp.addEventListener("input", function(e) {
      var a, b, i, val = this.value;
      /*close any already open lists of autocompleted values*/
      closeAllLists();
      if (!val) { return false;}
      currentFocus = -1;
      /*create a DIV element that will contain the items (values):*/
      a = document.createElement("DIV");
      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      /*append the DIV element as a child of the autocomplete container:*/
      this.parentNode.appendChild(a);
      /*for each item in the array...*/
      for (i = 0; i < arr.length; i++) {
        /*check if the item starts with the same letters as the text field value:*/
        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
          /*create a DIV element for each matching element:*/
          b = document.createElement("DIV");
          /*make the matching letters bold:*/
          b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
          b.innerHTML += arr[i].substr(val.length);
          /*insert a input field that will hold the current array item's value:*/
          b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
          /*execute a function when someone clicks on the item value (DIV element):*/
          b.addEventListener("click", function(e) {
              /*insert the value for the autocomplete text field:*/
              inp.value = this.getElementsByTagName("input")[0].value;
              /*close the list of autocompleted values,
              (or any other open lists of autocompleted values:*/
              closeAllLists();
          });
          a.appendChild(b);
        }
      }
  });
  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 38) { //up
        /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
          /*and simulate a click on the "active" item:*/
          if (x) x[currentFocus].click();
        }
      }
  });
  function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
    except the one passed as an argument:*/
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }
  /*execute a function when someone clicks in the document:*/
  document.addEventListener("click", function (e) {
      closeAllLists(e.target);
  });
}

/*An array containing all the country names in the world:*/
//var sparepart_titles = ["Afghanistan","Albania","Algeria","Andorra","Angola","Anguilla","Antigua & Barbuda","Argentina","Armenia","Aruba","Australia","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bermuda","Bhutan","Bolivia","Bosnia & Herzegovina","Botswana","Brazil","British Virgin Islands","Brunei","Bulgaria","Burkina Faso","Burundi","Cambodia","Cameroon","Canada","Cape Verde","Cayman Islands","Central Arfrican Republic","Chad","Chile","China","Colombia","Congo","Cook Islands","Costa Rica","Cote D Ivoire","Croatia","Cuba","Curacao","Cyprus","Czech Republic","Denmark","Djibouti","Dominica","Dominican Republic","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Ethiopia","Falkland Islands","Faroe Islands","Fiji","Finland","France","French Polynesia","French West Indies","Gabon","Gambia","Georgia","Germany","Ghana","Gibraltar","Greece","Greenland","Grenada","Guam","Guatemala","Guernsey","Guinea","Guinea Bissau","Guyana","Haiti","Honduras","Hong Kong","Hungary","Iceland","India","Indonesia","Iran","Iraq","Ireland","Isle of Man","Israel","Italy","Jamaica","Japan","Jersey","Jordan","Kazakhstan","Kenya","Kiribati","Kosovo","Kuwait","Kyrgyzstan","Laos","Latvia","Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Macau","Macedonia","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Mauritania","Mauritius","Mexico","Micronesia","Moldova","Monaco","Mongolia","Montenegro","Montserrat","Morocco","Mozambique","Myanmar","Namibia","Nauro","Nepal","Netherlands","Netherlands Antilles","New Caledonia","New Zealand","Nicaragua","Niger","Nigeria","North Korea","Norway","Oman","Pakistan","Palau","Palestine","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Poland","Portugal","Puerto Rico","Qatar","Reunion","Romania","Russia","Rwanda","Saint Pierre & Miquelon","Samoa","San Marino","Sao Tome and Principe","Saudi Arabia","Senegal","Serbia","Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","South Korea","South Sudan","Spain","Sri Lanka","St Kitts & Nevis","St Lucia","St Vincent","Sudan","Suriname","Swaziland","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania","Thailand","Timor L'Este","Togo","Tonga","Trinidad & Tobago","Tunisia","Turkey","Turkmenistan","Turks & Caicos","Tuvalu","Uganda","Ukraine","United Arab Emirates","United Kingdom","United States of America","Uruguay","Uzbekistan","Vanuatu","Vatican City","Venezuela","Vietnam","Virgin Islands (US)","Yemen","Zambia","Zimbabwe"];

/*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
//autocomplete(document.getElementById("titlesparepart1"), countries);


function selectGroupValue(modalId,tblNameGroup,tblNameData,tblNameSub,fields,imgId="",imgCol=0,imgPath="images/equ/blank.jpg")
{
		 		//alert("table Group: " + tblNameGroup + " table Detail: " +tblNameSub);
				 var tableGroup = document.getElementById(tblNameGroup);
		  var nRow  = tableGroup.rows.length;
				 //alert("nInput " + tableGroup.getElementsByTagName("INPUT").length);
				 var checkBoxes = tableGroup.getElementsByTagName("INPUT");
				 //alert("nInput: " + checkBoxes.length + "nRow: " + nRow);
				 var chkVal  = '';
				 var listVal = 0;
				 var equipVal  = 0;
				 //alert("working");
				 //alert(fields.length);
				/*
				 var strFields = '';
				 for (var i = 0; i < fields.length; i++)
				 {
				 				   if(fields[i] === 'checkbox' OR fields[i] === 'action'){
				 				   				  strFields = '\''+fields[i]+'\'';
				 				   }else {
				 				   				  strFields = ',\''+fields[i]+'\',';
				 				   }
				 }
				 alert(strFields);
				*/
				 for (var i  = 0; i < checkBoxes.length; i++)
				 {
				 				 //alert("working");
				 				 //alert(checkBoxes[i].checked);
				 				 if(checkBoxes[i].checked)
				 				 {
				 				 			 	//alert("working");
				 				 				 //alert(checkBoxes[i].type);
				 				 				 chkVal = checkBoxes[i].value;
				 				 				 listVal = checkBoxes[i].getAttribute("data-listId");
				 				 				 if (checkBoxes[i].getAttribute("data-equipmentId") !== null)
				 				 				 {
				 				 				 				 equipVal = checkBoxes[i].getAttribute("data-equipmentId");
				 				 				 }
				 				 				 //alert("Id Oder List: " + listVal + " Id Article Group: " + chkVal + " Id Customer: " + equipVal);
				 				 				 var hr = new XMLHttpRequest();
				 				 				 var url = "ajax.php";
				 				 				 var vars = "tablegroup="+tblNameGroup+"&table="+tblNameData+"&tablesub="+tblNameSub+"&act=add&groupid="+chkVal+"&subid="+listVal+"&equid="+equipVal;
				 				 				 hr.open("POST", url, true);
				 				 				 hr.setRequestHeader('Content-type','application/x-www-form-urlencoded; charset=ISO-8859-1;');
				 				 				 //hr.overrideMimeType('text/xml; charset=iso-8859-1');
				 				 				 //hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded;");
				 				 				 //hr.overrideMimeType('text/xml; charset=iso-8859-1');
				 				 				 //hr.setCharacterEncoding("UTF-8");
				 				 				 // Access the onreadystatechange event for the XMLHttpRequest object
				 				 				 hr.onreadystatechange = function()
				 				 				 {
				 				 				 				 //console.log(hr);
				 				 				 				 if(hr.readyState == 4 && hr.status == 200)
				 				 				 				 {
				 				 				 				 				 var return_data = hr.responseText;
				 				 				 				 				 //alert(return_data);
				 				 				 				 				 var arrData    = JSON.parse(hr.responseText);
				 				 				 				 				 //alert(arrData);
				 				 				 				 				 //alert( arrData['result'] );
				 				 				 				 				 
				 				 				 				 				 var tableHeaderRowCount = 1;
				 				 				 				 				 var table = document.getElementById(tblNameSub);
				 				 				 				 				 var rowCount = table.rows.length;
				 				 				 				 				 for (var i = tableHeaderRowCount; i < rowCount; i++) {
				 				 				 				 				 				table.deleteRow(tableHeaderRowCount);
				 				 				 				 				 }
				 				 				 				 				 //alert( arrData['message'] );
				 				 				 				 				 var nRow = arrData['result'].length;
				 				 				 				 				 document.getElementById(tblNameSub+'_records').innerText= nRow + ' Article(s)';
				 				 				 				 			 	//alert( "Rows: " + nRow + " - Cols: " + fields.length);
				 				 				 				 				 var response_data = arrData['result'];
				 				 				 				 				 
				 				 				 				 				 for (var r = 0; r < nRow; r++)
				 				 				 				 				 {
				 				 				 				 				 				  let row = document.createElement("tr");
				 				 				 				 				 				  for (var i = 0; i < fields.length; i++)
				 				 				 				 				 				  {
				 				 				 				 				 				  				   //alert("FieldName: " + fields[i]);
				 				 				 				 				 				  				   // Create cells
				 				 				 				 				 				  				   let c1 = document.createElement("td");
				 				 				 				 				 				  				   var strType = fields[i];
				 				 				 				 				 				  				   //c1.attr("id",fields[i]);
				 				 				 				 				 				  				   c1.id = fields[i];
				 				 				 				 				 				  				   //alert( response_data[r]["id_"+tblNameSub] );
				 				 				 				 				 				  				   if (fields[i] === 'description'){
				 				 				 				 				 				  				   				  //alert("Drscription: " + response_data[r][fields[i]]);
				 				 				 				 				 				  				   }
				 				 				 				 				 				  				   //alert(strType.indexOf("date") !== -1); // true
				 				 				 				 				 				  				   if (fields[i] == 'action'){
				 				 				 				 				 				  				   				  c1.classList.add("txt-center");
				 				 				 				 				 				  				   				  //var arrToPass = Object.assign({},fields);
				 				 				 				 				 				  				   				  var exFields = fields;
				 				 				 				 				 				  				   				  //c1.innerHTML = '<a href="javascript:saveSelect(\''+tblNameData+'\',\''+tblNameData+nRow+'\',\''+fields[i]+'\');"><img class="tbl-ico32" src="../Bundles/images/advancetools/3067443.png"/></a>';
				 				 				 				 				 				  				   }else if (fields[i] === 'checkbox'){
				 				 				 				 				 				  				   				  c1.classList.add("txt-center");
				 				 				 				 				 				  				   				  var fieldName = fields[i];
				 				 				 				 				 				  				   				  //alert(fields);
				 				 				 				 				 				  				   				  //var arrFields = fields.split(',');
				 				 				 				 				 				  				   				  //c1.innerHTML = '<input onchange="editRow(this,\''+tblNameSub+'\',[\'checkbox\',\'code\',\'title\' ,\'order_quantity\', \'sale_unit_price\',\'sale_discount_percent\',\'sale_discount_amount\',\'sale_amount_cost\',\'action\']);" data-listId="'+response_data[r]["id_"+tblNameSub]+'" id="'+tblNameSub+r+'" type="checkbox" value="'+response_data[r]["id_"+tblNameSub]+'">';
				 				 				 				 				 				  				   				  c1.innerHTML = '<input onchange="editRow(this,\''+tblNameSub+'\',[]);" data-listId="'+response_data[r]["id_"+tblNameSub]+'" id="'+tblNameSub+r+'" type="checkbox" value="'+response_data[r]["id_"+tblNameSub]+'">';
				 				 				 				 				 				  				   }else{
				 				 				 				 				 				  				   				  var fieldName = fields[i];
				 				 				 				 				 				  				   				  c1.innerText = response_data[r][fieldName];
				 				 				 				 				 				  				   }
				 				 				 				 				 				  				   // Append cells to row
				 				 				 				 				 				  				   row.appendChild(c1)
				 				 				 				 				 				  }//column
				 				 				 				 				 				  // Append row to table body
				 				 				 				 				 				  table.appendChild(row);
				 				 				 				 				 }//rows
				 				 				 				 }//readyState*/
				 				 				 }//send hr
				 				 				 // Send the data to PHP now... and wait for response to update the status div
				 				 				 hr.send(vars); // Actually execute the request
				 				 }//checked checkbox
				 }//for loop
}
function passValue(srcId,desId)
{
				 document.getElementById(desId).value = document.getElementById(srcId).value;
}
function changeChecked(tblName,chkId)
{
				  var table = document.getElementById(tblName);
				  var checkBoxes = table.getElementsByTagName("INPUT");
				  for (var i = 0; i < checkBoxes.length; i++)
				  {
				  				 //alert("inpId: " + checkBoxes[i].id + ' chkId: ' + chkId);
				 		 		 if(checkBoxes[i].id === chkId)
				 				  {
				 				  				 checkBoxes[i].checked = true;
				 				  }else{
				 				  				 checkBoxes[i].checked = false;
				 				  }
				  }
}
function selectValue(modalId,tblName,desId,imgId="",imgCol=0,imgPath="images/equ/blank.jpg")
{
				 var input = document.getElementById(desId);
				 var inputId = document.getElementById(desId.replace("txt", "id_"));
				 //alert("Id des: " + desId.replace("txt", "id_"));
				 var table = document.getElementById(tblName);
		  var nRow  = table.rows.length;
				 //alert("nInput" + table.getElementsByTagName("INPUT").length);
				 var checkBoxes = table.getElementsByTagName("INPUT");
				 //alert("nInput: " + checkBoxes.length);
				 var chkVal = '';
				 var listVal = 0;
				 for (var i = 0; i < checkBoxes.length; i++)
				 {
				 				 if(checkBoxes[i].checked)
				 				 {
				 				 				 //' listVal: ' + checkBoxes[i].getAttribute("data-listId") + ' chkVal: ' + checkBoxes[i].value + 
				 				 				 //alert(' listVal: ' + checkBoxes[i].getAttribute("data-listId") + ' chkVal: ' + checkBoxes[i].value +  ' imag: ' + checkBoxes[i].getAttribute("data-img"));
				 				 				 chkVal = checkBoxes[i].value;
				 				 				 listVal = checkBoxes[i].getAttribute("data-listId");
				 				 				 if (checkBoxes[i].getAttribute("data-img") !== null && document.getElementById("txtUpload") !== null)
				 				 				 {
				 				 				 				 document.getElementById("txtUpload").value = checkBoxes[i].getAttribute("data-img") ;
				 				 				 }
				 				 				
				 				 				 if (checkBoxes[i].getAttribute("data-img") !== null && document.getElementById("imgPreview") !== null)
				 				 				 {
				 				 				 				 document.getElementById("imgPreview").src = "images/equ/" + checkBoxes[i].getAttribute("data-img") ;
				 				 				 }
				 				 				 if (imgId !== "" && colId !== 0)
				 				 				 {
				 				 				 				document.getElementById(imgId).src = imgPath + table.rows[i].cells[colId].innerText;
				 				 				 }
				 				 				
				 				 				 //alert(document.getElementById("imgPreview") +' listVal: ' + checkBoxes[i].getAttribute("data-listId") + ' chkVal: ' + checkBoxes[i].value +  ' imag: ' + checkBoxes[i].getAttribute("data-img"));
				 				 				
				 				 }
				 				
				 }
				 //alert("table: " + tblName + " chkVal: " + chkVal + " listVal: " + listVal );
				 input.value = chkVal;
				 inputId.value = listVal;
				 hideModal(modalId);
				 
}
function saveSelect(tblName,rowId,fields)
{
				  //var fields = fields;
				  var arrFields = fields.split(',');
				  //alert("Save Select: " + tblName +' fields: ' + fields + ' 1st arrLength: ' + arrFields[0] );
				  //alert("2nd Column value: " + document.getElementById(arrFields[1]+rowId).value + ' col: ' + arrFields.length);
				  // Call postData function   
				  //post('php/posts.php',{ title: 'fruits', names: ['Apple', 'Banana', 'Mango'], basketId: 1 });     
				  var hr = new XMLHttpRequest();
				  var url = "ajax.php";
				  var vars = "table="+tblName+"&act=add";
				  //var varname = "vars";
				  var content = '';
				  for (var i = 1; i < (arrFields.length -1); i++)
				  {  
				  				  var el = document.getElementById(arrFields[i]+rowId);
				  				  
				  				  if (el.type === "text" ){
				  				  				  //alert(el.value);
				  				  				  eval("content = content + '&' + '" + arrFields[i] + "' + '=' + '" + el.value + "';");
				  				  }
				  }
				  vars = vars + content;
				  hr.open("POST", url, true);
				  hr.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
				  if (hr.overrideMimeType) { 
				  				hr.overrideMimeType('text/xml; charset=iso-8859-1');
				  }
				  //hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded;");
				  //hr.overrideMimeType('text/xml; charset=iso-8859-1');
				  //hr.setCharacterEncoding("UTF-8");
				  // Access the onreadystatechange event for the XMLHttpRequest object
				  hr.onreadystatechange = function()
				  {
				  				//console.log(hr);
				  				if(hr.readyState == 4 && hr.status == 200)
				  				{
				  								 //var return_data = hr.responseText;
				  								 //alert(return_data);
				  								 var arrData    = JSON.parse(hr.responseText);
				  								 //alert(arrData);
				  								 //alert(arrData['title']);
				  								 var tableId = arrData['table'];
				  								 var tableHeaderRowCount = 1;
				  								 var table = document.getElementById(tableId);
				  								 var rowCount = table.rows.length;
				  								 for (var i = tableHeaderRowCount; i < rowCount; i++) {
				  								 				table.deleteRow(tableHeaderRowCount);
				  								 }
				  								 //alert(JSON.stringify( arrData['result']) );
				  								 //alert( arrData['result'] instanceof Object);
				  							 	alert( arrData['message'] );
				  								 var nRow = arrData['result'].length;
				  								 var response_data = arrData['result'];
				  								 for (var r = 0; r < nRow; r++)
				  								 {
				  								 				 let row = document.createElement("tr");
				  								 				 for (var i = 0; i < arrFields.length; i++)
				  								 				 {
				  								 				 				// Create cells
				  								 				 				let c1 = document.createElement("td");
				  								 				 				var strType = arrFields[i];
				  								 				 				//alert(strType.indexOf("date") !== -1); // true
				  								 				 				
				  								 				 				// Insert data to cells
				  								 				 				if (arrFields[i] == 'checkbox')
				  								 				 				{
				  								 				 								c1.classList.add("txt-center");
				  								 				 								//alert("idRow" + rowId + " iRow: " + (tblName+(r+1)) );
				  								 				 								var fieldName = arrFields[(i+1)];
				  								 				 								if (rowId === (tblName+(r+1)))
				  								 				 								{
				  								 				 												c1.innerHTML = '<input data-listId="'+response_data[r]["id_"+tblName]+'" id="'+tblName+r+'" type="checkbox" checked value="'+response_data[r][fieldName]+'">';
				  								 				 								}else{
				  								 				 												c1.innerHTML = '<input id="'+tblName+r+'" type="checkbox" value="'+response_data[r][fieldName]+'">';
				  								 				 								}
				  								 				 				}
				  								 				 				else if (arrFields[i] == 'action')
				  								 				 				{
				  								 				 								c1.classList.add("txt-center");
				  								 				 								//var arrToPass = Object.assign({},fields);
				  								 				 								var exFields = fields;
				  								 				 								c1.innerHTML = '<!--input type="text" id="'+arrFields[i]+tblName+nRow+'" name="arrid_'+tblName+'[]"  value="0" /--><a href="javascript:saveSelect(\''+tblName+'\',\''+tblName+nRow+'\',\''+Object.values(arrFields)+'\');"><img class="tbl-ico32" src="Bundles/images/advancetools/3067443.png"/></a>';
				  								 				 				}
				  								 				 				else{
				  								 				 								var fieldName = arrFields[i];
				  								 				 								c1.innerText = response_data[r][fieldName];
				  								 				 				}
				  								 				 				// Append cells to row
				  								 				 				row.appendChild(c1);
				  								 				 }
				  								 				 // Append row to table body
				  								 				 table.appendChild(row);
				  								 }
				  								 //document.getElementById("status").innerHTML = return_data;
				  				 }
				  }
				  // Send the data to PHP now... and wait for response to update the status div
				  hr.send(vars); // Actually execute the request
}


function editRow(el,tblName,fields)
{
		  //alert("Edit Row");
		  //Reference the Table.
		  var table = document.getElementById(tblName);
		  var nRow  = table.rows.length;
		  var nCol  = table.rows[0].cells.length;
				 var arrFields = fields.indexOf;
		  //var checkBoxes = document.querySelectorAll('[id^="chk"]');
		  //Reference the CheckBoxes in Table.
		  var checkBoxes = table.getElementsByTagName("INPUT");
            
		  var strId = el.id;
		  //alert("Edit Row: "+ strId);
		  var ind   = Number(strId.replace(tblName,"")); 
		  //alert("Edit Row: "+ ind + " length col: " + nCol + " length Row: " + checkBoxes.length);
		  //Loop through the CheckBoxes.
   for (var i = 1; i < checkBoxes.length; i++) {
   				   if (checkBoxes[i].checked) {
   				   				  var row = checkBoxes[i].parentNode.parentNode;
   				   				  //alert('input type: ' + checkBoxes[i].type);
   				   				  var chkId = checkBoxes[i].id;
   				   				  //alert("checked " + chkId);
   				   				  for (var col = 1; col < nCol; col++)
   				   				  {
   				   				  				   //alert(row.cells[col].id);
   				   				  				   if (row.cells[col].id == 'action' || fields[col] == "action"){
       		   				  				  //alert('Checkbox value: ' + document.getElementById(chkId).value);
       		   				      row.cells[col].innerHTML = '<input class="disable" id="'+fields[col]+chkId+'" name="arrid_'+tblName+'[]"  type="text" value="'+document.getElementById(chkId).value+'" />';
       		   		    }
   				   				  				   else if (fields[col] !== "action")
   				   				  				   {
   				   				  				   				  //alert(row.cells[col].id);
   				   				  				   				  var txtName = fields[col];
   				   				  				   				  if (row.cells[col].id !== "" && row.cells[col].id !== null){
   				   				  				   				  				  txtName = row.cells[col].id;
   				   				  				   				  }
   				   				  				   				  row.cells[col].innerHTML = '<input class="" id="'+fields[col]+chkId+'" name="arr'+txtName+'[]"  type="text" value="'+row.cells[col].innerText+'" />';
   				   				  				   				  //alert("table: "+tblName+" arr"+txtName);
   				   				  				   				  var strField = "" + fields[col]+chkId;
   				   				  				   				  var arrField = fields[col] + "s";
   				   				  				   				  //if (strField.indexOf("title") !== 1){
   				   				  				   				      //autocomplete(document.getElementById(fields[col]+chkId), fields[col].'s');
   				   				  				   				  				  //eval("autocomplete(document.getElementById('" + strField + "')," + arrField + ");");
   				   				  				   				  //}
   				   				  				   }//End if
   				   				  				   else{
   				   				  				   				row.cells[col].innerHTML = '<a href=""><img class="tbl-ico32" src="Bundles/images/advancetools/3067443.png"/></a>';
   				   				  				   }//End else if
   				   				  				   //eval("autocomplete(document.getElementById('" + fields[col]+chkId + "')," + fields[col] + "s);");
   				   				  }//End for loop Cols
   				   				  for (var col = 1; col < nCol; col++)
   				   				  {
   				   				  				   var strField = "" + fields[col]+chkId;
   				   				  				   var arrField = tblName + "_" + fields[col] + "s";
   				   				  				   eval("autocomplete(document.getElementById('" + strField + "')," + arrField + ");");
   				   				  }//End for loop Cols
   				   }/*
   				else{
   				   				  var row = checkBoxes[i].parentNode.parentNode;
   				   				  //alert('input type: ' + checkBoxes[i].type);
   				   				  var chkId = checkBoxes[i].id;
   				   				  //alert("checked " + chkId);
   				   				  for (var col = 1; col < nCol; col++)
   				   				  {
   				   				  				   if (fields[col] == "action"){
       		   				  				  //alert('Checkbox value: ' + document.getElementById(chkId).value);
       		   				      row.cells[col].innerHTML = '<input class="disable" id="'+fields[col]+chkId+'" name="arrid_'+tblName+'[]"  type="text" value="'+document.getElementById(chkId).value+'" />';
       		   		    }
   				   				  				   else if (fields[col] !== "action")
   				   				  				   {
   				   				  				   				  row.cells[col].innerText = document.getElementById(fields[col]+chkId).value;
   				   				  				   }//End if
   				   				  				   else{
   				   				  				   				row.cells[col].innerHTML = '<a href=""><img class="tbl-ico32" src="Bundles/images/advancetools/3067443.png"/></a>';
   				   				  				   }//End else if
   				   				  				   //eval("autocomplete(document.getElementById('" + fields[col]+chkId + "')," + fields[col] + "s);");
   				   				  }//End for loop Cols
   				   }
   				   /*
       if (checkBoxes[i].checked) {
           var row = checkBoxes[i].parentNode.parentNode;
       		   //alert('input type: ' + checkBoxes[i].type);
       		   var chkId = checkBoxes[i].id;
       		   //alert("checked " + i);
       		   for (var col = 1; col < nCol; col++) {
       		   		   var elementTag = row.cells[col].innerText;
       		   		   //alert('field: ' + (fields[col]+chkId) +' Text: ' + elementTag);
       		   				  if (fields[col] == "action"){
       		   				  				  //alert('Checkbox value: ' + document.getElementById(chkId).value);
       		   				      row.cells[col].innerHTML = '<input class="disable" id="'+fields[col]+chkId+'" name="arrid_'+tblName+'[]"  type="text" value="'+document.getElementById(chkId).value+'" />';
       		   		   }
       		   				  var strType = ''+fields[col];
       		   				  //alert(strType.indexOf(substring) !== -1); // true
       		   		   if (elementTag != "" && fields[col] != "action" && (strType.indexOf(substring) !== -1)){
		  		   		           row.cells[col].innerHTML = '<input id="'+fields[col]+chkId+'" name="arr'+fields[col]+'[]"  type="date" value="' + row.cells[col].innerText + '" />';
       		   		   }else if (elementTag != "" && fields[col] != "action"){
		  		   		           row.cells[col].innerHTML = '<input id="'+fields[col]+chkId+'" name="arr'+fields[col]+'[]"  type="text" value="' + row.cells[col].innerText + '" />';
       		   		   }
		  		       }
       }else{
       		   var row = checkBoxes[i].parentNode.parentNode;
       		   //alert("checked " + i);
       		   for (var col = 1; col < nCol; col++) {
       		   		   var elementTag = row.cells[col].innerText;
       		   		   //alert(elementTag);
       		   		   if (ind == i){
		  		   		           //row.cells[col].innerHTML = '<input id="'+fields[col]+i+'" type="text" value="' + row.cells[col].innerText + '" />';
       		   		   		   //let text = "Data isnot modified!\nEither OK or Cancel.";
       		   		   		   //if (confirm(text) == true) {
       		   		   		  		//} else {
       		   		   		      checkBoxes[i].checked = true;
       		   		   		  		//}
       		   		   }
		  		       }
		      }*/
   }//End for rows
}

function checkAll(el,tblName,fields)
{
		  //Reference the Table.
   var grid = document.getElementById(tblName);
		  var nRow  = grid.rows.length;
		  //alert(nRow);
		  var nCol  = grid.rows[0].cells.length;
   
   //Reference the CheckBoxes in Table.
   var checkBoxes = grid.getElementsByTagName("INPUT");
   var message = "Id  Name                  Country\n";
   if (el.checked){
   		   //Loop through the CheckBoxes.
   		   for (var i = 1; i < nRow; i++) {
   		   		   checkBoxes[i].checked =true;
       }
   		   		editRow(el,tblName,fields);
   }else{
   		   
   		   for (var i = 1; i < grid.getElementsByTagName("INPUT").length; i++) {
   		   		   checkBoxes[i].checked = false;
   		   		   //alert("not checked: " +i+ checkBoxes[i].type);
   		   		   if (checkBoxes[i].type == 'checkbox')
   		   		   {
   		   		   		   var chkId = checkBoxes[i].id;
   		   		   		   //alert(checkBoxes[i].id);
   		   		   		   //alert(checkBoxes[i].value + ' Col: ' + nCol);
   		   		   		   var row = checkBoxes[i].parentNode.parentNode;
   		   		   		   //col.innerText = checkBoxes[i].value;
   		   		   	
   		   		       for (var col = 1; col < nCol; col++) {
   		   		       		    //alert(row.cells[col].innerHTML);
   		   		   		        row.cells[col].innerHTML = document.getElementById(fields[col]+chkId).value;
   		   		       }
   		   		   }
       }
   		   
   }
		   
    //Display selected Row data in Alert Box.
    //alert(message);       
}

function post(path, params, method) {
				//Create form
				const hidden_form = document.createElement('form');
				
				// Set method to post by default
				hidden_form.method = method || 'post';
				// Set path
				hidden_form.action = path;
				
				for (const key in params) {
								if (params.hasOwnProperty(key)) {
												const hidden_input = document.createElement('input');
												hidden_input.type = 'hidden';
												hidden_input.name = key;
												hidden_input.value = params[key];
												hidden_form.appendChild(hidden_input);
								}
				}
				
				document.body.appendChild(hidden_form);
				hidden_form.submit();
}

function addRowSelect(tblName,fields=[]) {
				//alert("Add Row");
				var table = document.getElementById(tblName);
				var nRow = table.rows.length;
				var nCol = table.rows[0].cells.length;
				//alert("Add New Row to table :" + tblName + ' nb of row: ' + nRow);
				//var n2Col = table.rows[1].cells.length;
				var objKey = Object.keys(fields);
				/*
				alert("Add New Row to table :" + tblName + ' nb of row: ' + nRow + ' nb of col at 1st Row: '+ n2Col);
				if ( n2Col == 0){
								table.deleteRow(1);
				}
				*/
				// Create row element
				let row = document.createElement("tr");
				//Add the Columns to the table.
				for (var i = 0; i < nCol; i++) {
								// Create cells
								let c1 = document.createElement("td");
								var strType = fields[i];
								//alert(strType.indexOf("date") !== -1); // true
       		   		   
								// Insert data to cells
								if (fields[i] == 'checkbox')
								{
												c1.classList.add("txt-center");
												c1.innerHTML = '<input id="row'+i+'" type="checkbox" checked>';
								}
								else if (fields[i] == 'action')
								{
												c1.classList.add("txt-center");
												//var arrToPass = Object.assign({},fields);
												var exFields = fields;
												c1.innerHTML = '<!--input type="text" id="'+fields[i]+tblName+nRow+'" name="arrid_'+tblName+'[]"  value="0" /--><a href="javascript:saveSelect(\''+tblName+'\',\''+tblName+nRow+'\',\''+Object.values(fields)+'\');"><img class="tbl-ico32" src="../Bundles/images/advancetools/3067443.png"/></a>';
								}
								else if (strType.indexOf("date") !== -1)
								{
												c1.innerHTML = '<input type="date" id="'+fields[i]+tblName+nRow+'" name="arrid_'+tblName+'[]"  value="0" /><!-- a href="'+window.location+'"><img class="tbl-ico32" src="Bundles/images/advancetools/3067443.png"/></a-->';
								}else{
												c1.innerHTML = '<input type="text" id="'+fields[i]+tblName+nRow+'" name="arr' + fields[i] + '[]" value="" />';
								}
								// Append cells to row
								row.appendChild(c1);
				}
					// Append row to table body
				table.appendChild(row);
}
function addRow(tblName,fields=[]) {
				//alert("Add Row : " + tblName);
				var table = document.getElementById(tblName);
				//alert("Table : " + table);
				var nRow = table.rows.length;
				//alert("Number Row : " + nRow);
				var nCol = table.rows[0].cells.length;
				//alert("Add New Row to table :" + tblName + ' nb of row: ' + nRow);
				var n2Col = table.rows[1].cells.length;
				var objKey = Object.keys(fields);
				
				//alert("Add New Row to table :" + tblName + ' nb of row: ' + nRow + ' nb of col at 1st Row: '+ n2Col);
				if ( n2Col == 0){
								table.deleteRow(1);
				}
				
				// Create row element
				let row = document.createElement("tr");
				//Add the Columns to the table.
				for (var i = 0; i < nCol; i++) {
								// Create cells
								let c1 = document.createElement("td");
								var strType = fields[i];
								//alert(strType.indexOf("date") !== -1); // true
       		   		   
								// Insert data to cells
								if (fields[i] == 'checkbox')
								{
												c1.classList.add("txt-center");
												c1.innerHTML = '<input id="row'+i+'" type="checkbox" checked>';
								}
								else if (fields[i] == 'action')
								{
												c1.innerHTML = '<input type="text" id="'+fields[i]+tblName+nRow+'" name="arrid_'+tblName+'[]"  value="0" /><!-- a href="'+window.location+'"><img class="tbl-ico32" src="Bundles/images/advancetools/3067443.png"/></a-->';
								}
								else if (strType.indexOf("date") !== -1)
								{
												c1.innerHTML = '<input type="date" id="'+fields[i]+tblName+nRow+'" name="arrid_'+fields[i]+'[]"  value="0" /><!-- a href="'+window.location+'"><img class="tbl-ico32" src="Bundles/images/advancetools/3067443.png"/></a-->';
								}else{
												c1.innerHTML = '<input type="text" name="arr' + fields[i] + '[]" value="" />';
								}
								// Append cells to row
								row.appendChild(c1);
				}
					// Append row to table body
				table.appendChild(row);
}
function myFunction(elem,tblName="myTable") {
        var input, filter, table, tr, td, i, txtValue;
        input = elem;
        filter = input.value.toUpperCase();
        table = document.getElementById(tblName);
        tr = table.getElementsByTagName("tr");
        for (i = 1; i < tr.length; i++) {
            tds = tr[i].getElementsByTagName("td");
            var matches = false;

            for (j = 0; j < tds.length; j++) {
                if (tds[j]) {
                    txtValue = tds[j].textContent || tds[j].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        matches = true;
                    } 
                }
            }

            if(matches == true)
            {
                tr[i].style.display = "";
            }
             else {
                    tr[i].style.display = "none";
                }

            }
        }

function sortTable(n,tblName="myTable") {
				//alert(n);
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById(tblName);
  switching = true;
  var img = document.getElementById("col"+n+tblName);
				let imgs = document.querySelectorAll('[id^="col"]')
var adminRoot = "../";
				
imgs.forEach(imgCol => {
				//alert(imgCol.id);
				//var adminDir = true;
  // so stuff here
				//alert (adminDir);
				imgCol.src= "" +  adminRoot + "Bundles/images/advancetools/4340090.png";
				
});
  //Set the sorting direction to ascending:
  dir = "asc"; 
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    rows = table.rows;
    /*Loop through all table rows (except the
    first, which contains table headers):*/
    for (i = 1; i < (rows.length - 1); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      /*Get the two elements you want to compare,
      one from current row and one from the next:*/
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      /*check if the two rows should switch place,
      based on the direction, asc or desc:*/
      if (dir == "asc") {
    
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      //Each time a switch is done, increase this count by 1:
      switchcount ++;      
    } else {
      /*If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again.*/
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
				  				img.src= "" +  adminRoot + "Bundles/images/advancetools/"+dir+"3.png";
}

