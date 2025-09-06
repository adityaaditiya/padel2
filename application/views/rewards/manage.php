<?php $this->load->view('templates/header'); ?>
<h2>Kelola Produk Penukaran</h2>
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
<?php endif; ?>
<a href="<?= site_url('rewards/create'); ?>" class="btn btn-primary mb-3">Tambah Produk</a>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Produk</th>
            <th>Poin</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p->nama_produk); ?></td>
                <td><?= (int) $p->poin; ?></td>
                <td><?= (int) $p->stok; ?></td>
                <td>
                    <a href="<?= site_url('rewards/edit/'.$p->id); ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="<?= site_url('rewards/delete/'.$p->id); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus produk?');">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php $this->load->view('templates/footer'); ?>
