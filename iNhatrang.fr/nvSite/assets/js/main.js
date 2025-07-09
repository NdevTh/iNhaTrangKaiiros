// assets/js/main.js

document.addEventListener("DOMContentLoaded", function () {
  const burger = document.getElementById("burger");
  const navLinks = document.getElementById("nav-links").querySelector("ul");

  burger.addEventListener("click", () => {
    navLinks.classList.toggle("show");
  });
});
