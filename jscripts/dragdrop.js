const cards = document.querySelectorAll('.draggableCard');
const containers = document.querySelectorAll('.cardsContainer');
const lists = document.querySelectorAll('.list');
const board = document.querySelector('.listsContainer');
let offsetX = 0;
let offsetY = 0;

cards.forEach(card => {
    card.addEventListener('dragstart', event => {

        event.stopPropagation();

        card.classList.add('dragging');
        setTimeout(()=>{card.className += ' invisible'}, 0);
    });

    card.addEventListener('dragend', event => {

        event.stopPropagation();

        card.classList.remove('dragging');
        card.classList.remove('invisible');

        // save new serial numbers for all cards in the list affected
        const list = card.closest('.list');            // destination list
        const cards = list.querySelectorAll('.card');  // cards in destination list
        const arr = {};
        const card_serial = {};
        for (let i = 1; i <= cards.length; i++){
            card_serial[cards[i-1].dataset.id] = i;
        }
        arr['card_serial'] = card_serial;                      // card-serial array in destination list with new card
        arr['listID'] = list.dataset.id;                       // destination list id
        arr['cardID'] = card.dataset.id;                       // dragged card id
        if (card_serial[arr['cardID']] == 1){
            arr['prevSibling'] = -1;
        }else {
            arr['prevSibling'] = card.previousElementSibling.dataset.id;    // previous card of dragged card in destination
        }
        sendRequest(changeCardOrder, RequestType.CardOrder, JSON.stringify(arr));
        //

    });

});

containers.forEach(container => {
    container.addEventListener('dragover', e => {
        e.preventDefault()
        const afterElement = getDragAfterElement(container, e.clientY)
        const draggable = document.querySelector('.dragging')
        if (afterElement == null) {
            container.appendChild(draggable)
        } else {
            container.insertBefore(draggable, afterElement)
        }
    })
})

function getDragAfterElement(container, y) {
    const draggableElements = [...container.querySelectorAll('.draggableCard:not(.dragging)')]

    return draggableElements.reduce((closest, child) => {
        const box = child.getBoundingClientRect()
        const offset = y - box.top - box.height / 2
        if (offset < 0 && offset > closest.offset) {
            return { offset: offset, element: child }
        } else {
            return closest
        }
    }, { offset: Number.NEGATIVE_INFINITY }).element
}


lists.forEach(list => {
    list.addEventListener('dragstart', () => {
        list.classList.add('draggingList');
        setTimeout(()=>{list.className += ' invisible'}, 0);

        // show trash
        const trash = document.querySelector('.binContainer');
        trash.style.rotate = "-35deg";
        trash.style.translate = "-135px";
        //
    });

    list.addEventListener('dragend', e => {

        const trash_coor = {};
        const rect = document.querySelector('.binContainer').getBoundingClientRect();
        trash_coor['top'] = rect.top;
        trash_coor['bottom'] = rect.bottom;
        trash_coor['left'] = rect.left;
        trash_coor['right'] = rect.right;

        if (e.clientX > trash_coor['left'] && e.clientX < trash_coor['right'] && e.clientY > trash_coor['top'] && e.clientY < trash_coor['bottom']){
            // remove list ---- AJAX
            const arr = {};
            arr['listID'] = list.dataset.id;
            sendRequest(deleteList, RequestType.DeleteList, JSON.stringify(arr));
        }
        else{
            list.classList.remove('draggingList');
            list.classList.remove('invisible');

            // save new serial numbers for all lists
            const lists = document.querySelectorAll('.listsContainer .list');
            const arr = {};
            for (let i = 1; i <= lists.length; i++){
                arr[lists[i-1].dataset.id] = i;
            }
            sendRequest(changeListOrder, RequestType.ListOrder, JSON.stringify(arr));
            //
        }


        // hide trash
        const trash = document.querySelector('.binContainer');
        trash.style.rotate = "35deg";
        trash.style.translate = "135px";

    });

});

board.addEventListener('dragover', e => {
    e.preventDefault()
    const draggable = document.querySelector('.draggingList')
    if (draggable != null) {
        const afterElement = getDragAfterListElement(board, e.clientX)
        if (afterElement == null) {
            board.appendChild(draggable)
        } else {
            board.insertBefore(draggable, afterElement)
        }
    }
})

function getDragAfterListElement(container, x) {
    const draggableElements = [...container.querySelectorAll('.draggableList:not(.draggingList)')]

    return draggableElements.reduce((closest, child) => {
        const box = child.getBoundingClientRect()
        const offset = x - box.left - box.width / 2
        if (offset < 0 && offset > closest.offset) {
            return { offset: offset, element: child }
        } else {
            return closest
        }
    }, { offset: Number.NEGATIVE_INFINITY }).element
}

// =================== trash listeners ======================================


const trash = document.querySelector('div.binContainer');
const trashImage = document.querySelector('div.binImage');

trash.addEventListener('dragover', e=>{
    trashImage.style.backgroundImage = "url('../images/openBin.png')";
})

trash.addEventListener('dragleave', e=>{
    trashImage.style.backgroundImage = "url('../images/closedBin.png')";
})
