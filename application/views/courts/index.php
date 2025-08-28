<?php $this->load->view('templates/header'); ?>
<h2>Daftar Lapangan</h2>
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>
<a href="<?php echo site_url('courts/create'); ?>" class="btn btn-primary mb-2">Tambah Lapangan</a>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Lapangan</th>
            <th>Harga per Jam</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($courts as $court): ?>
        <tr>
            <td><?php echo $court->id; ?></td>
            <td><?php echo htmlspecialchars($court->nama_lapangan); ?></td>
            <td><?php echo number_format($court->harga_per_jam, 0, ',', '.'); ?></td>
            <td><?php echo htmlspecialchars($court->status); ?></td>
            <td>
                <a href="<?php echo site_url('courts/edit/'.$court->id); ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="<?php echo site_url('courts/delete/'.$court->id); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Anda yakin?');">Hapus</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php $this->load->view('templates/footer'); ?>