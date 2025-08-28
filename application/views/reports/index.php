<?php $this->load->view('templates/header'); ?>
<h2>Laporan Bisnis</h2>
<form method="get" class="form-inline mb-3">
    <label for="start_date" class="mr-2">Dari:</label>
    <input type="date" name="start_date" id="start_date" class="form-control mr-2" value="<?php echo htmlspecialchars($start_date); ?>">
    <label for="end_date" class="mr-2">Sampai:</label>
    <input type="date" name="end_date" id="end_date" class="form-control mr-2" value="<?php echo htmlspecialchars($end_date); ?>">
    <button type="submit" class="btn btn-primary">Tampilkan</button>
</form>

<h4>Ringkasan</h4>
<table class="table table-bordered">
    <tr>
        <th>Total Booking</th>
        <td><?php echo $summary['total_bookings']; ?></td>
    </tr>
    <tr>
        <th>Total Pelanggan Unik</th>
        <td><?php echo $summary['total_customers']; ?></td>
    </tr>
</table>

<h4>Produk Terlaris</h4>
<?php if (!empty($summary['best_products'])): ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Terjual</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($summary['best_products'] as $p): ?>
                <tr>
                    <td><?php echo htmlspecialchars($p->nama_produk); ?></td>
                    <td><?php echo $p->qty; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Tidak ada data penjualan pada rentang tanggal ini.</p>
<?php endif; ?>

<?php $this->load->view('templates/footer'); ?>