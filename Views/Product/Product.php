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
        await alert(`Function openAddProductFormModal Error : ${err.message}`);
        return false;
    }
}
</script>
