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

async function asyncAddPressActionClick(attrFocus,keyNum,attrControlScope = '')
{
    try
    {
        const control = attrControlScope != '' ? 
        document.querySelector(attrControlScope) : document;
        await control.addEventListener('keypress',async (event) => 
        {
            if(event.keyCode === keyNum)
            {
                const focus = `${attrControlScope} ${attrFocus}`;
                await event.preventDefault();
                await document.querySelector(focus).click();
            }
        });
        
        return true;
    }
    catch(err)
    {
        alert(`Function asyncAddPressActionClick Error ${err.message}`);
        return false;
    }
}