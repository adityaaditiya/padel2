<?php $this->load->view('templates/header'); ?>
<h2>Edit Produk</h2>
<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
<form method="post" action="<?php echo site_url('products/update/'.$product->id); ?>">
    <div class="form-group">
        <label for="nama_produk">Nama Produk</label>
        <input type="text" name="nama_produk" id="nama_produk" class="form-control" value="<?php echo set_value('nama_produk', $product->nama_produk); ?>" required>
    </div>
    <div class="form-group">
        <label for="harga_jual">Harga Jual</label>
        <input type="number" step="0.01" name="harga_jual" id="harga_jual" class="form-control" value="<?php echo set_value('harga_jual', $product->harga_jual); ?>" required>
    </div>
    <div class="form-group">
        <label for="stok">Stok</label>
        <input type="number" name="stok" id="stok" class="form-control" value="<?php echo set_value('stok', $product->stok); ?>" required>
    </div>
    <div class="form-group">
        <label for="kategori">Kategori</label>
        <select name="kategori" id="kategori" class="form-control">
            <?php foreach ($categories as $cat): ?>
            <option value="<?php echo $cat; ?>" <?php echo set_select('kategori', $cat, $product->kategori === $cat); ?>><?php echo ucwords($cat); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="<?php echo site_url('products'); ?>" class="btn btn-secondary">Batal</a>
</form>
<?php $this->load->view('templates/footer'); ?>
