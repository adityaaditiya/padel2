<?php $this->load->view('templates/header'); ?>
<h2>Tambah Produk Penukaran</h2>
<form method="post" action="<?= site_url('rewards/store'); ?>">
    <div class="form-group">
        <label>Nama Produk</label>
        <input type="text" name="nama_produk" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Poin</label>
        <input type="number" name="poin" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Stok</label>
        <input type="number" name="stok" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
</form>
<?php $this->load->view('templates/footer'); ?>
