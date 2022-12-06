
// open add card form
document.querySelectorAll('table.addAnotherCardTable').forEach(table => {
    table.addEventListener('click', e => {
        table.style.display = 'none';
        table.nextElementSibling.style.display = 'block';
        table.nextElementSibling.firstElementChild.focus();
    })
});

// close add card form
document.querySelectorAll('img.closeCreateCard').forEach(image => {
    image.addEventListener('click', e => {
        const ele = image.parentElement.parentElement.parentElement.parentElement.parentElement;
        ele.style.display = 'none';
        ele.previousElementSibling.style.display = 'block';
    })
});

// open add list form
const addListButton = document.querySelector('table.addAnotherListTable');
addListButton.addEventListener('click', e => {
    console.log('entered');
    addListButton.style.display = 'none';
    addListButton.nextElementSibling.style.display = 'block';
    addListButton.nextElementSibling.firstElementChild.focus();
});

// close add list form
const  closeAddListButton = document.querySelector('img#closeCreateList');
closeAddListButton.addEventListener('click', e => {
    const ele = closeAddListButton.parentElement.parentElement.parentElement.parentElement.parentElement;
    ele.style.display = 'none';
    ele.previousElementSibling.style.display = 'block';
})
