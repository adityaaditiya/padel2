<?php $this->load->view('templates/header'); ?>
<h2>Laporan Tukar Poin</h2>
<form method="get" class="form-inline mb-3">
    <label for="start_date" class="mr-2">Dari:</label>
    <input type="date" name="start_date" id="start_date" class="form-control mr-2" value="<?php echo htmlspecialchars($start_date); ?>">
    <label for="end_date" class="mr-2">Sampai:</label>
    <input type="date" name="end_date" id="end_date" class="form-control mr-2" value="<?php echo htmlspecialchars($end_date); ?>">
    <button type="submit" class="btn btn-primary">Tampilkan</button>
    <input type="hidden" name="per_page" value="<?php echo $per_page; ?>">
    <input type="hidden" name="page" value="1">
    <input type="hidden" name="q" value="<?php echo htmlspecialchars($search); ?>">
</form>
<form method="get" class="mb-3" style="max-width:250px;">
    <input type="text" name="q" class="form-control <?php echo ($search && empty($details)) ? 'is-invalid' : ''; ?>" placeholder="Cari..." value="<?php echo htmlspecialchars($search); ?>">
    <div class="invalid-feedback">Tidak ada hasil ditemukan.</div>
    <input type="hidden" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
    <input type="hidden" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
    <input type="hidden" name="per_page" value="<?php echo $per_page; ?>">
    <input type="hidden" name="page" value="1">
</form>
<table class="table table-bordered" id="pointTable">
    <thead>
        <tr>
            <th>Kode Member</th>
            <th>Tanggal Transaksi</th>
            <th>Barang Tukar</th>
            <th>Point Awal</th>
            <th>Harga Point</th>
            <th>Point Akhir</th>
        </tr>
    </thead>
    <tbody>
    <?php if (!empty($details)): ?>
        <?php foreach ($details as $row): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['kode_member']); ?></td>
            <td><?php echo htmlspecialchars($row['tanggal']); ?></td>
            <td><?php echo htmlspecialchars($row['barang_tukar']); ?></td>
            <td><?php echo (int) $row['point_awal']; ?></td>
            <td><?php echo (int) $row['harga_point']; ?></td>
            <td><?php echo (int) $row['point_akhir']; ?></td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="6" class="text-center">Tidak ada data</td>
        </tr>
    <?php endif; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6">Total Daftar: <?php echo $total_rows; ?></td>
        </tr>
    </tfoot>
</table>
<div class="d-flex align-items-center">
    <?php if ($total_pages > 1): ?>
    <?php
        $base_params = [
            'start_date' => $start_date,
            'end_date'   => $end_date,
            'per_page'   => $per_page,
            'q'          => $search
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
    <form method="get" class="form-inline ml-3" id="perPageForm">
        <select name="per_page" id="per_page" class="form-control mr-2" onchange="this.form.submit()">
            <option value="10" <?php echo $per_page == 10 ? 'selected' : ''; ?>>10</option>
            <option value="25" <?php echo $per_page == 25 ? 'selected' : ''; ?>>25</option>
            <option value="50" <?php echo $per_page == 50 ? 'selected' : ''; ?>>50</option>
            <option value="100" <?php echo $per_page == 100 ? 'selected' : ''; ?>>100</option>
        </select>
        <input type="hidden" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
        <input type="hidden" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
        <input type="hidden" name="q" value="<?php echo htmlspecialchars($search); ?>">
        <input type="hidden" name="page" value="1">
    </form>
</div>
<div class="mt-3">
    <button id="exportPdf" class="btn btn-secondary">Export PDF</button>
    <button id="exportExcel" class="btn btn-success ml-2">Export Excel</button>
</div>
<table id="allPointTable" style="display:none;">
    <thead>
        <tr>
            <th>Kode Member</th>
            <th>Tanggal Transaksi</th>
            <th>Barang Tukar</th>
            <th>Point Awal</th>
            <th>Harga Point</th>
            <th>Point Akhir</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($all_details as $row): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['kode_member']); ?></td>
            <td><?php echo htmlspecialchars($row['tanggal']); ?></td>
            <td><?php echo htmlspecialchars($row['barang_tukar']); ?></td>
            <td><?php echo (int) $row['point_awal']; ?></td>
            <td><?php echo (int) $row['harga_point']; ?></td>
            <td><?php echo (int) $row['point_akhir']; ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6">Total Daftar: <?php echo $total_rows; ?></td>
        </tr>
    </tfoot>
</table>
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
        doc.autoTable({ html: '#allPointTable', startY: 30, showFoot: 'lastPage' });
        doc.save('laporan_tukar_poin.pdf');
    });

    document.getElementById('exportExcel').addEventListener('click', function () {
        const table = document.getElementById('allPointTable');
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
        XLSX.writeFile(wb, 'laporan_tukar_poin.xlsx');
    });
</script>
<?php $this->load->view('templates/footer'); ?>

