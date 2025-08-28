<?php $this->load->view('templates/header'); ?>
<h2>Laporan Keuangan</h2>
<form method="get" class="form-inline mb-3">
    <label for="start_date" class="mr-2">Dari:</label>
    <input type="date" name="start_date" id="start_date" class="form-control mr-2" value="<?php echo htmlspecialchars($start_date); ?>">
    <label for="end_date" class="mr-2">Sampai:</label>
    <input type="date" name="end_date" id="end_date" class="form-control mr-2" value="<?php echo htmlspecialchars($end_date); ?>">
    <label for="category" class="mr-2">Kategori:</label>
    <select name="category" id="category" class="form-control mr-2">
        <option value="semua" <?php echo $category === 'semua' ? 'selected' : ''; ?>>Semua</option>
        <option value="booking" <?php echo $category === 'booking' ? 'selected' : ''; ?>>Booking</option>
        <option value="batal" <?php echo $category === 'batal' ? 'selected' : ''; ?>>Batal Booking</option>
        <option value="product" <?php echo $category === 'product' ? 'selected' : ''; ?>>Penjualan Produk</option>
        <option value="cash_in" <?php echo $category === 'cash_in' ? 'selected' : ''; ?>>Tambah Uang Kas</option>
        <option value="cash_out" <?php echo $category === 'cash_out' ? 'selected' : ''; ?>>Ambil Uang Kas</option>
    </select>
    <button type="submit" class="btn btn-primary">Tampilkan</button>
</form>
<div class="form-group mb-3" style="max-width: 250px;">
    <input type="text" id="search" class="form-control" placeholder="Cari...">
    <div class="invalid-feedback">Tidak ada hasil ditemukan.</div>
</div>
<table class="table table-bordered" id="financeTable">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Keterangan</th>
            <th>Uang Masuk</th>
            <th>Uang Keluar</th>
        </tr>
    </thead>
    <tbody>
    <?php if (!empty($report['details'])): ?>
        <?php foreach ($report['details'] as $row): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['tanggal']); ?></td>
            <td><?php echo htmlspecialchars($row['keterangan']); ?></td>
            <td>Rp <?php echo number_format($row['uang_masuk'], 0, ',', '.'); ?></td>
            <td>Rp <?php echo number_format($row['uang_keluar'], 0, ',', '.'); ?></td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="4" class="text-center">Tidak ada data</td>
        </tr>
    <?php endif; ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2">Total</th>
            <th>Rp <?php echo number_format($report['total_masuk'], 0, ',', '.'); ?></th>
            <th>Rp <?php echo number_format($report['total_keluar'], 0, ',', '.'); ?></th>
        </tr>
        <tr>
            <th colspan="2">Saldo</th>
            <th colspan="2">Rp <?php echo number_format($report['saldo'], 0, ',', '.'); ?></th>
        </tr>
    </tfoot>
</table>

<div class="mt-3">
    <button id="exportPdf" class="btn btn-secondary">Export PDF</button>
    <button id="exportExcel" class="btn btn-success ml-2">Export Excel</button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const tableBody = document.getElementById('financeTable').getElementsByTagName('tbody')[0];
    const rows = tableBody.getElementsByTagName('tr');

    const noSearchRow = document.createElement('tr');
    noSearchRow.id = 'noSearchResults';
    noSearchRow.innerHTML = '<td colspan="4" class="text-center">Tidak ada hasil</td>';
    noSearchRow.style.display = 'none';
    tableBody.appendChild(noSearchRow);

    searchInput.addEventListener('input', function() {
        const filter = this.value.toLowerCase();
        let visible = 0;
        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            if (row.id === 'noSearchResults') continue;
            const text = row.textContent.toLowerCase();
            if (text.includes(filter)) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        }
        if (visible === 0 && filter !== '') {
            noSearchRow.style.display = '';
            searchInput.classList.add('is-invalid');
        } else {
            noSearchRow.style.display = 'none';
            searchInput.classList.remove('is-invalid');
        }
    });
});
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
document.getElementById('exportPdf').addEventListener('click', function () {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    const title = document.querySelector('h2').innerText.trim();
    const start = document.getElementById('start_date').value;
    const end = document.getElementById('end_date').value;
    doc.text(title, 14, 15);
    doc.text(`Periode: ${start} s/d ${end}`, 14, 25);
    doc.autoTable({ html: '#financeTable', startY: 30 });
    doc.save('laporan_keuangan.pdf');
});

document.getElementById('exportExcel').addEventListener('click', function () {
    const table = document.getElementById('financeTable');
    const wb = XLSX.utils.book_new();
    const tableData = XLSX.utils.sheet_to_json(XLSX.utils.table_to_sheet(table), { header: 1 });
    const title = document.querySelector('h2').innerText.trim();
    const start = document.getElementById('start_date').value;
    const end = document.getElementById('end_date').value;
    const wsData = [];
    wsData.push([title]);
    wsData.push([`Periode: ${start} s/d ${end}`]);
    wsData.push([]);
    wsData.push(...tableData);
    const ws = XLSX.utils.aoa_to_sheet(wsData);
    XLSX.utils.book_append_sheet(wb, ws, 'Laporan');
    XLSX.writeFile(wb, 'laporan_keuangan.xlsx');
});
</script>

<?php $this->load->view('templates/footer'); ?>

