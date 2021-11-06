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
                เพิ่มรายการ</button>
        </div>
    </div>
</div>

<script>
var _idRowPro = 0;
async function addProductPriceDetail(index = 0) {
    try {
        const htmlStr = `
        <div class="row border-bottom-dark py-2" id="rowSaleDetail${_idRowPro}" name='RowSaleDetail[]'>
            <div class="input-group col-lg-3">
                <div class="input-group-prepend">
                    <button class="btn btn-danger" type="button" name="delProductPriceButton[]" 
                    onclick="delProductPriceDetail(${_idRowPro});">ลบ</button>
                </div>
                <select class="form-control" name="UnitType[]" title="กรุณาเลือกหน่วยสินค้าด้วยครับ">
                    <option value=''>เลือกหน่วย</option>
                </select>
            </div>
            <div class="input-group col-lg-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">ราคาทุน</span>
                </div>
                <input type="text" class="form-control" name="CostPrice[]" value="" placeholder="ราคาทุน"
                    title="กรุณาป้อนราคาต้นทุนด้วยครับ">
            </div>
            <div class="input-group col-lg-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">ราคาขาย</span>
                </div>
                <input type="text" class="form-control" name="SalePrice[]" value="" placeholder="ราคาขาย"
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
        _idRowPro++;
        return true;
        
    } catch (err) {
        alert(`Function htmlProductPriceDetail Error : ${err.message}`);
        return false;
    }
}

async function delProductPriceDetail(idRow) {
    try {
        $(`#rowSaleDetail${idRow}`).remove();
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
        $(`#idBarcode${idRow}`).removeClass('bg-border-danger-input-empty');
        return true;
    } catch (err) {
        alert(`Function switchAutoGenIdBarcode Error : ${err.message}`);
        return false;
    }
}

addProductPriceDetail();


</script>
