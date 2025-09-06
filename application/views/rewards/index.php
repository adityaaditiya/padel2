<?php $this->load->view('templates/header'); ?>
<h2>Penukaran Poin</h2>
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
<?php endif; ?>
<?php if ($role === 'owner'): ?>
    <a href="<?= site_url('rewards/create'); ?>" class="btn btn-primary mb-3">Tambah Produk</a>
<?php endif; ?>
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
                    <?php if ($role === 'pelanggan' && $p->stok > 0): ?>
                        <a href="<?= site_url('rewards/redeem/'.$p->id); ?>" class="btn btn-success btn-sm">Tukar</a>
                    <?php endif; ?>
                    <?php if ($role === 'owner'): ?>
                        <a href="<?= site_url('rewards/delete/'.$p->id); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus produk?');">Hapus</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php $this->load->view('templates/footer'); ?>
