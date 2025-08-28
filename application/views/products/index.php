<?php $this->load->view('templates/header'); ?>
<h2>Daftar Produk</h2>
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>
<a href="<?php echo site_url('products/create'); ?>" class="btn btn-primary mb-2">Tambah Produk</a>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Produk</th>
            <th>Harga Jual</th>
            <th>Stok</th>
            <th>Kategori</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($products as $product): ?>
        <tr>
            <td><?php echo $product->id; ?></td>
            <td><?php echo htmlspecialchars($product->nama_produk); ?></td>
            <td><?php echo number_format($product->harga_jual, 0, ',', '.'); ?></td>
            <td><?php echo $product->stok; ?></td>
            <td><?php echo htmlspecialchars($product->kategori); ?></td>
            <td>
                <a href="<?php echo site_url('products/edit/'.$product->id); ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="<?php echo site_url('products/delete/'.$product->id); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Anda yakin?');">Hapus</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php $this->load->view('templates/footer'); ?>