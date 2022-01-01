async function openDetailProductFormModal(idProductNumber)
{
    try {
        await $('#productDetailModal').modal('show');
        const res = await asyncSendPostApi('Services/Product/Product.controller.php',
        {
            "Controller" : 'GetProductDetail',
            "Username" : _Username,
            "Password" : _Password,
            "IdProductName" : idProductNumber
        });
        
        await $('#productDetailModalLabel').html(`<b>สินค้า : </b>${res.Content.ProductName}`);

        let createHtml = `<div class='row d-flex justify-content-center border-bottom-secondary pb-4'>`; //div class row

        createHtml += `<div class='col-sm-7'>`; //div class col-sm-12

        createHtml += `<div id="carouselProductDetail" class="carousel slide" data-ride="carousel">`; //div class carousel slide

        createHtml += `<div class="carousel-inner">`; //div class carousel-inner
        
        if(res.Content.Images.length <= 0)
        {
            createHtml += `
            <div class="carousel-item active">
                <img class="d-block w-100" src="img/No_image_available_square.png">
            </div>`;
        }
        else
        {
            res.Content.Images.forEach(async (datas,key) => {
                const imgActive = key <= 0 ? 'active' : '';
                createHtml += `
                <div class="carousel-item ${imgActive}">
                    <img class="d-block w-100" src="Assets/Images/Products/${datas.FileName}">
                </div>`;
            });
        }

        createHtml += `</div>`; //div end class carousel-inner

        if(res.Content.Images.length > 1)
        {
            createHtml += `
            <a class="carousel-control-prev" href="#carouselProductDetail" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon rounded-circle bg-dark py-3" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselProductDetail" role="button" data-slide="next">
                <span class="carousel-control-next-icon rounded-circle bg-dark py-3" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>`;
        }

        createHtml += `</div>`; //end div carousel slide

        createHtml += `</div>`; //end div class col-sm-12

        createHtml += `</div>`; //end div class row

        if(res.Content.RelatedProducts.length > 0)
        {
            createHtml += `<div class='row border-bottom-secondary'>`; //div class row
            createHtml += `
            <div class='col-sm-4'>
                <b>ศัพท์เรียกตัวสินค้า :</b>
            </div>`;

            let htmlRelatedName = ``;

            await res.Content.RelatedProducts.forEach(async (datas) => {
                htmlRelatedName += `
                <li class="list-group-item">
                    <div class="row">
                        <div class='col-12'>
                            ${datas.Name}
                        </div>
                    </div>
                </li>
                `;
            });

            createHtml += `
            <div class='col-sm-8'>
                <ul class="list-group list-group-flush">
                    ${htmlRelatedName}
                </ul>
            </div>`;
            createHtml += `</div>`; //end div class row
        }

        createHtml += `<div class='row border-bottom-secondary'>`; //div class row
        createHtml += `
        <div class='col-sm-4'>
            <b>คำอธิบายเกี่ยวกับตัวสินค้า :</b>
        </div>
        <div class='col-8'>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <div class="row">
                        <div class='col-12'>
                             ${res.Content.DetailAboutProduct != '' ? res.Content.DetailAboutProduct : '-'}
                        </div>
                    </div>
                </li>
            </ul>
        </div>`;
        createHtml += `</div>`;//end div class row
        
        createHtml += `<div class='row border-bottom-secondary'>`; //div class row
        createHtml += `
        <div class='col-sm-4'>
            <b>รายละเอียดราคา :</b>
        </div>
        <div class='col-sm-8'>
            <ul class="list-group list-group-flush" id='productPriceDetail'>

            </ul>
        </div>
        `;
        createHtml += `</div>`;//end div class row
        
        await $("#productDetailModalBody").html(createHtml);

        await $('#carouselProductDetail').carousel('pause');

        const createHtmlPriceTitle = `
        <li class="list-group-item">
            <div class="row">
                <div class="col-4 text-left font-weight-bold">
                    หน่วย
                </div>
                <div class="col-4 text-right font-weight-bold">
                    ต้นทุน
                </div>
                <div class="col-4 text-right font-weight-bold">
                    ราคาขาย
                </div>
            </div>
        </li>`;

        await $('#productPriceDetail').append(createHtmlPriceTitle);

        for(let i = 0;i < res.Content.Prices.length;i++)
        {
            const unitName = res.Content.Prices[i].UnitName;
            const costPrice = await asyncAddCommas(res.Content.Prices[i].CostPrice,2);
            const salePrice = await asyncAddCommas(res.Content.Prices[i].SalePrice,2);
            const idBarCode = res.Content.Prices[i].IdBarcode;
            
            const createHtmlPriceDetail = `
            <li class="list-group-item">
                <div class="row">
                    <div class="col-12 d-flex justify-content-end">
                        <button type='button' 
                        class='btn btn-sm btn-secondary rounded-circle' 
                        id='slideTogglePriceDetailButton${i}' 
                        onclick='slideToggleProductDetail("priceDetailRow${i}","slideTogglePriceDetailButton${i}");'
                        style="width: 25px; height: 25px; padding:0;" >-</button>
                    </div>
                </div>
                <div class="row" id=priceDetailRow${i}>
                    <div class="col-4 text-left">
                        ${unitName}
                    </div>
                    <div class="col-4 text-right">
                        ${costPrice} บ.
                    </div>
                    <div class="col-4 text-right">
                        ${salePrice} บ.
                    </div>
                    <div class="col-12 d-flex justify-content-center">
                        <img class='d-block' id='productBarcode${i}'>
                    </div>
                    <div class="col-12 d-flex justify-content-center">
                        <a target='_blank' id='productBarcodePrint${i}' class='btn btn-md btn-success'>Print</a>
                        <button type='button' class='btn btn-md btn-primary' onclick='copyText("${idBarCode}");'>Copy</button>
                    </div>
                </div>
            </li>`;

            await $(`#productPriceDetail`).append(createHtmlPriceDetail);

            await JsBarcode(`#productBarcode${i}`,idBarCode,{
                "format" : 'CODE128',
                "height" : '50',
                "fontSize" : '14'
            });
            
            const idBarcodeBase64 = document.querySelector(`#productPriceDetail #productBarcode${i}`).src;
            
            document.querySelector(`#productPriceDetail #productBarcodePrint${i}`).href = 
            `Views/Print/ProductBarcode.php?Username=${_Username}&FileName=${idBarCode}&ImgBase64=${idBarcodeBase64}`;

        }

        return true;
    } catch (err) {
        alert(`Function openDetailProductFormModal Error : ${err.message}`);
        return false;
    }
}
async function slideToggleProductDetail(idContentAction,idButtonAction = null)
{
    try
    {
        const res = await $(`#${idContentAction}`).slideToggle(100,
        async () =>{
            
            if(idButtonAction != null)
            {
                const elemStyleDisplay = document.getElementById(idContentAction).style.display;

                if(elemStyleDisplay == 'none') $(`#${idButtonAction}`).text('+');

                else $(`#${idButtonAction}`).text('-');

                return true;
            }
            return null;
        });

        return res;
    }
    catch(err)
    {
        alert(`Function slideToggleProductDetail Error : ${err.message}`);
        return false;
    }
}