const cards = document.querySelectorAll('.draggableCard');
const containers = document.querySelectorAll('.cardsContainer');
const lists = document.querySelectorAll('.list');
const board = document.querySelector('.listsContainer');

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
    });

    list.addEventListener('dragend', () => {
        list.classList.remove('draggingList');
        list.classList.remove('invisible');
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
