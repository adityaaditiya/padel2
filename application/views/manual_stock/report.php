<?php $this->load->view('templates/header'); ?>
<h2>Laporan Stok Masuk/Keluar</h2>
<form class="form-inline mb-3" method="get">
    <label class="mr-2" for="start">Dari</label>
    <input type="date" name="start" id="start" value="<?php echo htmlspecialchars($filter_start); ?>" class="form-control mr-2">
    <label class="mr-2" for="end">Sampai</label>
    <input type="date" name="end" id="end" value="<?php echo htmlspecialchars($filter_end); ?>" class="form-control mr-2">
    <input type="text" name="q" placeholder="Cari produk" value="<?php echo htmlspecialchars($search_query); ?>" class="form-control mr-2">
    <button type="submit" class="btn btn-primary">Filter</button>
</form>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Nama Produk</th>
            <th>Jenis Transaksi</th>
            <th>Jumlah</th>
            <th>Keterangan</th>
            <th>Total Stok</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($records as $r): ?>
        <tr>
            <td><?php echo $r->created_at; ?></td>
            <td><?php echo htmlspecialchars($r->nama_produk); ?></td>
            <td><?php echo ucfirst($r->type); ?></td>
            <td><?php echo $r->quantity; ?></td>
            <td><?php echo htmlspecialchars($r->note); ?></td>
            <td><?php echo $r->total_stock; ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<a href="<?php echo site_url('manual_stock'); ?>" class="btn btn-secondary mt-3">Kembali</a>
<?php $this->load->view('templates/footer'); ?>
