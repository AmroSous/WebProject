
// open add card form
document.querySelectorAll('table.addAnotherCardTable').forEach(table => {
    openAddCard(table);
});
function openAddCard(table){
    table.addEventListener('click', () => {
        table.style.display = 'none';
        table.nextElementSibling.style.display = 'block';
        table.nextElementSibling.firstElementChild.focus();
    })
}

// close add card form
document.querySelectorAll('img.closeCreateCard').forEach(image => {
    closeAddCard(image);
});
function closeAddCard(image){
    image.addEventListener('click', () => {
        const ele = image.parentElement.parentElement.parentElement.parentElement.parentElement;
        ele.style.display = 'none';
        ele.previousElementSibling.style.display = 'block';
    })
}
//==============================================================================

// open add list form
const addListButton = document.querySelector('table.addAnotherListTable');
addListButton.addEventListener('click', () => {
    addListButton.style.display = 'none';
    addListButton.nextElementSibling.style.display = 'block';
    addListButton.nextElementSibling.firstElementChild.focus();
});

// close add list form
const  closeAddListButton = document.querySelector('img#closeCreateList');
closeAddListButton.addEventListener('click', () => {
    const ele = closeAddListButton.parentElement.parentElement.parentElement.parentElement.parentElement;
    ele.style.display = 'none';
    ele.previousElementSibling.style.display = 'block';
})

// display edit entity when hover card
document.querySelectorAll('.card').forEach(card => {
    card.addEventListener('mouseover', () => {
        card.children[1].lastElementChild.style.display = 'block';
    })
})

// hide edit entity when not hover card
document.querySelectorAll('.card').forEach(card => {
    card.addEventListener('mouseout', () => {
        card.children[1].lastElementChild.style.display = 'none';
    })
})

// prevent line break in contentEditable
document.querySelectorAll('.editable').forEach(thing => {
    thing.addEventListener('keypress', evt => {
        if (evt.which === 13) {
            evt.preventDefault();
        }
    })
})

// open card panel
document.querySelectorAll('.card').forEach(card => {
    card.addEventListener('click', () => {
        document.querySelector('.cardPanelGlass').style.display = 'block';
        card.lastElementChild.style.display = 'block';
        card.lastElementChild.classList.add('displayed');
    })
})

// close card panel
document.querySelector('.cardPanelGlass').addEventListener('click', closeCard);
document.querySelectorAll('.closeCardPanel').forEach(close => {
    close.addEventListener('click', e => {
        e.stopPropagation();
        closeCard();
    });
});
function closeCard(){
    document.querySelector('.displayed').style.display = 'none';
    document.querySelector('.cardPanelGlass').style.display = 'none';
    const card = document.querySelector('.displayed').parentElement;
    card.querySelector('.cardNameInput').innerHTML = card.querySelector('.cardPanelTitle').innerHTML;
    document.querySelector('.displayed').classList.remove('displayed');
}

// list name change
document.querySelectorAll('.listName').forEach(field => {
    field.addEventListener('focusout', ()=>{
        const arr = {};
        arr['listID'] = field.parentElement.dataset.id;
        arr['name'] = field.innerText;
        sendRequest(changeListName, RequestType.ListName, JSON.stringify(arr));
    })
})

// change card name listener
document.querySelectorAll('.cardPanelTitle').forEach(field => {
    field.addEventListener('focusout', ()=>{
        const arr = {};
        arr['cardID'] = field.parentElement.parentElement.parentElement.dataset.id;
        arr['name'] = field.innerText;
        sendRequest(changeCardName, RequestType.CardName, JSON.stringify(arr));
    })
})

// show save button when changing description text
document.querySelectorAll('.cardDescription').forEach(desc => {
    desc.addEventListener('input', ()=>{
        desc.previousElementSibling.children[0].style.display = 'inline-block';
    })
})

// save description of card --- ajax
document.querySelectorAll('.saveDesc').forEach(button=>{
    button.addEventListener('click', ()=>{
        button.style.display = 'none';
        const arr = {};
        arr['cardID'] = button.parentElement.parentElement.parentElement.parentElement.dataset.id;
        arr['desc'] = button.parentElement.nextElementSibling.innerText;
        sendRequest(changeCardDesc, RequestType.CardDesc, JSON.stringify(arr));
    })
})

// create card button pressed
document.querySelectorAll('button.createCard').forEach(button=>{
    button.addEventListener('click', ()=>{
        const container = button.closest('.list').querySelector('.cardsContainer');
        const numOfCards = container.childElementCount;
        const arr = {};
        arr['listID'] = button.closest('.list').dataset.id;
        arr['name'] = button.closest('.addCardForm').querySelector('.cardName').value;
        arr['position'] = numOfCards + 1;
        sendRequest(addCard, RequestType.AddCard, JSON.stringify(arr));
    })
})

// create list button pressed
document.querySelector('#createList').addEventListener('click', e=>{
    const numOfLists = document.querySelector('.listsContainer').childElementCount;
    const arr = {};
    arr['name'] = document.querySelector('.addList').querySelector('#listName').value;
    arr['position'] = numOfLists + 1;
    sendRequest(addList, RequestType.AddList, JSON.stringify(arr));
})

//  ------------------------------------------------  create new card function --------------------------

function createCard(cardsContainer, cardName, cardID){
    //clone existing card
    const card = cardsContainer.firstElementChild.cloneNode(true);

    // ===================================== change attributes and innerHTML =============

    card.dataset.id = cardID;
    card.id = 'card'.concat(cardID);
    card.querySelector('.cardNameInput').innerHTML = cardName;
    card.querySelector('.cardPanelTitle').innerHTML = cardName;
    card.querySelector('.cardDescription').innerHTML = '';

    //===================================== add event listeners ===========================

    card.addEventListener('dragstart', event => {
        event.stopPropagation();
        card.classList.add('dragging');
        setTimeout(()=>{card.className += ' invisible'}, 0);
    });
    card.addEventListener('dragend', event => {
        event.stopPropagation();
        card.classList.remove('dragging');
        card.classList.remove('invisible');
    });
    card.addEventListener('mouseover', () => {
        card.children[1].lastElementChild.style.display = 'block';
    });
    card.addEventListener('mouseout', () => {
        card.children[1].lastElementChild.style.display = 'none';
    });
    card.querySelector('.cardPanelTitle').addEventListener('keypress', evt => {
        if (evt.which === 13) {
            evt.preventDefault();
        }
    })
    card.addEventListener('click', () => {
        document.querySelector('.cardPanelGlass').style.display = 'block';
        card.lastElementChild.style.display = 'block';
        card.lastElementChild.classList.add('displayed');
    })
    card.querySelector('.closeCardPanel').addEventListener('click', e => {
            e.stopPropagation();
            closeCard();
    });
    const e = card.querySelector('.cardPanelTitle');
    e.addEventListener('focusout', ()=>{
        const arr = {};
        arr['cardID'] = e.parentElement.parentElement.parentElement.dataset.id;
        arr['name'] = e.innerText;
        sendRequest(changeCardName, RequestType.CardName, JSON.stringify(arr));
    });
    card.querySelector('.cardDescription').addEventListener('input', ()=>{
        card.querySelector('.saveDesc').style.display = 'inline-block';
    });
    card.querySelector('.saveDesc').addEventListener('click', ()=>{
        card.querySelector('.saveDesc').style.display = 'none';
        const arr = {};
        arr['cardID'] = card.dataset.id;
        arr['desc'] = card.querySelector('.cardDescription').innerText;
        sendRequest(changeCardDesc, RequestType.CardDesc, JSON.stringify(arr));
    });
    // ============================================ append new card =====================
    cardsContainer.appendChild(card);
}

// --------------------------------- create new list function -----------------------------------------------------------------

function createList(listName, listID){

    // ======================================= clone copy of list and change contents ====

    const cloneSource = document.querySelector('.listsContainer').firstElementChild;
    const list = cloneSource.cloneNode();
    list.dataset.id = listID;
    list.id = 'list'.concat(listID);
    const part1 = cloneSource.querySelector('.listName').cloneNode(true);
    part1.innerHTML = listName;
    const part2 = cloneSource.querySelector('.cardsContainer').cloneNode();
    const part3 = cloneSource.querySelector('.addCard').cloneNode(true);
    list.appendChild(part1);
    list.appendChild(part2);
    list.appendChild(part3);

    // ========================================= add listeners =====================

    openAddCard(list.querySelector('table.addAnotherCardTable'));
    closeAddCard(list.querySelector('img.closeCreateCard'));
    list.addEventListener('dragstart', () => {
        list.classList.add('draggingList');
        setTimeout(()=>{list.className += ' invisible'}, 0);
    });
    const ee = list.querySelector('.cardsContainer');
    ee.addEventListener('dragover', e => {
        e.preventDefault()
        const afterElement = getDragAfterElement(ee, e.clientY)
        const draggable = document.querySelector('.dragging')
        if (afterElement == null) {
            ee.appendChild(draggable)
        } else {
            ee.insertBefore(draggable, afterElement)
        }
    })
    list.addEventListener('dragend', () => {
        list.classList.remove('draggingList');
        list.classList.remove('invisible');
    });
    list.querySelector('.editable').addEventListener('keypress', evt => {
        if (evt.which === 13) {
            evt.preventDefault();
        }
    })
    list.querySelector('.listName').addEventListener('focusout', ()=>{
        const arr = {};
        arr['listID'] = list.dataset.id;
        arr['name'] = list.querySelector('.listName').innerText;
        sendRequest(changeListName, RequestType.ListName, JSON.stringify(arr));
    })
    list.querySelector('button.createCard').addEventListener('click', ()=>{
        const container = list.querySelector('.cardsContainer');
        const numOfCards = container.childElementCount;
        const arr = {};
        arr['listID'] = list.dataset.id;
        arr['name'] = list.querySelector('input.cardName').value;
        arr['position'] = numOfCards + 1;
        sendRequest(addCard, RequestType.AddCard, JSON.stringify(arr));
    })

    // ========================================= append new list =================

    document.querySelector('.listsContainer').appendChild(list);
}