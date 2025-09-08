<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('store/overlay'); ?>
<h2>Manajemen Stok Manual</h2>
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>
<form method="post" action="<?php echo site_url('manual_stock/save'); ?>" class="mb-4">
    <div class="form-row">
        <div class="col-md-4">
            <label for="product">Cari Produk</label>
            <input list="products" name="product_id" id="product" class="form-control" required>
            <datalist id="products">
                <?php foreach ($products as $p): ?>
                <option value="<?php echo $p->id; ?>"><?php echo htmlspecialchars($p->nama_produk); ?></option>
                <?php endforeach; ?>
            </datalist>
        </div>
        <div class="col-md-2">
            <label for="qty">Jumlah</label>
            <input type="number" name="qty" id="qty" min="1" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label for="type">Jenis Transaksi</label>
            <select name="type" id="type" class="form-control" required>
                <option value="Tambah">Tambah</option>
                <option value="Ambil">Ambil</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="note">Keterangan</label>
            <input type="text" name="note" id="note" class="form-control">
        </div>
    </div>
    <button type="submit" class="btn btn-primary mt-3">Simpan</button>
</form>
<?php $this->load->view('templates/footer'); ?>
