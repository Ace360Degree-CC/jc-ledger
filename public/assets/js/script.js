
// My Javascript - Hameed

// Side Bar Load 
let SideBarCollapsed = localStorage.getItem('sidebar')===true;

function updateSidebarLocal(){
    SideBarCollapsed=!SideBarCollapsed
    localStorage.setItem('sidebar',SideBarCollapsed);
}


// Toggle Sidebar with Toggle Button Click 
const sideBarToggler = document.getElementById('sidebar-toggle');
const sidebar = document.querySelector('.sidebar-theme');
const navBar = document.querySelector('.nav-theme');
const toggleIcon = document.querySelector('#toggle-icon');
const PageWrapper  = document.querySelector('.page-wrapper-theme');

sideBarToggler.addEventListener('click', ()=>{
    sidebar.classList.toggle('collapsed');
    sidebar.classList.toggle('expanded');
    navBar.classList.toggle('expanded');
    PageWrapper.classList.toggle('collapsed');
    toggleIcon.classList.toggle('fa-expand');
    toggleIcon.classList.toggle('fa-compress');
    updateSidebarLocal();
});

function expandMenu(){
    sidebar.classList.remove('collapsed');
    sidebar.classList.add('expanded');
    navBar.classList.add('expanded');
    PageWrapper.classList.add('collapsed');
}


sidebar.addEventListener('mouseenter',function(){
    expandMenu();
});

sidebar.addEventListener('mouseleave',function(){
    if(SideBarCollapsed){
    sidebar.classList.add('collapsed');
    sidebar.classList.remove('expanded');
    navBar.classList.remove('expanded');
    PageWrapper.classList.remove('collapsed');
    }
})


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





// datatable Js 

let table = new DataTable('.datatable');





