// Captura elementos del DOM
const menuContainer = document.getElementById('containerMenu');
const menuIcon = document.querySelector('.menu-icon');
const closeIcon = document.getElementById('cerrar');


//Al hacer click mostramos el menu
menuIcon.addEventListener('click', () => {
    menuContainer.style.display = "block";
    document.body.style.overflow = 'hidden';
});
  
//Al hacer click ocultamos el menu
closeIcon.addEventListener('click', () => {
    menuContainer.style.display = "none";
    document.body.style.overflow = 'auto';
});
  