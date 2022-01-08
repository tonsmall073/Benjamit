<div class="container-fluid" id="formEditProduct">
    <div class="row form-group">
        <div class="col-sm-12">
            <label>ชื่อสินค้า</label>
        </div>
        <div class="col-sm-12">
            <input type="text" class="form-control" name="ProductName" value="" placeholder="ชื่อสินค้า"
                title="กรุณาป้อนข้อมูลชื่อสินค้าด้วยครับ">
        </div>
    </div>
    <div class="row form-group">
        <div class="col-sm-12">
            <label class="d-inline">เพิ่มศัพท์การเรียกตัวสินค้า</label>
            <button class="btn btn-sm btn-primary" onclick="addProductRelatedName();"><i class="bi bi-plus"></i> เพิ่มศัพท์สินค้า</button>
        </div>
    </div>
    <div class="col-sm-12 py-2" id="productRelate">

    </div>
    <div class="row form-group">
        <div class="col-sm-12">
            <label>รายละเอียดตัวสินค้า</label>
        </div>
        <div class="col-sm-12">
            <textarea type="text" class="form-control" name="DetailAboutProduct" 
            rows="5" placeholder="รายละเอียดตัวสินค้า ไม่อธิบายใส่เครื่องหมาย (-)" 
            title="กรุณาป้อนข้อมูลรายละเอียดตัวสินค้าด้วยครับ"></textarea>
        </div>
    </div>
    <div class="row form-group">
        <div class="col-sm-12">
            <label class="d-inline">รายละเอียดราคาสินค้า</label>
            <button class="btn btn-sm btn-primary" onclick="addProductPriceDetail();"><i class="bi bi-plus"></i>
                เพิ่มราคาสินค้า</button>
        </div>
    </div>
    <div class="col-sm-12 py-2" id="saleProductDetail">

    </div>
    <div class="row form-group" id="productImages">
        <div class="col-sm-12">
            <label class="d-inline">รูปภาพสินค้าสินค้า</label>
            <button class="btn btn-sm btn-primary" onclick="addProductImages();"><i class="bi bi-plus"></i>
                เพิ่มรูปภาพสินค้า</button>
        </div>
    </div>
    <div class="col-sm-12 py-2" id="productImages">
        
    </div>

</div>

<script>
var _idRowProRel = 0;
var _idRowPro = 0;
var _idRowImg = 0;

async function addProductRelatedName(val = '')
{
    try
    {
        const htmlStr = `
        <div class="row border-bottom-dark pb-2" name="RowProductRelatedName[]" id="rowProductRelated${_idRowProRel}">
            <div class="input-group col-sm-12">
                <div class="input-group-prepend">
                    <button class="btn btn-danger" type="button" name="delProductRelatedButton[]" 
                    onclick="delProductRelated(${_idRowProRel});">ลบ</button>
                </div>
                <input type="text" name="ProductRelatedName[]" class="form-control" value="${val}" 
                placeholder="ชื่อศัพท์การเรียกสินค้า" title="กรุณาคีย์ชื่อศัพท์การเรียกสินค้าด้วยครับ">
            </div>
        </div>`;
        await $('#productRelate').append(htmlStr);
        _idRowProRel++;
        return true;
    }
    catch(err)
    {
        alert(`Function addProductRelatedName Error : ${err.message}`);
        return false;
    }
}

async function delProductRelated(idRow)
{
    try
    {
        await $(`#rowProductRelated${idRow}`).remove();
        return true;
    }
    catch(err)
    {
        alert(`Function delProductRelated Error : ${err.message}`);
        return false;
    }
}

async function addProductPriceDetail(params = '') {
    try {
        const unitName = params != '' ? params.UnitName : '';
        const idUnitName = params != '' ? params.IdUnitName : 0;
        const costPrice = params != '' ? await asyncAddCommas(params.CostPrice,2) : '';
        const salePrice = params != '' ? await asyncAddCommas(params.SalePrice,2) : '';
        const idBarcode = params != '' ? params.IdBarcode : 'AutoIdBarcode';

        const htmlStr = `
        <div class="row border-bottom-dark pb-2" id="rowSaleDetail${_idRowPro}" name='RowSaleDetail[]'>
            <div class="input-group col-lg-3">
                <div class="input-group-prepend">
                    <button class="btn btn-danger" type="button" name="delProductPriceButton[]" 
                    onclick="delProductPriceDetail(${_idRowPro});">ลบ</button>
                </div>
                <select class="form-control" name="UnitType[]" id="unitType${_idRowPro}" 
                title="กรุณาเลือกหน่วยสินค้าด้วยครับ">
                    <option value=''>เลือกหน่วย</option>
                </select>
            </div>
            <div class="input-group col-lg-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">ราคาทุน</span>
                </div>
                <input type="text" class="form-control text-right" name="CostPrice[]" id="costPrice${_idRowPro}" value="" placeholder="ราคาทุน"
                    title="กรุณาป้อนราคาต้นทุนด้วยครับ">
            </div>
            <div class="input-group col-lg-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">ราคาขาย</span>
                </div>
                <input type="text" class="form-control text-right" name="SalePrice[]" id="salePrice${_idRowPro}" value="" placeholder="ราคาขาย"
                    title="กรุณาป้อนราคาขายด้วยครับ">
            </div>
            <div class="input-group col-lg-3">
                <input type="text" class="form-control" name="IdBarcode[]"  id="idBarcode${_idRowPro}" value="AutoIdBarcode" readonly 
                placeholder="คีย์ Id Barcode" title="กรุณาคีย์ข้อมูล Id Barcode ด้วยครับ">
                <div class="input-group-append">
                    <button class="btn btn-success" type="button" id="idBarcodeButton${_idRowPro}" 
                    onclick="switchAutoGenIdBarcode(${_idRowPro});">ON</button>
                </div>
            </div>
        </div>`;

        await $('#saleProductDetail').append(htmlStr);
        document.getElementById(`costPrice${_idRowPro}`).value = costPrice;
        await IMask(document.getElementById(`costPrice${_idRowPro}`),
        await asyncIMaskSetOptionNumberAddComma(2,0,1000000));

        document.getElementById(`salePrice${_idRowPro}`).value = salePrice;
        await IMask(document.getElementById(`salePrice${_idRowPro}`),
        await asyncIMaskSetOptionNumberAddComma(2,0,1000000));

        await getUnitTypeAllCreateHtmlOptions(`unitType${_idRowPro}`,idUnitName);

        const selectize = $(`#unitType${_idRowPro}`).selectize({
            create: false,
            sortField: "text",
        });
        
        if(idBarcode != 'AutoIdBarcode'){
            await switchAutoGenIdBarcode(_idRowPro);
            document.getElementById(`idBarcode${_idRowPro}`).value = idBarcode;
        }
            
        _idRowPro++;
        return true;
        
    } catch (err) {
        alert(`Function addProductPriceDetail Error : ${err.message}`);
        return false;
    }
}

async function getUnitTypeAllCreateHtmlOptions(idName,valSelected = null)
{
    try
    {
        res = await asyncSendPostApi(
            'Services/DatasAboutProduct/DatasAboutProduct.controller.php',
            {"Controller":'GetUnitType'});

        if(res.Status != 200)
        {
            alert(res.MessageDesc);
            return false;
        }

        await res.Datas.forEach(async (datas) => {
            
            const elemOption = document.createElement("OPTION");
            elemOption.value = datas.Id;
            elemOption.innerText = datas.Name;  
            if(valSelected == datas.Id) elemOption.setAttribute('selected',true);     
            await document.getElementById(idName).appendChild(elemOption);     
        });
        
        return true;
    }
    catch(err)
    {
        alert(`Function getUnitTypeAll Error : ${err.message}`);
        
        return false;
    }
}

async function addProductImages(val = null)
{
    try
    {
        const srcImg = val != null ? `Assets/Images/Products/${val}` : '';
        const htmlStr = `
            <div class='row border-bottom-dark pb-2' id='rowProductImg${_idRowImg}'>
                <div class="col-lg-12 px-auto" name="numberImgProduct[]">

                </div>
                <div class="input-group col-lg-12 pb-2">
                    <div class="input-group-prepend">
                        <button class="btn btn-danger" onclick="delProductImgDetail(${_idRowImg});">ลบ</button>
                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="UploadImgProduct[]" 
                        id="uploadImgProduct${_idRowImg}" onchange="putProductImgToCanvas(this,'dataProductImg${_idRowImg}','uploadTextImg${_idRowImg}');"
                        title="กรุณาเลือกรูปภาพที่จะอัพโหลดด้วยครับ" >
                        <label class="custom-file-label" id="uploadTextImg${_idRowImg}" for="uploadImgProduct${_idRowImg}">Choose file</label>
                    </div>
                </div>
                <div class='col-lg-12' id='colProductImg${_idRowImg}'>
                    <canvas id="dataProductImg${_idRowImg}" class="fabric-canvas" 
                    width="700px;" height="700px;" name='DataProductImg[]'></canvas>
                </div>
            </div>
        `;

        await $('#productImages').append(htmlStr);
        if(srcImg != '') {
            const dataImgHtml = `<img id='dataProductImg${_idRowImg}' src='${srcImg}' >`;
            const slashSplit = srcImg.split('/');
            const positionFocus = parseInt(slashSplit.length) - parseInt(1);
            const getFileName = slashSplit[positionFocus];
            await $(`#colProductImg${_idRowImg}`).html(dataImgHtml);
            await $(`#uploadImgProduct${_idRowImg}`).attr('disabled',true);
            await $(`#uploadTextImg${_idRowImg}`).text(getFileName);
        }
        _idRowImg++;
        await createConcatTextNumberMulti('numberImgProduct[]','รูปภาพที่');
        return true;
    }
    catch(err)
    {
        alert(`Function addProductImages Error : ${err.message}`);
        return false;
    }
}

async function createConcatTextNumberMulti(elemName,firstText = '')
{
    try
    {
        await document.getElementsByName(elemName).forEach(async (elem,key) => {
            elem.innerHTML = `<b>${firstText} ${key+1}</b>`;
        });
        return true;
    }
    catch(err)
    {
        alert(`Function createConcatTextNumberMulti Error : ${err.message}`);
        return false;
    }
}

async function delProductImgDetail(idRow) {
    try {
        await $(`#rowProductImg${idRow}`).remove();
        await createConcatTextNumberMulti('numberImgProduct[]','รูปภาพที่');
        return true;
    } catch (err) {
        alert(`Function delProductImgDetail Error : ${err.message}`);
        return false;
    }
}

async function putProductImgToCanvas(elem,idTarget,idLabel)
{
    try
    {
        if(elem.files.name == false) return false;
        document.getElementById(idLabel).innerText = elem.files[0].name;
        await asyncfabricAddPutImg(elem,idTarget);
        elem.disabled = true;
        return true;
    }
    catch(err)
    {
        alert(`Function asyncfabricAddPutImg Error : ${err.message}`);
        return false;
    }

}

async function delProductPriceDetail(idRow) {
    try {
        $(`#rowSaleDetail${idRow}`).remove();
        if(document.getElementsByName('RowSaleDetail[]').length == 0) await addProductPriceDetail();
        return true;
    } catch (err) {
        alert(`Function delProductPriceDetail Error : ${err.message}`);
        return false;
    }
}

async function switchAutoGenIdBarcode(idRow) {
    try {
        if ($(`#idBarcodeButton${idRow}`).hasClass('btn-success')) {
            $(`#idBarcodeButton${idRow}`).removeClass('btn-success');
            $(`#idBarcodeButton${idRow}`).addClass('btn-secondary');
            $(`#idBarcodeButton${idRow}`).html('OFF');
            $(`#idBarcode${idRow}`).attr('readonly',false);
            $(`#idBarcode${idRow}`).val('');
        } else {
            $(`#idBarcodeButton${idRow}`).removeClass('btn-secondary');
            $(`#idBarcodeButton${idRow}`).addClass('btn-success');
            $(`#idBarcodeButton${idRow}`).html('ON');
            $(`#idBarcode${idRow}`).attr('readonly',true);
            $(`#idBarcode${idRow}`).val('AutoIdBarcode');
        }
        $(`#idBarcode${idRow}`).removeClass('is-invalid');
        return true;
    } catch (err) {
        alert(`Function switchAutoGenIdBarcode Error : ${err.message}`);
        return false;
    }
}

async function getProductDetail(response)
{
    try
    {
        const res = response;
        document.getElementsByName('ProductName')[0].value = res.Content.ProductName;

        for(datas of res.Content.RelatedProducts)
        {
            await addProductRelatedName(datas.Name);
        }

        const detailAboutProduct =  res.Content.DetailAboutProduct != '' ? res.Content.DetailAboutProduct : '-';
        document.getElementsByName('DetailAboutProduct')[0].value = detailAboutProduct;

        for(let datas of res.Content.Prices)
        {
            await addProductPriceDetail(datas);
        }

        for(let datas of res.Content.Images)
        {
            await addProductImages(datas.FileName);
        }

        return true;
    }
    catch(err)
    {
        alert(`Function getProductDetail Error : ${err.message}`);
        return false;
    }
}

</script>
