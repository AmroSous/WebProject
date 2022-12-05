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


