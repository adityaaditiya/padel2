<?php $this->load->view('templates/header'); ?>
<?php $role = $this->session->userdata('role'); ?>
<h2>Tambah Produk</h2>
<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
<form method="post" action="<?php echo site_url('products/store'); ?>" id="productForm">
    <div class="form-group">
        <label for="nama_produk">Nama Produk</label>
        <input type="text" name="nama_produk" id="nama_produk" class="form-control" value="<?php echo set_value('nama_produk'); ?>" required>
    </div>
    <div class="form-group">
        <label for="harga_jual">Harga Jual</label>
        <input type="number" step="0.01" name="harga_jual" id="harga_jual" class="form-control" value="<?php echo set_value('harga_jual'); ?>" required>
    </div>
    <div class="form-group">
        <label for="stok">Stok</label>
        <input type="number" name="stok" id="stok" class="form-control" value="<?php echo set_value('stok'); ?>" required>
    </div>
    <div class="form-group">
        <label for="kategori">Kategori</label>
        <select name="kategori" id="kategori" class="form-control">
            <?php foreach ($categories as $cat): ?>
            <option value="<?php echo $cat; ?>" <?php echo set_select('kategori', $cat); ?>><?php echo ucwords($cat); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="<?php echo site_url('products'); ?>" class="btn btn-secondary">Batal</a>
</form>

<?php if ($role === 'kasir'): ?>
<!-- Modal Konfirmasi -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                data tidak bisa diubah dan dihapus, lanjutkan simpan?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cek Dulu</button>
                <button type="button" class="btn btn-primary" id="confirmSave">Simpan</button>
            </div>
        </div>
    </div>
</div>
<script>
(function() {
    var form = document.getElementById('productForm');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        $('#confirmModal').modal('show');
    });
    document.getElementById('confirmSave').addEventListener('click', function() {
        $('#confirmModal').modal('hide');
        form.submit();
    });
})();
</script>
<?php endif; ?>

<?php $this->load->view('templates/footer'); ?>
