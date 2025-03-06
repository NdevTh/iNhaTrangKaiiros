//alert('working');
/*
*/
function printDoc(divID)
{
    alert('printing: ' + divID);
				  //Get the HTML of div
				  var divElements = document.getElementById(divID).innerHTML;
				      //Get the HTML of whole page
				      var oldPage = document.body.innerHTML;
				      var newWin = window.open("");
				      //Reset the page's HTML with div's HTML only
				      newWin.document.write("<html>");
				      newWin.document.write("<head>");
				      newWin.document.write("<title>");
				      newWin.document.write("</title>");
				      newWin.document.write("<link rel='stylesheet' href='css/admin.css'>");
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
    toggle.addEventListener("click", () => {
    				//alert('working');
      sidebar.classList.toggle("close");
    });
    toggle.click();
/*
    searchBtn.addEventListener("click", () => {
      sidebar.classList.remove("close");
    })
    modeSwitch.addEventListener("click", () => {
      body.classList.toggle("dark");
      if (body.classList.contains("dark")) {
        modeText.innerText = "Light mode";
      } else {
        modeText.innerText = "Dark mode";
      }
    });*/
function openTab(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultTab").click();

let arrow = document.querySelectorAll(".arrow");
  for (var i = 0; i < arrow.length; i++) {
    arrow[i].addEventListener("click", (e)=>{
   let arrowParent = e.target.parentElement.parentElement;//selecting main parent of arrow
   arrowParent.classList.toggle("showMenu");
    });
  }
