<?php $this->load->view('templates/header'); ?>
<h2>Daftar Produk</h2>
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>
<?php $role = $this->session->userdata('role'); ?>
<form method="get" class="form-inline mb-3">
    <input type="date" name="start_date" class="form-control mr-2" value="<?php echo html_escape($start_date); ?>">
    <input type="date" name="end_date" class="form-control mr-2" value="<?php echo html_escape($end_date); ?>">
    <select name="kategori" class="form-control mr-2">
        <option value="">Semua Kategori</option>
        <?php foreach ($categories as $cat): ?>
        <option value="<?php echo $cat; ?>" <?php echo ($selected_category === $cat) ? 'selected' : ''; ?>><?php echo ucwords($cat); ?></option>
        <?php endforeach; ?>
    </select>
    <input type="hidden" name="q" value="<?php echo html_escape($search_query); ?>">
    <button type="submit" class="btn btn-secondary">Filter</button>
    <a href="<?php echo site_url('products/create'); ?>" class="btn btn-primary ml-2">Tambah Produk</a>
</form>
<form method="get" class="mb-3" style="max-width:250px;">
    <input type="text" name="q" class="form-control <?php echo ($search_query && empty($products)) ? 'is-invalid' : ''; ?>" placeholder="Cari produk..." value="<?php echo html_escape($search_query); ?>">
    <div class="invalid-feedback">Produk tidak ditemukan</div>
    <input type="hidden" name="start_date" value="<?php echo html_escape($start_date); ?>">
    <input type="hidden" name="end_date" value="<?php echo html_escape($end_date); ?>">
    <input type="hidden" name="kategori" value="<?php echo html_escape($selected_category); ?>">
    <input type="hidden" name="per_page" value="<?php echo $per_page; ?>">
    <input type="hidden" name="page" value="1">
</form>

<table id="productsTable" class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Produk</th>
            <th>Harga Jual</th>
            <th>Stok</th>
            <th>Kategori</th>
            <?php if ($role !== 'kasir'): ?>
                <th>Aksi</th>
            <?php endif; ?>
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
            <?php if ($role !== 'kasir'): ?>
            <td>
                <a href="<?php echo site_url('products/edit/'.$product->id); ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="<?php echo site_url('products/delete/'.$product->id); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Anda yakin?');">Hapus</a>
            </td>
            <?php endif; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>


<?php if ($start_date && $end_date): ?>
    <?php if (!empty($products)): ?>
    <form method="get" class="mb-3" style="max-width:250px;">
        <input type="text" name="q" class="form-control <?php echo ($search_query && empty($products)) ? 'is-invalid' : ''; ?>" placeholder="Cari produk..." value="<?php echo html_escape($search_query); ?>">
        <div class="invalid-feedback">Produk tidak ditemukan</div>
        <input type="hidden" name="start_date" value="<?php echo html_escape($start_date); ?>">
        <input type="hidden" name="end_date" value="<?php echo html_escape($end_date); ?>">
        <input type="hidden" name="kategori" value="<?php echo html_escape($selected_category); ?>">
        <input type="hidden" name="per_page" value="<?php echo $per_page; ?>">
        <input type="hidden" name="page" value="1">
    </form>

    <table id="productsTable" class="table table-bordered">
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

    <div class="d-flex align-items-center mt-3">
        <?php if ($total_pages > 1): ?>
        <?php
            $base_params = [
                'start_date' => $start_date,
                'end_date'   => $end_date,
                'per_page'   => $per_page,
                'q'          => $search_query,
                'kategori'   => $selected_category
            ];
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
        <form method="get" class="form-inline ml-3">
            <label for="per_page" class="mr-2">Per Halaman:</label>
            <select name="per_page" id="per_page" class="form-control mr-2" onchange="this.form.submit()">
                <option value="10" <?php echo $per_page == 10 ? 'selected' : ''; ?>>10</option>
                <option value="25" <?php echo $per_page == 25 ? 'selected' : ''; ?>>25</option>
                <option value="50" <?php echo $per_page == 50 ? 'selected' : ''; ?>>50</option>
                <option value="100" <?php echo $per_page == 100 ? 'selected' : ''; ?>>100</option>
            </select>
            <input type="hidden" name="start_date" value="<?php echo html_escape($start_date); ?>">
            <input type="hidden" name="end_date" value="<?php echo html_escape($end_date); ?>">
            <input type="hidden" name="q" value="<?php echo html_escape($search_query); ?>">
            <input type="hidden" name="kategori" value="<?php echo html_escape($selected_category); ?>">
            <input type="hidden" name="page" value="1">
        </form>
    </div>

    <?php $params = http_build_query(['start_date' => $start_date, 'end_date' => $end_date, 'q' => $search_query, 'kategori' => $selected_category]); ?>
    <a href="<?php echo site_url('products/export_excel?' . $params); ?>" class="btn btn-success mt-2">Export Excel</a>
    <?php else: ?>
        <p>Tidak ada tambah produk di tanggal ini.</p>
    <?php endif; ?>
<?php else: ?>
    <p>Silahkan pilih tanggal tambah produk.</p>
<?php endif; ?>

<?php $this->load->view('templates/footer'); ?>
