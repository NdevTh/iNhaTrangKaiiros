// Burger menu
document.addEventListener("DOMContentLoaded", function () {
    const burger = document.getElementById("burger");
    const nav = document.getElementById("nav-links").querySelector("ul");
  
    burger.addEventListener("click", () => {
      nav.classList.toggle("active");
    });
  });
  alert