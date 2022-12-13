
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
    const arr = {};
    arr['boardName'] = document.querySelector('#boardNameInput').value;
    arr['template'] = document.querySelector('select#boardTemplate').value;
    sendRequest(addBoard, RequestType.AddBoard, JSON.stringify(arr));
})

// change between boards
document.querySelectorAll('table.boardsList td').forEach(row => {
    row.addEventListener('click', ()=>{
        window.location.href = "http://localhost/Schema/pages/boards.php?id=" + row.dataset.id;
    })
})

//boards context
document.querySelectorAll('span.ellipseSpan').forEach(item => {
    item.addEventListener('click', e => {
        e.preventDefault();
        e.stopPropagation();
        const context = document.querySelector('.contextBoards');
        context.style.display = 'none';
        context.style.top = (e.clientY + 1)+'px';
        context.style.left = (e.clientX + 5)+'px';
        context.style.display = 'block';
        context.dataset.sourceID = item.closest('td').dataset.id;
        context.dataset.sourceName = item.previousElementSibling.innerHTML;
    })
})

document.querySelector('div.contextBoards div.contextBoardsDelete').addEventListener('click', ()=>{
    if (confirm("Are you sure you want to delete ".concat(document.querySelector('.contextBoards').dataset.sourceName).concat(" list ?"))){
        const arr = {};
        arr['boardID'] = document.querySelector('.contextBoards').dataset.sourceID;
        sendRequest(deleteBoard, RequestType.DeleteBoard, JSON.stringify(arr));
    }
})

document.querySelector('div.contextBoards div.contextBoardsRename').addEventListener('click', ()=>{
    document.querySelector('div.renameBoardGlass').style.display = 'block';
    const pane = document.querySelector('div.boardRenamePane');
    pane.querySelector('#boardRenameInput').value = document.querySelector('div.contextBoards').dataset.sourceName;
    pane.style.display = 'block';
    pane.querySelector('#boardRenameInput').focus();
})

// close popup and context menu when clicking outside
document.addEventListener('click', e => {
    document.querySelector('.contextBoards').style.display = 'none';
    const element = document.querySelector('.userProfile');
    element.style.height = '0';
    element.classList.remove('selectedPopup');
    document.querySelector('li div.userImg').classList.remove('selectedPopupParent');
})

//------------------------- board rename pane glass ----------------------

document.querySelector('.renameBoardGlass').addEventListener('click', ()=>{
    document.querySelector('div.renameBoardGlass').style.display = 'none';
    const pane = document.querySelector('div.boardRenamePane');
    pane.style.display = 'none';
})

document.querySelector('input#boardRenameInput').addEventListener('keydown', e=>{
    if (e.keyCode === 13){
        const arr = {};
        arr['boardName'] = document.querySelector('input#boardRenameInput').value;
        arr['boardID'] = document.querySelector('div.contextBoards').dataset.sourceID;
        if (arr['boardName'] != ''){
            sendRequest(renameBoard, RequestType.RenameBoard, JSON.stringify(arr));
        }
    }
})
