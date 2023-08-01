<!-- Kode HTML untuk menampilkan daftar semua pesanan -->
<div class="container">
    <div class="container shadow-container">
        <?php if($this->session->flashdata('success') != ""):?>
        <div class="alert alert-success">
            <?php echo $this->session->flashdata('success');?>
        </div>
        <?php endif ?>
        <?php if($this->session->flashdata('error') != ""):?>
        <div class="alert alert-danger">
            <?php echo $this->session->flashdata('error');?>
        </div>
        <?php endif ?>
        <div class="d-flex justify-content-between align-items-center">
            <div class="btn-group">
                <h2>Semua Pesanan</h2>
            </div>
            <input class="form-control mb-3" id="myInput" type="text" placeholder="Cari .." style="width:50%;">
        </div>

        <div class="table-responsive-sm">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Nama Pengguna</th>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Alamat</th>
                        <th>Status</th>
                        <th>Tanggal Pesan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="myTable">
                    <?php if(!empty($orders)) {?>
                    <?php foreach($orders as $order) { ?>
                    <tr>
                        <td><?php echo $order['username']; ?></td>
                        <td><?php echo $order['d_name']; ?></td>
                        <td><?php echo $order['quantity']; ?></td>
                        <td><?php echo "Rp.".$order['price']; ?></td>
                        <td><?php echo $order['address']; ?></td>

                        <?php $status=$order['status'];
						if($status=="" or $status=="NULL") { ?>
                        <td> <button type="button" class="btn btn-secondary" style="font-weight:bold;"><i class="fas fa-bars"></i> Pengiriman</button></td>
                        <?php } if($status=="in process") { ?>
                        <td> <button type="button" class="btn btn-warning"><span class="fa fa-cog fa-spin"
                                    aria-hidden="true"></span> Sedang Dalam Perjalanan</button></td>
                        <?php } if($status=="closed") { ?>
                        <td> <button type="button" class="btn btn-success"><span class="fa fa-check-circle"aria-hidden="true"></span> Terkirim</button>
                        </td> <?php } ?> <?php if($status=="rejected") { ?>
                        <td> <button type="button" class="btn btn-danger"><i class="far fa-times-circle"></i> Dibatalkan</button>
                        </td>
                        <?php } ?>
                        <td><?php echo $order['date']; ?></td>
                        <td>
                            <a href="<?php echo base_url().'admin/orders/processOrder/'.$order['o_id'];?>"
                                class="btn btn-info mb-1"> <i class="fas fa-arrow-alt-circle-right"></i> Proses</a>
                            <a href="<?php echo base_url().'admin/orders/deleteOrder/'.$order['o_id']?>"
                                class="btn btn-danger"><i class="fas fa-trash-alt"></i> Hapus</a>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php } else {?>
                    <tr>
                        <td colspan="8">Rekaman tidak ditemukan</td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
function deleteOrder(id) {
    if (confirm("Apakah Anda yakin ingin menghapus pesanan?")) {
        window.location.href = '<?php echo base_url().'admin/orders/deleteOrder/';?>' + id;
    }
}

$(document).ready(function() {
    $("#myInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#myTable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
</script>
