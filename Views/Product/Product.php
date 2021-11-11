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
        <button type="button" class="btn btn-lg btn-secondary float-right m-2"><i class="bi bi-table"></i>
            แบบตาราง</button>
        <button type="button" class="btn btn-lg btn-secondary float-right m-2"><i
                class="bi bi-bounding-box-circles"></i> แบบกล่อง</button>
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

async function saveProduct() {
    try {
        let chkSwal2Alerted = 0;
        let elemAlert = null;

        const elemProductName = document.getElementsByName('ProductName');
        const elemRowProductRelated = document.getElementsByName('RowProductRelatedName[]');
        const elemProductRelated = document.getElementsByName('ProductRelatedName[]');
        const elemRowSaleDetail = document.getElementsByName('RowSaleDetail[]');
        const elemUnitType = document.getElementsByName('UnitType[]');
        const elemCostPrice = document.getElementsByName('CostPrice[]');
        const elemSalePrice = document.getElementsByName('SalePrice[]');
        const elemIdBarcode = document.getElementsByName('IdBarcode[]');
        const elemUploadImgProduct = document.getElementsByName('UploadImgProduct[]');
        const elemDataProductImg = document.getElementsByName('DataProductImg[]');

        if (elemProductName[0].value == 0) {
            elemAlert = elemProductName[0];
            chkSwal2Alerted = 1;
        }
        if (chkSwal2Alerted == 0) {
            for (let index = 0; index < elemRowProductRelated.length; index++) {
                if (elemProductRelated[index].value == 0) {
                    elemAlert = elemProductRelated[index];
                    chkSwal2Alerted = 1;
                    break;
                }
            }
        }
        if (chkSwal2Alerted == 0) {
            for (let index = 0; index < elemRowSaleDetail.length; index++) {
                if (elemUnitType[index].value == 0) {
                    elemAlert = elemUnitType[index];
                    chkSwal2Alerted = 1;
                    break;
                }

                if (elemCostPrice[index].value == 0) {
                    elemAlert = elemCostPrice[index];
                    chkSwal2Alerted = 1;
                    break;
                }

                if (elemSalePrice[index].value == 0) {
                    elemAlert = elemSalePrice[index];
                    chkSwal2Alerted = 1;
                    break;
                }

                if (elemIdBarcode[index].value == 0) {
                    elemAlert = elemIdBarcode[index];
                    chkSwal2Alerted = 1;
                    break;
                }
            }
        }
        if (chkSwal2Alerted == 0) {
            for (let index = 0; index < elemUploadImgProduct.length; index++) {
                if (elemUploadImgProduct[index].value == 0) {
                    elemAlert = elemUploadImgProduct[index];
                    chkSwal2Alerted = 1;
                    break;
                }
            }
        }

        if (chkSwal2Alerted == 1) {
            elemAlert.focus();
            await Swal.fire({
                "icon": 'warning',
                "text": elemAlert.title,
                "showConfirmButton": true,
                "confirmButtonText": 'OK',
                "confirmButtonColor": '#fbbc05',
                "timer": 5000
            });

            return false;
        }

        const resChkPro = await asyncSendPostApi('Services/DatasAboutProduct/DatasAboutProduct.controller.php', {
            "Controller": 'GetSimilarProductName',
            "ProductName": elemProductName[0].value
        });

        console.log(resChkPro);
        if (resChkPro.Status != 200 && resChkPro.Status != 204) {
            alert(resChkPro.MessageDesc);
            return false;
        }

        if (resChkPro.length != 0) {
            $resSwal2 = await Swal.fire({
                "title": 'Do you want to save the changes?',
                "showDenyButton": true,
                "showConfirmButton": true,
                "confirmButtonText": 'Save',
                "confirmButtonColor": '#34a853',
                "denyButtonText": `Don't save`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    return true;
                } else if (result.isDenied) {
                    return false;
                }
            });
            if ($resSwal2 == true) {
                alert('ผ่านได้');
                return false;
            } else {
                alert('ไม่ให้ผ่าน');
                return false;
            }
        }

        const createFormDatas = new FormData();
        createFormDatas.append("Controller", 'AddProduct');
        createFormDatas.append("ProductName", 'elemProductName');

        await elemProductRelated.forEach(async (elem, key) => {
            createFormDatas.append(`ProductRelatedName[${key}]`, elem.value);
        });

        await elemUnitType.forEach(async (elem, key) => {
            createFormDatas.append(`UnitType[${key}]`, elemUnitType[key].value);
            createFormDatas.append(`CostPrice[${key}]`, elemCostPrice[key].value);
            createFormDatas.append(`SalePrice[${key}]`, elemSalePrice[key].value);
            createFormDatas.append(`IdBarcode[${key}]`, elemIdBarcode[key].value);
        });

        await elemDataProductImg.forEach(async (elem, key) => {
            createFormDatas.append(`ProductPicture[${key}]`, elem.toDataURL());
        });

        let req = {};

        await createFormDatas.forEach(async (value, key) => {
            req[key] = value;
        });

        console.log(JSON.stringify(req));

        //const resAddPro = await asyncSendPostApi('Services/Product/Product.controller.php/',req);

        return false;

        await Swal.fire({
            "icon": 'warning',
            "text": `บันข้อมูลสินค้าชื่อ ${elemProductName.value} เรียบร้อยแล้วครับ`,
            "showConfirmButton": true,
            "confirmButtonText": 'OK',
            "confirmButtonColor": '#fbbc05',
            "timer": 5000
        });

        await $('#productModal').modal('hide');
        return true;

    } catch (err) {
        alert(`Function saveProduct Error : ${err.message}`);
        return false;
    }
}
asyncAddPressActionClickMulti('#productModalButton', 13, '#productModal');
asyncAddClickAlertInputEmptyMulti('#productModalButton', '#productModal', 'is-invalid');
asyncAddEventClearAlertInputNotEmptyMulti('#productModal', 'change', 'is-invalid');
</script>
