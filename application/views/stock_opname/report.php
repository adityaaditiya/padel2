<?php $this->load->view('templates/header'); ?>
<h2>Laporan Stock Opname</h2>
<table id="stockOpnameReportTable" class="table table-bordered">
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

<div class="mt-3">
    <button id="exportPdf" class="btn btn-secondary">Export PDF</button>
    <button id="exportExcel" class="btn btn-success ml-2">Export Excel</button>
</div>

<a href="<?php echo site_url('stock_opname'); ?>" class="btn btn-secondary mt-3">Kembali</a>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
document.getElementById('exportPdf').addEventListener('click', function () {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    doc.text('Laporan Stock Opname', 14, 15);
    doc.autoTable({ html: '#stockOpnameReportTable', startY: 20 });
    doc.save('laporan_stock_opname.pdf');
});

document.getElementById('exportExcel').addEventListener('click', function () {
    const table = document.getElementById('stockOpnameReportTable');
    const wb = XLSX.utils.book_new();
    const ws = XLSX.utils.table_to_sheet(table);
    XLSX.utils.book_append_sheet(wb, ws, 'Stock Opname');
    XLSX.writeFile(wb, 'laporan_stock_opname.xlsx');
});
</script>

<?php $this->load->view('templates/footer'); ?>
