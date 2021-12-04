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
                if(event.value == 0) event.classList.add(classAlert);
                else event.classList.remove(classAlert);
            });

            await document.querySelectorAll(`${elemNameControlScope} select`).forEach(async (event) =>
            {
                /*class selectized for use selectize.js ต้องมี div คลุมอีกที*/
                if(event.value == 0){ 
                    if(event.classList.contains('selectized'))
                    event.parentNode.classList.add(classAlert);
                    else event.classList.add(classAlert);
                }
                else {
                    if(event.classList.contains('selectized'))
                    event.parentNode.classList.remove(classAlert);
                    else event.classList.remove(classAlert);
                }
            });

            await document.querySelectorAll(`${elemNameControlScope} textarea`).forEach(async (event) =>
            {
                if(event.value == 0) event.classList.add(classAlert);
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
async function asyncAddEventClearAlertInputNotEmptyMulti(elemNameControlScope,setEvent,removeClass)
{
    try
    {
        const control = elemNameControlScope != '' ? 
        document.querySelector(elemNameControlScope) : document;
        control.addEventListener(setEvent,async () => 
        {
            
            await document.querySelectorAll(`${elemNameControlScope} input`).forEach(async (event) =>
            {
                if(event.value != 0) event.classList.remove(removeClass);
            });

            await document.querySelectorAll(`${elemNameControlScope} select`).forEach(async (event) =>
            {
                /*class selectized for use selectize.js ต้องมี div คลุมอีกที*/
                if(event.value != 0)
                {
                    if(event.classList.contains('selectized'))
                    event.parentNode.classList.remove(removeClass);
                    else event.classList.remove(removeClass);
                }
            });

            await document.querySelectorAll(`${elemNameControlScope} textarea`).forEach(async (event) =>
            {
                if(event.value != 0) event.classList.remove(removeClass);
            });
        });
        
        return true;
    }
    catch(err)
    {
        alert(`Function asyncAddEventClearAlertInputNotEmptyMulti Error : ${err.message}`);
        return false;
    }
}

async function asyncfabricAddPutImg(elem,idRenderImg)
{
    try
    {
        const canvas = this.__canvas = await new fabric.Canvas(idRenderImg);
        const text = await new fabric.Rect();

        await fabric.Image.fromURL(URL.createObjectURL(elem.files[0]),async (img) => {
	            img.clipTo = async (ctx) => {
                await text.render(ctx);

            }
            await canvas.add(img);
            await canvas.renderAll();
        });
        return true;
    }
    catch(err)
    {
        alert(`Function asyncfabricAddPutImg Error : ${err.message}`);
        return false;
    }
}

//สำหรับเอาไปตั้งค่า Input lib IMask เป็นจำนวนเงินทีมีลูกนํ้าและทศนิยม
async function asyncIMaskSetOptionNumberAddComma(decimals = 0,minNum = 0,maxNum = 0)
{
    return {
        mask: Number,  // enable number mask
        
        // other options are optional with defaults below
        scale: decimals,  // digits after point, 0 for integers
        signed: false,  // disallow negative
        thousandsSeparator: ',',  // any single char
        padFractionalZeros: false,  // if true, then pads zeros at end to the length of scale
        normalizeZeros: true,  // appends or removes zeros at ends
        radix: '.',  // fractional delimiter
        mapToRadix: ['.'],  // symbols to process as radix

        // additional number interval options (e.g.)
        min: minNum,
        max: maxNum
    }
}

function addCommas(valNumber,valdecimals = null)
{
    try
    {
        const rgxNumberOnly = /^[0-9\.]*$/;
        const nStr = valdecimals == null ? new String(valNumber) : new String(valNumber.toFixed(valdecimals));
        
        if(nStr.search(rgxNumberOnly)) throw "ค่าพารามิเตอร์เลขจำนวนที่ได้รับต้องเป็นตัวเลขเท่านั้น ประเภท int หรือ float";

        const rgxUseAddCommas = /(\d+)(\d{3})/;
        const x = nStr.split('.');

        if(x.length > 2) throw "ค่าพารามิเตอร์ที่ได้รับไม่ตรงรูปแบบ";

        const number = new String(x[0]);
        const decimal = x.length > 1 ? new String(x[1]) : '';


        let createStr = number;
        while (rgxUseAddCommas.test(createStr)) {
            createStr = createStr.replace(rgxUseAddCommas, '$1' + ',' + '$2');
        }
        
        return createStr + (decimal != '' ? `.${decimal}` : '');
    }
    catch(err)
    {
        return `Function asyncAddCommas Error : ${err}`;
    }
}

async function asyncAddCommas(valNumber,valdecimals = null)
{
    try
    {
        const rgxNumberOnly = /^[0-9\.]*$/;
        const nStr = valdecimals == null ? new String(valNumber) : new String(valNumber.toFixed(valdecimals));
        
        if(nStr.search(rgxNumberOnly)) throw "ค่าพารามิเตอร์เลขจำนวนที่ได้รับต้องเป็นตัวเลขเท่านั้น ประเภท int หรือ float";

        const rgxUseAddCommas = /(\d+)(\d{3})/;
        const x = nStr.split('.');

        if(x.length > 2) throw "ค่าพารามิเตอร์ที่ได้รับไม่ตรงรูปแบบ";

        const number = new String(x[0]);
        const decimal = x.length > 1 ? new String(x[1]) : '';


        let createStr = number;
        while (rgxUseAddCommas.test(createStr)) {
            createStr = createStr.replace(rgxUseAddCommas, '$1' + ',' + '$2');
        }
        
        return createStr + (decimal != '' ? `.${decimal}` : '');
    }
    catch(err)
    {
        return `Function asyncAddCommas Error : ${err}`;
    }
}
