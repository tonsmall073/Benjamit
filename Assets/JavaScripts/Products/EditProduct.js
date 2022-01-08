async function editProduct() {
    try {
        let chkSwal2Alerted = 0;
        let elemAlert = null;

        const elemProductName = document.getElementsByName('ProductName');
        const elemDetailAboutProduct = document.getElementsByName('DetailAboutProduct');
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
            if (elemDetailAboutProduct[0].value == 0) {
                elemAlert = elemDetailAboutProduct[0];
                chkSwal2Alerted = 1;
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

        if (resChkPro.Status != 200 && resChkPro.Status != 204) {
            alert(resChkPro.MessageDesc);
            return false;
        }

        if (resChkPro.Datas.length > 0) {

            
            createHtmlSwal2 = await createHtmlSimilarProductSwal2(resChkPro.Datas,elemProductName[0].value);

            const resSwal2 = await Swal.fire({
                "title": 'พบรายการคล้ายกัน คุณต้องการบันทึกข้อมูล ใช่หรือไม่',
                "html" : createHtmlSwal2,
                "showDenyButton": true,
                "showConfirmButton": true,
                "confirmButtonText": 'ใช่',
                "confirmButtonColor": '#34a853',
                "denyButtonText": 'ไม่',
            }).then((result) => {
                if (result.isConfirmed) {
                    return true;
                } else if (result.isDenied) {
                    return false;
                }
            });

            if (resSwal2 != true) return false;
        }

        const createFormDatas = new FormData();
        createFormDatas.append("Controller", 'AddProduct');
        createFormDatas.append("Username", _Username);
        createFormDatas.append("Password", _Password);
        createFormDatas.append("ProductName", elemProductName[0].value);
        createFormDatas.append("DetailAboutProduct", elemDetailAboutProduct[0].value);

        await elemProductRelated.forEach(async (elem, key) => {
            createFormDatas.append(`ProductRelatedName[${key}]`, elem.value);
        });

        await elemUnitType.forEach(async (elem, key) => {
            const costPrice = await elemCostPrice[key].value.replace(/,/g,'');
            const salePrice = await elemSalePrice[key].value.replace(/,/g,'');
            createFormDatas.append(`UnitType[${key}]`, elemUnitType[key].value);
            createFormDatas.append(`CostPrice[${key}]`, costPrice);
            createFormDatas.append(`SalePrice[${key}]`, salePrice);
            createFormDatas.append(`IdBarcode[${key}]`, elemIdBarcode[key].value);
        });

        await elemDataProductImg.forEach(async (elem, key) => {
            createFormDatas.append(`ProductPicture[${key}]`, elem.toDataURL());
        });

        let req = {};

        await createFormDatas.forEach(async (value, key) => {
            req[key] = value;
        });

        const resAddPro = await asyncSendPostApi('Services/Product/Product.controller.php',req);

        if(resAddPro.Status != 200)
        {
            alert(resAddPro.MessageDesc);
            return false;
        }

        await Swal.fire({
            "icon": 'success',
            "text": `บันข้อมูลสินค้าชื่อ ${elemProductName[0].value} เรียบร้อยแล้วครับ`,
            "showConfirmButton": true,
            "confirmButtonText": 'OK',
            "confirmButtonColor": '#34a853',
            "timer": 5000
        });

        await $('#productModal').modal('hide');
        return true;

    } catch (err) {
        alert(`Function saveProduct Error : ${err.message}`);
        return false;
    }
}

async function createHtmlSimilarProductSwal2(params,textProName)
{
    let alertDanger = ``;
    let notAlert = ``;
    await params.forEach(async (datas,key) => {
        const num = key + 1;
        if(datas.Name == textProName)
        alertDanger +=`<h5 class='alert-danger'>${num}. ${datas.Name}</h5>`;
        else notAlert += `<h5>${num}. ${datas.Name}</h5>`;
    });
    
    let htmlSwal2 = `<div class="overflow-auto text-left" style="max-height: 100px;">`;
    htmlSwal2 += alertDanger;
    htmlSwal2 += notAlert;
    htmlSwal2 += `</div>`;
    return htmlSwal2;
}