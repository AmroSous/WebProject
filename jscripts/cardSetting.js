// =============================== positioning cards setting =======================

document.querySelectorAll('span.rightEntityEdit').forEach(button => {
    button.addEventListener('mouseover', e=>{
        const setting = button.closest('.card').querySelector('.cardSetting');
        setting.style.visibility = 'visible';
        setting.style.width = '50px';
    })

    button.addEventListener('mouseout', e=>{
        const setting = button.closest('.card').querySelector('.cardSetting');
        setting.style.width = '0';
        setting.style.visibility = 'hidden';
    })
})

document.querySelectorAll('div.cardSettingOpen').forEach(button => {
    button.addEventListener('click', e=>{
        e.stopPropagation();
        document.querySelector('.cardPanelGlass').style.display = 'block';
        button.closest('.card').querySelector('.cardPanel').style.display = 'block';
        button.closest('.card').querySelector('.cardPanel').classList.add('displayed');
    })
})

document.querySelectorAll('div.cardSettingDelete').forEach(button => {
    button.addEventListener('click', e=>{
        e.stopPropagation();
        const card = button.closest('.card');
        if (confirm("Are you sure you want to delete ".concat(card.querySelector('.cardNameInput').innerHTML).concat(" card ?"))){
            const arr = {};
            arr['cardID'] = card.dataset.id;
            sendRequest(deleteCard, RequestType.DeleteCard, JSON.stringify(arr));
        }
    })
})