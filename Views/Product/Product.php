<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">ดูข้อมูลและจัดการ</h1>
    <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
</div>
<!-- Content Row -->
<div class="card shadow md-4">
    <div class='md-4'>
        <button type="button" class="btn btn-lg btn-success float-left m-2" onclick="openAddProductFormModal();"><i
                class="bi bi-plus"></i>
            เพิ่มสินค้า</button>
        <button type="button" class="btn btn-lg btn-secondary float-right m-2" onclick="showProductTablePattern();">
            <i class="bi bi-table"></i>
            แบบตาราง</button>
        <button type="button" class="btn btn-lg btn-secondary float-right m-2" onclick="showProductBoxPattern();">
            <i class="bi bi-bounding-box-circles"></i> แบบกล่อง</button>
    </div>
    <div class="col-sm-12 py-2" id="showProducts">

    </div>
</div>

<!-- modal -->

<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" data-backdrop="static"
    data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="productModalBody">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                <button type="button" class="btn btn-primary" id="productModalButton">บันทึก</button>
            </div>
        </div>
    </div>
</div>
<!--End model -->
<script src='Assets/JavaScripts/Products/AddProduct.js'></script>
<script>
async function openAddProductFormModal() {
    try {
        await $('#productModalLabel').html("<i class='bi bi-plus'></i> เพิ่มข้อมูลสินค้า");
        await $('#productModalBody').load('Views/Product/AddProductModal.php');
        await $('#productModalButton').attr('onclick', 'saveProduct();');
        await $('#productModal').modal('show');
        return true;
    } catch (err) {
        alert(`Function openAddProductFormModal Error : ${err.message}`);
        return false;
    }
}

async function showProductTablePattern() {
    try {
        const element = document.querySelector('#showProducts');
        element.innerHTML = `
        <div class='table-responsive'>
            <table width='100%' cellspacing='0' class='table table-bordered dataTable' id='getProductsDataTable'>
                <thead>
                    <tr>
                        <th>รหัส</th>
                        <th>ชื่อสินค้า</th>
                        <th>ราคาขายสินค้า</th>
                        <th>สถานะใช้งาน</th>
                        <th>วันที่บันทึกล่าสุด</th>
                        <th>ผู้บันทึกล่าสุด</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>`;

        await $('#getProductsDataTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": 'Services/Product/Product.controller.php',
                "type": 'POST',
                "data": {
                    "Controller": 'GetProductsForDataTable'
                },
                "dataFilter": (datas) => {
                    const json = jQuery.parseJSON(datas);
                    //let map = [];
                    json.Datas.forEach((obj) => {
                        let strSub = ``;
                        obj.Price.forEach((objSub) => {
                            strSub += `<div class='row py-1 mt-1 mx-2 border border-secondary'>
                                <div class='col-lg-6 text-left'>${objSub.UnitName}</div>
                                <div class='col-lg-6 text-right'>${objSub.SalePrice} บ.</div>
                            </div>`;
                        });
                        obj.Price = strSub;
                        //map.push(obj);
                    });
                    json.data = json.Datas;
                    json.recordsTotal = json.RecordsTotal;
                    json.recordsFiltered = json.RecordsFiltered;
                    json.draw = json.Draw;
                    return JSON.stringify(json);
                }
            },
            "createdRow": function ( row, data, index ) {
                let chkAttr = '';

                if(data.ActiveStatus == 0) chkAttr = 'checked';

                $('td', row).eq(3).html(`
                    <div class='row px-2'> 
                        <div class="custom-control custom-switch col-sm-12" style='zoom:140%;'>
                            <input type="checkbox" class="custom-control-input"id="switchActive${index}" ${chkAttr} 
                            onchange="switchActiveStatusProduct(this,'${data.Id}','${data.Name}');" >
                            <label class="custom-control-label" for="switchActive${index}" >ON</label>
                        </div>
                    </div>`);
            },
            "columns": [{
                    "data": 'Id',
                    "name": 'ProductName.Id',
                    "orderable": true
                },
                {
                    "data": 'Name',
                    "name": 'ProductName.Name',
                    "orderable": true
                },
                {
                    "data": 'Price',
                    "name": 'ProductPrice.SalePrice',
                    "orderable": false
                },
                {
                    "data": 'ActiveStatus',
                    "name": 'ProductName.ActiveStatus',
                    "orderable": true
                },
                {
                    "data": 'SaveDate',
                    "name": 'ProductName.SaveDate',
                    "orderable": true
                },
                {
                    "data": 'Username',
                    "name": 'Member.UserId',
                    "orderable": true
                }
            ],
            "order": [
                [0, "ASC"]
            ]

        });
        return true;
    } catch (err) {
        alert(`Function showProductTablePattern Error : ${err.message}`);
        return false;
    }

}
async function showProductBoxPattern() {

}

async function switchActiveStatusProduct(elem,idProductNumber,textProduct = null)
{
    try
    {
        let activeStatus = 0;
        const proStr = textProduct != null ? `สินค้าชื่อ : ${textProduct}\n` : "";
        if(elem.checked == false) activeStatus = 1;

        const res = await asyncSendPostApi('Services/Product/Product.controller.php',{
            "Controller":'SwitchActiveStatus',
            "Username":_Username,
            "Password":_Password,
            "IdProductName":idProductNumber,
            "ActiveStatus":activeStatus
        });

        if(res.Status != 200)
        {
            alert(res.MessageDesc);
            if(elem.checked == false) elem.checked = true;
            else elem.checked = false;
            return false;
        }

        await Swal.fire({
            "icon": 'success',
            "text": `${proStr}ทำการเปลียนสถานะใช้งานเรียบร้อยแล้วครับ!`,
            "showConfirmButton": true,
            "confirmButtonText": 'OK',
            "confirmButtonColor": '#34a853',
            "timer": 5000
        });

        return true;
    }
    catch(err)
    {
        return false;
    }
}

asyncAddPressActionClickMulti('#productModalButton', 13, '#productModal');
asyncAddClickAlertInputEmptyMulti('#productModalButton', '#productModal', 'is-invalid');
asyncAddEventClearAlertInputNotEmptyMulti('#productModal', 'change', 'is-invalid');
</script>
