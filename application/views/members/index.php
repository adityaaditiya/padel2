<?php $this->load->view('templates/header'); ?>
<h2>Data Member</h2>
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>
<a href="<?php echo site_url('members/create'); ?>" class="btn btn-primary mb-2">Tambah Member</a>
<input type="text" id="memberSearch" class="form-control mb-3 w-auto d-inline-block" style="max-width: 250px;" placeholder="Cari member...">
<small id="searchFeedback" class="form-text text-danger d-none">Member tidak ditemukan</small>
<table id="membersTable" class="table table-bordered">
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
    <?php
        $base_params = ['per_page' => $per_page];
        $max_links  = 5;
        $start_page = max(1, $page - intdiv($max_links, 2));
        $end_page   = min($total_pages, $start_page + $max_links - 1);
        $start_page = max(1, $end_page - $max_links + 1);
    ?>
    <nav>
        <ul class="pagination mb-0">
            <?php if ($page > 1): ?>
                <li class="page-item"><a class="page-link" href="?<?php echo http_build_query($base_params + ['page'=>1]); ?>">First</a></li>
                <li class="page-item"><a class="page-link" href="?<?php echo http_build_query($base_params + ['page'=>$page-1]); ?>">Prev</a></li>
            <?php else: ?>
                <li class="page-item disabled"><span class="page-link">First</span></li>
                <li class="page-item disabled"><span class="page-link">Prev</span></li>
            <?php endif; ?>
            <?php for ($p = $start_page; $p <= $end_page; $p++): ?>
                <li class="page-item <?php echo $p === $page ? 'active' : ''; ?>">
                    <a class="page-link" href="?<?php echo http_build_query($base_params + ['page'=>$p]); ?>"><?php echo $p; ?></a>
                </li>
            <?php endfor; ?>
            <?php if ($page < $total_pages): ?>
                <li class="page-item"><a class="page-link" href="?<?php echo http_build_query($base_params + ['page'=>$page+1]); ?>">Next</a></li>
                <li class="page-item"><a class="page-link" href="?<?php echo http_build_query($base_params + ['page'=>$total_pages]); ?>">Last</a></li>
            <?php else: ?>
                <li class="page-item disabled"><span class="page-link">Next</span></li>
                <li class="page-item disabled"><span class="page-link">Last</span></li>
            <?php endif; ?>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('memberSearch');
    const table = document.getElementById('membersTable');
    const feedback = document.getElementById('searchFeedback');

    searchInput.addEventListener('keyup', function () {
        const filter = searchInput.value.toLowerCase();
        let visibleCount = 0;

        const rows = table.getElementsByTagName('tr');
        for (let i = 1; i < rows.length; i++) {
            const text = rows[i].textContent.toLowerCase();
            const match = text.indexOf(filter) > -1;
            rows[i].style.display = match ? '' : 'none';
            if (match) {
                visibleCount++;
            }
        }

        if (filter && visibleCount === 0) {
            feedback.classList.remove('d-none');
        } else {
            feedback.classList.add('d-none');
        }
    });
});
</script>

<?php $this->load->view('templates/footer'); ?>
