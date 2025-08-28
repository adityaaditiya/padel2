<?php $this->load->view('templates/header'); ?>
<h2>Data Member</h2>
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>
<a href="<?php echo site_url('members/create'); ?>" class="btn btn-primary mb-3">Tambah Member</a>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Kode Member</th>
            <th>Nama</th>
            <th>Email</th>
            <th>No Telepon</th>
            <th>Alamat</th>
            <th>Kecamatan</th>
            <th>Kota</th>
            <th>Provinsi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($members as $m): ?>
            <tr>
                <td><?php echo htmlspecialchars($m->kode_member); ?></td>
                <td><?php echo htmlspecialchars($m->nama_lengkap); ?></td>
                <td><?php echo htmlspecialchars($m->email); ?></td>
                <td><?php echo htmlspecialchars($m->no_telepon); ?></td>
                <td><?php echo htmlspecialchars($m->alamat); ?></td>
                <td><?php echo htmlspecialchars($m->kecamatan); ?></td>
                <td><?php echo htmlspecialchars($m->kota); ?></td>
                <td><?php echo htmlspecialchars($m->provinsi); ?></td>
                <td><a href="<?php echo site_url('members/edit/'.$m->id); ?>" class="btn btn-sm btn-warning">Edit</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php $this->load->view('templates/footer'); ?>
