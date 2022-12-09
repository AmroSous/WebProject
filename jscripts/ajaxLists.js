const RequestType = {
    'ListOrder': 'ListOrder',
    'CardOrder': 'CardOrder',
    'ListName': 'ListName',
    'CardName': 'CardName',
    'CardDesc': 'CardDesc',
    'AddBoard': 'AddBoard',
    'AddCard': 'AddCard',
    'AddList': 'AddList'
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

// ---------------------------------- change lists or boards order ---------
function changeOrder(xHttp){
}
// ---------------------------------- change list name ---------
function changeListName(xHttp){
    if (xHttp.status == 200 && xHttp.readyState == 4) {
        const response = JSON.parse(xHttp.responseText);
        if (response['status'] != "OK") {
            document.querySelector('#list'.concat(response['listID'])).firstElementChild.innerHTML = response['name'];
        }
    }
}
// ---------------------------------- change card name ---------
function changeCardName(xHttp){
    if (xHttp.status == 200 && xHttp.readyState == 4) {
        const response = JSON.parse(xHttp.responseText);
        if (response['status'] != "OK") {
            document.querySelector('#card'.concat(response['cardID'])).querySelector('.cardPanelTitle').innerHTML = response['name'];
        }
    }
}
// ---------------------------------- change card description ---------
function changeCardDesc(xHttp){
}
// ---------------------------------- add board response --------------
function addBoard(xHttp){
    if (xHttp.status == 200 && xHttp.readyState == 4) {
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
            createCard(cardsContainer, response['name'], response['cardID']);
        }
    }
}
// ---------------------------------- create new list --------------
function addList(xHttp){
    console.log(xHttp.responseText);
    if (xHttp.status == 200 && xHttp.readyState == 4) {
        const response = JSON.parse(xHttp.responseText);
        if (response['status'] == 'OK') {
            createList(response['name'], response['listID']);
        }
    }
}
