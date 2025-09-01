<?php $this->load->view('templates/header'); ?>
<h2>Daftar Produk</h2>
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>
<a href="<?php echo site_url('products/create'); ?>" class="btn btn-primary mb-2">Tambah Produk</a>
<form method="get" class="form-inline mb-3">
    <input type="date" name="start_date" class="form-control mr-2" value="<?php echo html_escape($start_date); ?>">
    <input type="date" name="end_date" class="form-control mr-2" value="<?php echo html_escape($end_date); ?>">
    <button type="submit" class="btn btn-secondary">Filter</button>
</form>

<input type="text" id="productSearch" class="form-control mb-3 w-auto d-inline-block" style="max-width: 250px;" placeholder="Cari produk...">
<small id="searchFeedback" class="form-text text-danger d-none">Produk tidak ditemukan</small>

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

<div class="d-flex justify-content-between align-items-center mt-3">
    <div class="btn-group" role="group" aria-label="Per halaman">
        <?php foreach ([10,25,50,100] as $size): ?>
            <a href="<?php echo site_url('products?' . http_build_query([
                'start_date' => $start_date,
                'end_date'   => $end_date,
                'per_page'   => $size
            ])); ?>" class="btn btn-outline-secondary<?php echo ($per_page == $size) ? ' active' : ''; ?>"><?php echo $size; ?></a>
        <?php endforeach; ?>
    </div>
    <div>
        <?php echo $this->pagination->create_links(); ?>
    </div>
</div>

<?php $params = http_build_query(['start_date' => $start_date, 'end_date' => $end_date]); ?>
<a href="<?php echo site_url('products/export_excel?' . $params); ?>" class="btn btn-success mt-2">Export Excel</a>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('productSearch');
    const table = document.getElementById('productsTable');
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
