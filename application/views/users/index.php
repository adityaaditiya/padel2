<?php $this->load->view('templates/header'); ?>
<h1>Daftar Pengguna</h1>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $u): ?>
        <tr>
            <td><?php echo htmlspecialchars($u->nama_lengkap); ?></td>
            <td><?php echo htmlspecialchars($u->email); ?></td>
            <td><?php echo htmlspecialchars($u->role); ?></td>
            <td><a href="<?php echo site_url('users/edit/'.$u->id); ?>" class="btn btn-sm btn-primary">Edit</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php $this->load->view('templates/footer'); ?>
