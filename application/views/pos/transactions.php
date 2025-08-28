<?php $this->load->view('templates/header'); ?>
<h2>Daftar Transaksi POS</h2>
<form method="get" class="mb-3">
    <input type="date" name="start" value="<?php echo htmlspecialchars($filter_start); ?>">
    <input type="date" name="end" value="<?php echo htmlspecialchars($filter_end); ?>">
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
                </tr>
            </thead>
            <tbody>
            <?php foreach ($sales as $s): ?>
                <tr>
                    <td><?php echo htmlspecialchars($s->nomor_nota); ?></td>
                    <td><?php echo htmlspecialchars($s->customer_name); ?></td>
                    <td>Rp <?php echo number_format($s->total_belanja, 0, ',', '.'); ?></td>
                    <td><?php echo htmlspecialchars($s->tanggal_transaksi); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Tidak ada transaksi pada rentang tanggal tersebut.</p>
    <?php endif; ?>
<?php else: ?>
    <p>Silakan pilih rentang tanggal untuk melihat transaksi.</p>
<?php endif; ?>
<?php $this->load->view('templates/footer'); ?>
<script>
document.addEventListener('DOMContentLoaded', function(){
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
        });
    }
});
</script>

