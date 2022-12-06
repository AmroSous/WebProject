function closeSlideBar(){
    document.querySelector('.slideBar').style.width = '0';
    setTimeout("document.querySelector('.closedBar').style.display = 'block';", 240);
    setTimeout("document.querySelector('.openSlideBar').style.visibility = 'visible';", 240);
    console.log('close');
}

function openSlideBar(){
    document.querySelector('.closedBar').style.display = 'none';
    document.querySelector('.openSlideBar').style.visibility = 'hidden';
    document.querySelector('.slideBar').style.width = '18%';
    console.log('open');
}

document.querySelector('.closeCreateBoardPanel').addEventListener('click', () => {
    document.querySelector('.addBoardPlane').style.display = 'none';
})

document.querySelector('a.addBoard').addEventListener('click', ()=>{
    document.querySelector('.addBoardPlane').style.display = 'block';
})



