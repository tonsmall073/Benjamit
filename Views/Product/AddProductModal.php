<div class="container-fluid">
    <div class="row form-group">
        <div class="col-sm-12">
            <label>ชื่อสินค้า</label>
        </div>
        <div class="col-sm-12">

            <input type="text" class="form-control" name="productName" aria-describedby="emailHelp"
                placeholder="ชื่อสินค้า" title="กรุณาป้อนข้อมูลชื่อสินค้าด้วยครับ">
        </div>
    </div>
    <div class="row form-group">
        <div class="col-sm-12">
            <label class="d-inline">รายละเอียดราคาสินค้า</label>
            <button class="btn btn-sm btn-primary"><i class="bi bi-plus"></i> เพิ่มรายการ</button>
        </div>
        <div class="row" name='rowSaleDetail[]'>

            <div class="input-group col-sm-3">
                <div class="input-group-prepend">
                    <button class="btn btn-danger" type="button">ลบ</button>
                </div>
                <select class="form-control" name="unitType[]" title="กรุณาเลือกหน่วยสินค้าด้วยครับ">
                    <option value=''>เลือกหน่วย</option>
                </select>
            </div>
            <div class="input-group col-sm-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">ราคาต้นทุน</span>
                </div>
                <input type="text" class="form-control" name="costPrice[]" placeholder="ราคาต้นทุน"
                    title="กรุณาป้อนราคาต้นทุนด้วยครับ">
            </div>
            <div class="input-group col-sm-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">ราคาขาย</span>
                </div>
                <input type="text" class="form-control" name="salePrice[]" placeholder="ราคาขาย"
                    title="กรุณาป้อนราคาขายด้วยครับ">
            </div>
            <div class="input-group col-sm-3">
                <input type="text" class="form-control" name="idBarcode[]" placeholder="Id Barcode"
                    title="กรุณาข้อมูล Id Barcode ด้วยครับ">
                <div class="input-group-append">
                    <button class="btn btn-secondary" type="button">Auto</button>
                </div>
            </div>

        </div>
    </div>
</div>
