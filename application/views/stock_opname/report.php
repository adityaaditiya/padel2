<?php $this->load->view('templates/header'); ?>
<h2>Laporan Stock Opname</h2>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama Produk</th>
            <th>Stok Sistem</th>
            <th>Jumlah Fisik</th>
            <th>Selisih</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($records as $row): ?>
        <tr>
            <td><?php echo htmlspecialchars($row->nama_produk); ?></td>
            <td><?php echo $row->stok_sistem; ?></td>
            <td><?php echo $row->stok_fisik; ?></td>
            <td><?php echo $row->selisih; ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<a href="<?php echo site_url('stock_opname'); ?>" class="btn btn-secondary">Kembali</a>
<?php $this->load->view('templates/footer'); ?>
