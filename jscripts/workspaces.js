
document.querySelectorAll('.wsSettingEllipse').forEach(setting => {
    setting.addEventListener('mouseover', ()=>{
        setting.closest('.workspaces').querySelector('.wsSetting').style.width = 'fit-content';
    })

    setting.addEventListener('mouseout', ()=>{
        setting.closest('.workspaces').querySelector('.wsSetting').style.width = '0';
    })
})

document.querySelectorAll('.wsSetting').forEach(setting => {
    setting.addEventListener('mouseover', ()=>{
        setting.style.width = 'fit-content';
    })

    setting.addEventListener('mouseout', ()=>{
        setting.style.width = '0';
    })
})

document.querySelectorAll('.wsSettingAdd').forEach(setting => {
    setting.addEventListener('click', e => {
        e.stopPropagation();
        document.querySelector('.glassLayer').style.display = 'block';
        document.querySelector('.addBoardPlane').style.display = 'block';
        document.querySelector('.addBoardPlane').dataset.id = setting.closest('.workspaces').dataset.id;
        document.querySelector('.addBoardPlane #boardNameInput').focus();
    })
})

document.querySelector('.glassLayer').addEventListener('click', ()=>{
    document.querySelector('.glassLayer').style.display = 'none';
    document.querySelector('.addBoardPlane').style.display = 'none';
})

document.querySelector('#submitCreateCard').addEventListener('click', ()=>{
    const arr = {};
    arr['boardName'] = document.querySelector('#boardNameInput').value;
    arr['template'] = document.querySelector('select#boardTemplate').value;
    arr['wsID'] = document.querySelector('.addBoardPlane').dataset.id;
    sendRequest(addBoard2, RequestType.AddBoard2, JSON.stringify(arr));
})

document.querySelector('#addWsButton').addEventListener('click', ()=>{
    const name = document.querySelector('#addWsInput').value;
    const arr = {};
    arr['wsName'] = name;
    sendRequest(addWorkspace, RequestType.AddWorkspace, JSON.stringify(arr));
})
