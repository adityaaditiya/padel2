<?php $this->load->view('templates/header'); ?>
<h2>Edit Produk Penukaran</h2>
<form method="post" action="<?= site_url('rewards/update/'.$product->id); ?>">
    <div class="form-group">
        <label>Nama Produk</label>
        <input type="text" name="nama_produk" class="form-control" value="<?= htmlspecialchars($product->nama_produk); ?>" required>
    </div>
    <div class="form-group">
        <label>Poin</label>
        <input type="number" name="poin" class="form-control" value="<?= (int) $product->poin; ?>" required>
    </div>
    <div class="form-group">
        <label>Stok</label>
        <input type="number" name="stok" class="form-control" value="<?= (int) $product->stok; ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
</form>
<?php $this->load->view('templates/footer'); ?>
