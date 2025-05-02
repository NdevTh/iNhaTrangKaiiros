// Burger menu
document.addEventListener("DOMContentLoaded", function () {
    const burger = document.getElementById("burger");
    const nav = document.getElementById("nav-links").querySelector("ul");
  
    burger.addEventListener("click", () => {
      nav.classList.toggle("active");
    });
  });

function showMenuItems(menuItems)
{
  alert("working");
     //2) Construire la chaîne de caractères
    let message = "Voici les plats de cette semaine :\n\n";  // \n = saut de ligne
    for (let i = 0; i < menuItems.length; i++) {
      // Ajoute chaque item + numéro
      message += (i + 1) + ". " + menuItems[i]["title"] + "\n";
    }

    // 3) Afficher la liste via une alert()
    alert(message);
}