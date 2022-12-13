const RequestType = {
    'ListOrder': 'ListOrder',
    'CardOrder': 'CardOrder',
    'ListName': 'ListName',
    'CardName': 'CardName',
    'CardDesc': 'CardDesc',
    'AddBoard': 'AddBoard',
    'AddCard': 'AddCard',
    'AddList': 'AddList',
    'DeleteBoard': 'DeleteBoard',
    'DeleteList': 'DeleteList',
    'DeleteCard': 'DeleteCard',
    'RenameBoard': 'RenameBoard',
    'AddWorkspace': 'AddWorkspace',
    'DeleteWorkspace': 'DeleteWorkspace',
    'AddBoard2': 'AddBoard2'
};

function sendRequest(cFunction, type, content){
    const xHttp = new XMLHttpRequest();
    xHttp.onload = function () {cFunction(this)};
    xHttp.open('POST', 'http://localhost/Schema/common/ajaxResponse.php');
    xHttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xHttp.send("type="+type+"&content="+content);
}

/*
==================== cFunctions ==========================
*/

// ---------------------------------- change lists order ---------
function changeListOrder(xHttp){
    if (xHttp.status == 200 && xHttp.readyState == 4) {
        const response = JSON.parse(xHttp.responseText);
    }
}
// ---------------------------------- change boards order ---------
function changeCardOrder(xHttp){
    if (xHttp.status == 200 && xHttp.readyState == 4) {
        const response = JSON.parse(xHttp.responseText);
        document.querySelector('#card'.concat(response['cardID'])).querySelector('.inList').innerHTML = 'In list '.concat(response['listName']);
    }
}
// ---------------------------------- change list name ---------
function changeListName(xHttp){
    if (xHttp.status == 200 && xHttp.readyState == 4) {
        const response = JSON.parse(xHttp.responseText);
        if (response['status'] != "OK") {
            document.querySelector('#list'.concat(response['listID'])).firstElementChild.innerHTML = response['name'];
        }else
            console.log(response['error']);
    }
}
// ---------------------------------- change card name ---------
function changeCardName(xHttp){
    if (xHttp.status == 200 && xHttp.readyState == 4) {
        const response = JSON.parse(xHttp.responseText);
        if (response['status'] != "OK") {
            document.querySelector('#card'.concat(response['cardID'])).querySelector('.cardPanelTitle').innerHTML = response['name'];
        }else
            console.log(response['error']);
    }
}
// ---------------------------------- change card description ---------
function changeCardDesc(xHttp){
}
// ---------------------------------- add board response --------------
function addBoard(xHttp){
    if (xHttp.status == 200 && xHttp.readyState == 4) {
        console.log(xHttp.responseText);
        const response = JSON.parse(xHttp.responseText);
        if (response['status'] != 'OK') {
            document.querySelector('.addBoardErrors').innerHTML = response['error'];
        } else {
            window.location.href = ("http://localhost/Schema/pages/boards.php?id=" + response['boardId']);
        }
    }
}
// ---------------------------------- create new card --------------
function addCard(xHttp){
    if (xHttp.status == 200 && xHttp.readyState == 4) {
        const response = JSON.parse(xHttp.responseText);
        if (response['status'] == 'OK') {
            const list = document.querySelector(('#list'.concat(response['listID'])));
            const cardsContainer = list.querySelector('.cardsContainer');
            createCard(cardsContainer, response['name'], response['cardID'], response['listName']);
        }else
            console.log(response['error']);
    }
}
// ---------------------------------- create new list --------------
function addList(xHttp){
    console.log(xHttp.responseText);
    if (xHttp.status == 200 && xHttp.readyState == 4) {
        const response = JSON.parse(xHttp.responseText);
        if (response['status'] == 'OK') {
            createList(response['name'], response['listID']);
        }else
            console.log(response['error']);
    }
}
//---------------------------------- Delete board -----------------
function deleteBoard(xHttp){
    if (xHttp.status == 200 && xHttp.readyState == 4) {
        const response = JSON.parse(xHttp.responseText);
        if (response['status'] == 'OK') {
            document.querySelector('td#board'.concat(response['boardID'])).closest('tr').remove();
            if (response['isCurrent'] == 'YES')
                window.location.href = "http://localhost/Schema/pages/workspaces.php";
        }
        else
            console.log(response['error']);
    }
}
//---------------------------------- Delete list -----------------
let trashSound = new Audio('../images/bin.wav');
function deleteList(xHttp){
    if (xHttp.status == 200 && xHttp.readyState == 4) {
        const response = JSON.parse(xHttp.responseText);
        if (response['status'] == 'OK'){
            document.querySelector('div#list'.concat(response['listID'])).remove();
            // rearranging lists serial
            const lists = document.querySelectorAll('.listsContainer .list');
            const arr = {};
            for (let i = 1; i <= lists.length; i++){
                arr[lists[i-1].dataset.id] = i;
            }
            trashSound.play();
            sendRequest(changeListOrder, RequestType.ListOrder, JSON.stringify(arr));
            //
        }else
            console.log(response['error']);
    }
}
//---------------------------------- Delete board -----------------
function deleteCard(xHttp){
    if (xHttp.status == 200 && xHttp.readyState == 4) {
        const response = JSON.parse(xHttp.responseText);
        if (response['status'] == 'OK'){
            document.querySelector('div#card'.concat(response['cardID'])).remove();
        }else
            console.log(response['error']);
    }
}
//---------------------------------- Rename Board --------------------
function renameBoard(xHttp){
    if (xHttp.status == 200 && xHttp.readyState == 4) {
        const response = JSON.parse(xHttp.responseText);
        if (response['status'] == 'OK'){
            document.querySelector('td#board'.concat(response['boardID'])).firstElementChild.innerHTML = response['boardName'];
            if (response['isCurrent'] == 'YES'){
                document.querySelector('div.topBar .boardNameTopBar').innerHTML = response['boardName'];
            }
            document.querySelector('div.renameBoardGlass').style.display = 'none';
            const pane = document.querySelector('div.boardRenamePane');
            pane.style.display = 'none';
        }else
            console.log(response['error']);
    }
}
//---------------------------------- Add workspace ---------------------
function addWorkspace(xHttp){
    if (xHttp.status == 200 && xHttp.readyState == 4) {
        const response = JSON.parse(xHttp.responseText);
        if (response['status'] == 'OK'){
            window.location.href = 'http://localhost/Schema/pages/workspaces.php';
        }else
            console.log(response['error']);
    }
}
//---------------------------------- Delete workspace ------------------
function deleteWorkspace(xHttp){
    if (xHttp.status == 200 && xHttp.readyState == 4) {
        const response = JSON.parse(xHttp.responseText);
        if (response['status'] == 'OK'){

        }else
            console.log(response['error']);
    }
}
//---------------------------------- Add board 2 ---------------------
function addBoard2(xHttp){
    if (xHttp.status == 200 && xHttp.readyState == 4) {
        const response = JSON.parse(xHttp.responseText);
        if (response['status'] == 'OK'){
            window.location.href = 'http://localhost/Schema/pages/workspaces.php';
        }else
            console.log(response['error']);
    }
}