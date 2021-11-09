<div class="container-fluid" id="formAddProduct">
    <div class="row form-group">
        <div class="col-sm-12">
            <label>ชื่อสินค้า</label>
        </div>
        <div class="col-sm-12">

            <input type="text" class="form-control" name="ProductName" value="" placeholder="ชื่อสินค้า"
                title="กรุณาป้อนข้อมูลชื่อสินค้าด้วยครับ">
        </div>
    </div>
    <div class="row form-group" id="saleProductDetail">
        <div class="col-sm-12">
            <label class="d-inline">รายละเอียดราคาสินค้า</label>
            <button class="btn btn-sm btn-primary" onclick="addProductPriceDetail();"><i class="bi bi-plus"></i>
                เพิ่มราคาสินค้า</button>
        </div>
    </div>
    <div class="row form-group" id="productImages">
        <div class="col-sm-12">
            <label class="d-inline">รูปภาพสินค้าสินค้า</label>
            <button class="btn btn-sm btn-primary" onclick="addProductImages();"><i class="bi bi-plus"></i>
                เพิ่มรูปภาพสินค้า</button>
        </div>
    </div>
</div>

<script>
var _idRowPro = 0;
var _idRowImg = 0;
async function addProductPriceDetail() {
    try {
        const htmlStr = `
        <div class="row border-bottom-dark py-2" id="rowSaleDetail${_idRowPro}" name='RowSaleDetail[]'>
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
        await IMask(document.getElementById(`costPrice${_idRowPro}`),
        await asyncIMaskSetOptionNumberAddComma(2,0,1000000));
        await IMask(document.getElementById(`salePrice${_idRowPro}`),
        await asyncIMaskSetOptionNumberAddComma(2,0,1000000));
        await getUnitTypeAllCreateHtmlOptions(`unitType${_idRowPro}`);
        // await $(`#unitType${_idRowPro}`).selectpicker();
        $(`#unitType${_idRowPro}`).selectize({
  create: false,
  sortField: "text",
});
            
        _idRowPro++;
        return true;
        
    } catch (err) {
        alert(`Function addProductPriceDetail Error : ${err.message}`);
        return false;
    }
}

async function getUnitTypeAllCreateHtmlOptions(idName)
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

async function addProductImages()
{
    try
    {
        const htmlStr = `
            <div class='row border-bottom-dark py-2' id='rowProductImg${_idRowImg}'>
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
                <div class='col-lg-12'>
                    <canvas id="dataProductImg${_idRowImg}" class="fabric-canvas" 
                    width="700px;" height="700px;" name='DataProductImg[]'></canvas>
                </div>
            </div>
        `;
        _idRowImg++;
        await $('#productImages').append(htmlStr);
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

addProductPriceDetail();


</script>
