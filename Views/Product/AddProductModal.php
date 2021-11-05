<div class="container-fluid">
    <div class="row form-group">
        <div class="col-sm-12">
            <label>ชื่อสินค้า</label>
        </div>
        <div class="col-sm-12">

            <input type="text" class="form-control" name="ProductName" value=""
                placeholder="ชื่อสินค้า" title="กรุณาป้อนข้อมูลชื่อสินค้าด้วยครับ">
        </div>
    </div>
    <div class="row form-group" id="saleProductDetail">
        <div class="col-sm-12">
            <label class="d-inline">รายละเอียดราคาสินค้า</label>
            <button class="btn btn-sm btn-primary" onclick="addProductPriceDetail();"><i class="bi bi-plus"></i> เพิ่มรายการ</button>
        </div>
    </div>
</div>

<script>
async function addProductPriceDetail()
{
    try
    {
        const htmlStr = `
        <div class="row border-bottom-dark py-2" name='RowSaleDetail[]'>
            <div class="input-group col-lg-3">
                <div class="input-group-prepend">
                    <button class="btn btn-danger" type="button" name="delProductPriceButton[]" onclick="delProductPriceDetail(this);">ลบ</button>
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
                <input type="text" class="form-control" name="IdBarcode[]" value="" placeholder="Id Barcode"
                    title="กรุณาข้อมูล Id Barcode ด้วยครับ">
                <div class="input-group-append">
                    <button class="btn btn-secondary" type="button">Auto</button>
                </div>
            </div>
        </div>`;

        await $('#saleProductDetail').append(htmlStr);
        return true;
    }
    catch(err)
    {
        alert(`Function addProductPriceDetail Error : ${err.message}`);
        return false;
    }
}

async function delProductPriceDetail(elem)
{
    try
    {
        await asyncAddElemByNameAttrTabIndex(elem);
        $(`div[name='RowSaleDetail[]`)[elem.tabIndex].remove();
        return true;
    }
    catch(err)
    {
        alert(`Function delProductPriceDetail Error : ${err.message}`);
        return false;
    }
}

addProductPriceDetail();
</script>