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
<div class="d-flex align-items-center">
    <?php if ($total_pages > 1): ?>
    <nav>
        <ul class="pagination mb-0">
            <?php for ($p = 1; $p <= $total_pages; $p++): ?>
                <?php $query = http_build_query(['per_page' => $per_page, 'page' => $p]); ?>
                <li class="page-item <?php echo $p === $page ? 'active' : ''; ?>">
                    <a class="page-link" href="?<?php echo $query; ?>"><?php echo $p; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php endif; ?>
    <form method="get" class="form-inline ml-3" id="perPageForm">
        <label for="per_page" class="mr-2">Per Halaman:</label>
        <select name="per_page" id="per_page" class="form-control mr-2" onchange="this.form.submit()">
            <option value="10" <?php echo $per_page == 10 ? 'selected' : ''; ?>>10</option>
            <option value="25" <?php echo $per_page == 25 ? 'selected' : ''; ?>>25</option>
            <option value="50" <?php echo $per_page == 50 ? 'selected' : ''; ?>>50</option>
            <option value="100" <?php echo $per_page == 100 ? 'selected' : ''; ?>>100</option>
        </select>
        <input type="hidden" name="page" value="1">
    </form>
</div>

<?php $this->load->view('templates/footer'); ?>
