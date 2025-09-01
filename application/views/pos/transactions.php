<?php $this->load->view('templates/header'); ?>
<h2>Daftar Transaksi POS</h2>
<form method="get" class="mb-3">
    <input type="date" name="start" value="<?php echo set_value('', date('Y-m-d')); ?>">
    <input type="date" name="end" value="<?php echo set_value('', date('Y-m-d')); ?>">
    <button type="submit" class="btn btn-primary btn-sm px-2">Cari</button>
</form>

<?php if ($filter_start && $filter_end): ?>
    <?php if (!empty($sales)): ?>
        <input type="text" id="search" class="form-control form-control-sm mb-2" placeholder="Cari transaksi..." style="max-width:200px;display:inline-block;">
        <div id="search-error" class="text-danger mb-2" style="display:none;">Data tidak ditemukan.</div>
        <table class="table table-bordered table-sm" id="transaction-table">
            <thead>
                <tr>
                    <th>Nota</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($sales as $s): ?>
                <tr>
                    <td><?php echo htmlspecialchars($s->nomor_nota); ?></td>
                    <td><?php echo htmlspecialchars($s->customer_name ?: 'non member'); ?></td>
                    <td>Rp <?php echo number_format($s->total_belanja, 0, ',', '.'); ?></td>
                    <td><?php echo htmlspecialchars($s->tanggal_transaksi); ?></td>
                    <td><a href="<?php echo site_url('pos/reprint/'.$s->id); ?>" class="btn btn-sm btn-secondary" title="Print nota" aria-label="Print nota"><i class="fas fa-print"></i></a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <!-- <th colspan="2" class="text-right">Total Halaman</th> -->
                    <th id="page-total">Rp <?php echo number_format($page_total, 0, ',', '.'); ?></th>
                    <th colspan="2"></th>
                </tr>
            </tfoot>
        </table>
        <div class="d-flex align-items-center">
            <?php if ($total_pages > 1): ?>
            <nav>
                <ul class="pagination mb-0">
                    <?php for ($p = 1; $p <= $total_pages; $p++): ?>
                        <?php $query = http_build_query([
                            'start' => $filter_start,
                            'end'   => $filter_end,
                            'per_page' => $per_page,
                            'page'  => $p
                        ]); ?>
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
                <input type="hidden" name="start" value="<?php echo htmlspecialchars($filter_start); ?>">
                <input type="hidden" name="end" value="<?php echo htmlspecialchars($filter_end); ?>">
                <input type="hidden" name="page" value="1">
            </form>
        </div>
    <?php else: ?>
        <p>Tidak ada transaksi pada rentang tanggal tersebut.</p>
    <?php endif; ?>
<?php else: ?>
    <p>Silakan pilih rentang tanggal untuk melihat transaksi.</p>
<?php endif; ?>
<?php $this->load->view('templates/footer'); ?>
<script>
document.addEventListener('DOMContentLoaded', function(){
    function updateTotal() {
        var rows = document.querySelectorAll('#transaction-table tbody tr');
        var total = 0;
        rows.forEach(function(row){
            if (row.style.display !== 'none') {
                var text = row.cells[2].textContent.replace(/[^0-9]/g, '');
                total += parseInt(text, 10) || 0;
            }
        });
        var cell = document.getElementById('page-total');
        if (cell) {
            cell.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
        }
    }

    var searchInput = document.getElementById('search');
    if (searchInput) {
        searchInput.addEventListener('keyup', function(){
            var filter = this.value.toLowerCase();
            var rows = document.querySelectorAll('#transaction-table tbody tr');
            var any = false;
            rows.forEach(function(row){
                if (row.textContent.toLowerCase().includes(filter)) {
                    row.style.display = '';
                    any = true;
                } else {
                    row.style.display = 'none';
                }
            });
            var error = document.getElementById('search-error');
            if (error) {
                error.style.display = any || filter.length === 0 ? 'none' : 'block';
            }
            updateTotal();
        });
    }

    updateTotal();
});
</script>

