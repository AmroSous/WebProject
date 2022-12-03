

function showPopup(button, elementClass){
    const element = document.querySelector('.'.concat(elementClass));
    if (element.style.height != '170px') {
        element.style.height = '170px';
        element.classList.add('selectedPopup');
        button.classList.add('selectedPopupParent');
    }else{
        element.style.height = '0';
        element.classList.remove('selectedPopup');
        button.classList.remove('selectedPopupParent');
    }
}

