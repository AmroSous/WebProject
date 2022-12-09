
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

// close create new Board Panel
document.querySelector('.closeCreateBoardPanel').addEventListener('click', () => {
    document.querySelector('.addBoardPlane').style.display = 'none';
})

// open create new Board Panel
document.querySelector('a.addBoard').addEventListener('click', ()=>{
    document.querySelector('.addBoardPlane').style.display = 'block';
})

// submit create board .. ajax
document.querySelector('#submitCreateCard').addEventListener('click', ()=>{
    sendRequest(addBoard, RequestType.AddBoard, document.querySelector('#boardNameInput').value);
})

// change between boards
document.querySelectorAll('table.boardsList td').forEach(row => {
    row.addEventListener('click', ()=>{
        window.location.href = "http://localhost/Schema/pages/boards.php?id=" + row.dataset.id;
    })
})



