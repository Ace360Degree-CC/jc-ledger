
// My Javascript - Hameed


// Toggle Sidebar with Toggle Button Click 
const sideBarToggler = document.getElementById('sidebar-toggle');
const sidebar = document.querySelector('.sidebar-theme');
const navBar = document.querySelector('.nav-theme');
const toggleIcon = document.querySelector('#toggle-icon');

sideBarToggler.addEventListener('click', ()=>{
    sidebar.classList.toggle('collapsed');
    sidebar.classList.toggle('expanded');
    navBar.classList.toggle('expanded');
    toggleIcon.classList.toggle('fa-expand');
    toggleIcon.classList.toggle('fa-compress');
});


const dropDownTrigger = document.querySelectorAll('.menu-list-item.dropdown');
dropDownTrigger.forEach((item,index)=>{
    item.addEventListener('click',()=>{
        item.classList.toggle('expanded');
    })
});


// Show Profile Menu
let profileElem = document.querySelector('.profile-launch');
let profileMenu = document.querySelector('.profile-menu');

profileElem.addEventListener('click', (event) => {
    profileMenu.classList.toggle('show');
    event.stopPropagation(); // Prevent immediate closing when clicking the button
});

document.addEventListener('click', function (event) {
    if (!profileMenu.contains(event.target) && !profileElem.contains(event.target)) {
        profileMenu.classList.remove('show');
    }
});



