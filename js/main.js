function sendApi(type,url,req,resType)
{
    return $.ajax(
        {
            "url" : url,
            "type" : type,
            "dataType" : resType,
            "data" : req,
            "success" : (res) => {
                return res;
            },
            "error" : (err) => {
                alert(`Function sendApi Error : ${err}`);
                return false;
            }
        }
    );
}

async function asyncSendApi(url,type,req,resType)
{
    return await $.ajax(
        {
            "url" : url,
            "type" : type,
            "dataType" : resType,
            "data" : req,
            "success" : async (res) => {
                return await res;
            },
            "error" : async (err) => {
                alert(`Function sendApi Error : ${err}`);
                return false;
            }
        }
    );
}

async function asyncSendPostApi(url,req)
{
    return await $.ajax(
        {
            "url" : url,
            "type" : 'POST',
            "dataType" : 'JSON',
            "data" : req,
            "success" : async (res) => {
                return await res;
            },
            "error" : async (err) => {
                alert(`Function sendApi Error : ${err}`);
                return false;
            }
        }
    );
}

function loadView(path)
{
    $('#content').load(path);
}

async function asyncLoadView(path)
{
    await $('#content').load(path);
    return true;
}

async function asyncAddPressActionClickMulti(elemNameFocus,keyNum,elemNameControlScope = '')
{
    try
    {
        const control = elemNameControlScope != '' ? 
        document.querySelector(elemNameControlScope) : document;
        control.addEventListener('keypress',async (event) => 
        {
            if(event.keyCode === keyNum)
            {
                const focus = `${elemNameControlScope} ${elemNameFocus}`;
                await event.preventDefault();
                await document.querySelector(focus).click();
            }
        });
        
        return true;
    }
    catch(err)
    {
        alert(`Function asyncAddPressActionClickMulti Error ${err.message}`);
        return false;
    }
}

//เช็คทั้งหมดเมื่อทำการ Click ให้เอา Class Alert Input เข้ามา กรณีเป็นค่่าว่าง
async function asyncAddClickAlertInputEmptyMulti(elemFucus,elemNameControlScope,classAlert)
{
    try
    {
        const control = elemNameControlScope != '' ? 
        document.querySelector(`${elemNameControlScope} ${elemFucus}`) : document;

        control.addEventListener('click',async () => 
        {
            await document.querySelectorAll(`${elemNameControlScope} input`).forEach(async (event) =>
            {
                if(event.value == '') event.classList.add(classAlert);
                else event.classList.remove(classAlert);
            });

            await document.querySelectorAll(`${elemNameControlScope} select`).forEach(async (event) =>
            {
                if(event.value == '') event.classList.add(classAlert);
                else event.classList.remove(classAlert);
            });

            await document.querySelectorAll(`${elemNameControlScope} textarea`).forEach(async (event) =>
            {
                if(event.value == '') event.classList.add(classAlert);
                else event.classList.remove(classAlert);
            });
        });
    return true;
    }
    catch(err)
    {
        alert(`Function asyncAddClickAlertInputEmptyMulti Error : ${err.message}`);
        return false;
    }
}

//เช็คทั้งหมดเมื่อเกิด Event เช็คไม่ใช่ค่าว่าง ให้เอา Class Alert Input ออกไป
async function asyncAddEventClearClassInputNotEmptyMulti(elemNameControlScope,setEvent,removeClass)
{
    try
    {
        const control = elemNameControlScope != '' ? 
        document.querySelector(elemNameControlScope) : document;
        control.addEventListener(setEvent,async () => 
        {
            
            await document.querySelectorAll(`${elemNameControlScope} input`).forEach(async (event) =>
            {
                if(event.value != '') event.classList.remove(removeClass);
            });

            await document.querySelectorAll(`${elemNameControlScope} select`).forEach(async (event) =>
            {
                if(event.value != '') event.classList.remove(removeClass);
            });

            await document.querySelectorAll(`${elemNameControlScope} textarea`).forEach(async (event) =>
            {
                if(event.value != '') event.classList.remove(removeClass);
            });
        });
        
        return true;
    }
    catch(err)
    {
        alert(`Function asyncAddEventClearClassInputNotEmptyMulti Error : ${err.message}`);
        return false;
    }
}
