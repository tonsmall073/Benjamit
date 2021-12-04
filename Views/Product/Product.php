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
                <button type="button" class="btn btn-success" id="productModalButton">บันทึก</button>
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
        <div class='table-responsive shadow p-3'>
            <table width='100%' cellspacing='0' class='table table-bordered dataTable' id='getProductsDataTable'>
                <thead>
                    <tr>
                        <th>รหัส</th>
                        <th>ชื่อสินค้า</th>
                        <th>ราคาขายสินค้า</th>
                        <th>สถานะใช้งาน</th>
                        <th>วันที่บันทึกล่าสุด</th>
                        <th>ผู้บันทึกล่าสุด</th>
                        <th>แก้ไข</th>
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
                        obj.ButtonEdit = `<button class='btn btn-sm btn-warning' onclick='openEditProductFormModal({${obj.Id})'>แก้ไข</button>`;
                        let strSub = ``;
                        obj.Prices.forEach((objSub) => {
                            strSub += `<div class='row py-1 mt-1 mx-2 border border-secondary'>
                                <div class='col-lg-6 text-left'>${objSub.UnitName}</div>
                                <div class='col-lg-6 text-right'>${addCommas(objSub.SalePrice,2)} บ.</div>
                            </div>`;
                        });
                        obj.Prices = strSub;
                        //map.push(obj);
                    });
                    json.data = json.Datas;
                    json.recordsTotal = json.RecordsTotal;
                    json.recordsFiltered = json.RecordsFiltered;
                    json.draw = json.Draw;
                    return JSON.stringify(json);
                }
            },
            "createdRow": ( row, data, index ) => {
                const chkAttr = data.ActiveStatus == 0 ? 'checked' : '';

                $('td', row).eq(1).append(`
                    <a class='text-white rounded-lg bg-info' href='#viewDetailProductNo${data.Id}' 
                    id='viewDetailProductNo${data.Id}' onclick='openDetailProductFormModal(${data.Id});'>ดูข้อมูลเพิ่ม</a>
                `);

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
                    "data": 'Prices',
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
                },
                {
                    "data": 'ButtonEdit',
                    "name": 'ProductName.Id',
                    "orderable": false
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

async function showProductBoxPattern()
{
    try
    {

    
    const element = document.querySelector('#showProducts').innerHTML = `
    <div class='row'>
        <div class='col-sm-12 px-4'>
            <div class='card shadow mb-3 p-4'>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="searchProduct"><i class="bi bi-search"></i></span>
                    </div>
                    <input type="text" class="form-control" name="searchProduct" 
                    placeholder="ค้นหาสินค้า" aria-label="searchProduct" onkeyup="searchAllProductBoxPattern(this.value);">
                </div>
            </div>
        </div>
    </div>
    <div class='col-sm-12' id='getAllProducts'>
    </div>
    <div class='col-sm-12' id='paginationPrduct'>
        <nav>
            <ul class="pagination">
              <li class="page-item">
                <a class="page-link" href="#" aria-label="Previous">
                  <span aria-hidden="true">Previous</span>
                  <span class="sr-only">Previous</span>
                </a>
              </li>
              <li class="page-item active"><a class="page-link" href="#">1</a></li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item">
                <a class="page-link" href="#" aria-label="Next">
                  <span aria-hidden="true">Next</span>
                  <span class="sr-only">Next</span>
                </a>
              </li>
            </ul>
        </nav>
    </div>`;

    await searchAllProductBoxPattern();

    return true;
    }
    catch(err)
    {
        alert(`Function showProductBoxPattern Error : ${err.message}`);
        return false;
    }
}

async function searchAllProductBoxPattern(valSearch = null,valStart = 0,valLimit = 6)
{
    try
    {
        await getAllProductBoxPattern('#getAllProducts',valSearch,valStart,valLimit);
        return true;
    }
    catch(err)
    {
        alert(`Function searchAllProductBoxPattern Error : ${err.message}`);
        return false;
    }
}

async function getAllProductBoxPattern(idRender,valSearch = null,valStart,valLimit) {

    try
    {
        const res = await asyncSendPostApi(
            'Services/Product/Product.controller.php',
        {
            "Controller" : 'GetProducts',
            "Start" : valStart,
            "Length" : valLimit,
            "SearchValue" : valSearch
        });

        if(res.Status != 200)
        {
            alert(res.MessageDesc);
            return false;
        }

        const element = document.querySelector(idRender);
        element.innerHTML = '';

        const row = document.createElement('DIV');
        row.classList.add('row');

        if(res.Datas.length <= 0)
        {
            row.innerHTML = `
                <div class='col-sm-12 p-3 text-center'>
                    <b class='font-weight-bold'>
                        <div class='card shadow p-3'>
                        No data available
                    </b>
                    </div>
                </div>
            `;
            element.appendChild(row);
            return true;
        }
        
        

        await res.Datas.forEach(async (datas) => {
            const col = document.createElement('DIV');
            col.classList.add('col-lg-4');
            col.classList.add('p-3');

            const divCard = document.createElement('DIV');
            divCard.classList.add('card');
            divCard.classList.add('border-top-0');
            divCard.classList.add('shadow');

            if(datas.Images.length > 0)
            {
                const divCarousel = document.createElement('DIV');
                divCarousel.classList.add('carousel');
                divCarousel.classList.add('slide');
                divCarousel.id = `carouselProductId${datas.Id}`;
                divCarousel.setAttribute('data-ride','carousel');

                const divCarouselInner = document.createElement('DIV');
                divCarouselInner.classList.add('carousel-inner');

                await datas.Images.forEach((datas,key) => {
                    const divCarouselItem = document.createElement('DIV');
                    divCarouselItem.classList.add('carousel-item');

                    if(key == 0) divCarouselItem.classList.add('active');

                    divCarouselItem.innerHTML = `<img class="d-block w-100" src="Assets/Images/Products/${datas.FileName}">`;
                    
                    divCarouselInner.appendChild(divCarouselItem);
                });

                if(datas.Images.length > 1)
                {
                    const aPrev = document.createElement('A');
                    aPrev.classList.add('carousel-control-prev');
                    aPrev.setAttribute('role','button');
                    aPrev.setAttribute('data-slide','prev');
                    aPrev.href = `#carouselProductId${datas.Id}`;
                    aPrev.innerHTML = `
                        <span class="carousel-control-prev-icon rounded-circle bg-dark py-3" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    `;
                    divCarousel.appendChild(aPrev);

                    const aNext = document.createElement('A');
                    aNext.classList.add('carousel-control-next');
                    aNext.setAttribute('role','button');
                    aNext.setAttribute('data-slide','next');
                    aNext.href = `#carouselProductId${datas.Id}`;
                    aNext.innerHTML = `
                        <span class="carousel-control-next-icon rounded-circle bg-dark py-3" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    `;
                    divCarousel.appendChild(aNext);
                }

                divCarousel.appendChild(divCarouselInner);
                divCard.appendChild(divCarousel);
            }
            else
            {
                const divCardImg = document.createElement('IMG');
                divCardImg.src = 'img/No_image_available_square.png';
                divCardImg.classList.add('d-block');
                divCardImg.classList.add('w-100');
                divCard.appendChild(divCardImg);
            }

            const divCardBody1 = document.createElement('DIV');
            divCardBody1.classList.add('card-body');
            divCardBody1.classList.add('pb-0');
            divCardBody1.classList.add('border-bottom-secondary');
            
            const divCardBody1H = document.createElement('H3');
            divCardBody1H.classList.add('card-title');
            divCardBody1H.innerText = `${datas.Name}`;
            
            divCardBody1.appendChild(divCardBody1H);
            divCard.appendChild(divCardBody1);
            
            const divCardBody2 = document.createElement('DIV');
            divCardBody2.classList.add('card-body');
            
            const divCardBody2H5 = document.createElement('H5');
            divCardBody2H5.innerText = 'ราคาขาย';
            divCardBody2.appendChild(divCardBody2H5);

            const divCardBody2Div = document.createElement('DIV');
            divCardBody2Div.classList.add('overflow-auto');
            divCardBody2Div.style.height = '100px';
            
            const divCardBody2UL = document.createElement('UL');
            divCardBody2UL.classList.add('list-group');
            divCardBody2UL.classList.add('list-group-flush');

            divCardBody2Div.appendChild(divCardBody2UL);
            divCardBody2.appendChild(divCardBody2Div);
            await datas.Prices.forEach(async (details) => {
                const salePrice = await asyncAddCommas(details.SalePrice,2);
                const divCardBody2ULLi = document.createElement('LI');
                divCardBody2ULLi.classList.add('list-group-item');
                divCardBody2ULLi.innerHTML = `
                <div class='row'>
                    <div class='col-lg-6 text-left'>
                        ${details.UnitName}
                    </div>
                    <div class='col-lg-6 text-right'>
                        ${salePrice} บ.
                    </div>
                </div>`;
                divCardBody2UL.appendChild(divCardBody2ULLi);
            });
            const divCardBody3 = document.createElement('DIV');
            divCardBody3.classList.add('card-body');
            divCardBody3.innerHTML = `
            <div class='row text-center'>
                <div class='col-md-6'>
                    <button class='btn btn-md btn-primary' onclick='openDetailProductFormModal(${datas.Id});'>ดูข้อมูลเพิ่ม</button>
                </div>
                <div class='col-md-6'>
                    <button class='btn btn-md btn-warning' onclick='openEditProductFormModal({${datas.Id})'>แก้ไขข้อมูล</button>
                </div>
            </div>
            `;
            
            divCard.appendChild(divCardBody2);
            divCard.appendChild(divCardBody3);
            col.appendChild(divCard)
            
            row.appendChild(col);
        });

        await element.appendChild(row);
        await $('.carousel').carousel('pause')
        return true;
    }
    catch(err)
    {
        alert(`Function showProductBoxPattern Error : ${err.message}`);
        return false;
    }
    const element = document.querySelector('#showProducts');

    const row = document.createElement
    

}

async function openDetailProductFormModal(idProductNumber)
{

}

async function openEditProductFormModal(idProductNumber)
{

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
showProductBoxPattern();
</script>
