function closeSlideBar(){
    document.querySelector('.slideBarCell').style.width = '0';
    setTimeout("document.querySelector('.closedBar').style.display = 'block';", 280);
    setTimeout("document.querySelector('.openSlideBar').style.visibility = 'visible';", 280);
}

function openSlideBar(){
    document.querySelector('.closedBar').style.display = 'none';
    document.querySelector('.openSlideBar').style.visibility = 'hidden';
    document.querySelector('.slideBarCell').style.width = '170px';
}


function addBoard(){

}

const listItems = document.querySelectorAll(".sidebar-list li");

listItems.forEach((item) => {
    item.addEventListener("click", () => {
        let isActive = item.classList.contains("active");

        listItems.forEach((el) => {
            el.classList.remove("active");
        });

        if (isActive) item.classList.remove("active");
        else item.classList.add("active");
    });
});

const toggleSidebar = document.querySelector(".toggle-sidebar");
const logo = document.querySelector(".logo-box");
const sidebar = document.querySelector(".sidebar");

toggleSidebar.addEventListener("click", () => {
    sidebar.classList.toggle("close");
});

logo.addEventListener("click", () => {
    sidebar.classList.toggle("close");
});

