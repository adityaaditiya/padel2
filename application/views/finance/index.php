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
    <label for="view" class="mr-2">Tampilan:</label>
    <select name="view" id="view" class="form-control mr-2">
        <option value="rekap" <?php echo $view_mode === 'rekap' ? 'selected' : ''; ?>>Rekap</option>
        <option value="detail" <?php echo $view_mode === 'detail' ? 'selected' : ''; ?>>Detail</option>
    </select>
    <button type="submit" class="btn btn-primary">Tampilkan</button>
    <input type="hidden" name="per_page" value="<?php echo $per_page; ?>">
    <input type="hidden" name="page" value="1">
    <input type="hidden" name="q" value="<?php echo htmlspecialchars($search); ?>">
</form>
<form method="get" class="mb-3" style="max-width:250px;">
    <input type="text" name="q" class="form-control <?php echo ($search && empty($report['details'])) ? 'is-invalid' : ''; ?>" placeholder="Cari..." value="<?php echo htmlspecialchars($search); ?>">
    <div class="invalid-feedback">Tidak ada hasil ditemukan.</div>
    <input type="hidden" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
    <input type="hidden" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
    <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
    <input type="hidden" name="view" value="<?php echo htmlspecialchars($view_mode); ?>">
    <input type="hidden" name="per_page" value="<?php echo $per_page; ?>">
    <input type="hidden" name="page" value="1">
</form>
<?php if ($view_mode === 'detail'): ?>
    <?php if ($category === 'product'): ?>
    <table class="table table-bordered" id="financeTable">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nomor Nota</th>
                <th>Nama Member</th>
                <th>Nomor Member</th>
                <th>Nama Produk</th>
                <th>Harga Jual Produk</th>
                <th>Total Harga</th>
                <th>Uang Masuk</th>
                <th>Uang Keluar</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($report['details'])): ?>
            <?php foreach ($report['details'] as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['tanggal']); ?></td>
                <td><?php echo htmlspecialchars($row['nomor_nota']); ?></td>
                <td><?php echo htmlspecialchars($row['nama_member']); ?></td>
                <td><?php echo htmlspecialchars($row['nomor_member']); ?></td>
                <td><?php echo htmlspecialchars($row['nama_produk']); ?></td>
                <td>Rp <?php echo number_format($row['harga_jual'], 0, ',', '.'); ?></td>
                <td>Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                <td>Rp <?php echo number_format($row['uang_masuk'], 0, ',', '.'); ?></td>
                <td>Rp <?php echo number_format($row['uang_keluar'], 0, ',', '.'); ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="9" class="text-center">Tidak ada data</td>
            </tr>
        <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="7">Total</th>
                <th>Rp <?php echo number_format($report['total_masuk'], 0, ',', '.'); ?></th>
                <th>Rp <?php echo number_format($report['total_keluar'], 0, ',', '.'); ?></th>
            </tr>
            <tr>
                <th colspan="7">Saldo</th>
                <th colspan="2">Rp <?php echo number_format($report['saldo'], 0, ',', '.'); ?></th>
            </tr>
        </tfoot>
    </table>
    <?php else: ?>
    <table class="table table-bordered" id="financeTable">
        <thead>
            <tr>
                <th>Kode Booking</th>
                <th>Tanggal Booking</th>
                <th>Nama Member</th>
                <th>Nomor Member</th>
                <th>Poin Dipakai</th>
                <th>Diskon</th>
                <th>Total Harga</th>
                <th>Uang Masuk</th>
                <th>Uang Keluar</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($report['details'])): ?>
            <?php foreach ($report['details'] as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['kode_booking']); ?></td>
                <td><?php echo htmlspecialchars($row['tanggal_booking']); ?></td>
                <td><?php echo htmlspecialchars($row['nama_member']); ?></td>
                <td><?php echo htmlspecialchars($row['nomor_member']); ?></td>
                <td><?php echo (int) $row['poin_dipakai']; ?></td>
                <td>Rp <?php echo number_format($row['diskon'], 0, ',', '.'); ?></td>
                <td>Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                <td>Rp <?php echo number_format($row['uang_masuk'], 0, ',', '.'); ?></td>
                <td>Rp <?php echo number_format($row['uang_keluar'], 0, ',', '.'); ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="9" class="text-center">Tidak ada data</td>
            </tr>
        <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="7">Total</th>
                <th>Rp <?php echo number_format($report['total_masuk'], 0, ',', '.'); ?></th>
                <th>Rp <?php echo number_format($report['total_keluar'], 0, ',', '.'); ?></th>
            </tr>
            <tr>
                <th colspan="7">Saldo</th>
                <th colspan="2">Rp <?php echo number_format($report['saldo'], 0, ',', '.'); ?></th>
            </tr>
        </tfoot>
    </table>
    <?php endif; ?>
<?php else: ?>
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
<?php endif; ?>
<div class="d-flex align-items-center">
    <?php if ($total_pages > 1): ?>
    <?php
        $base_params = [
            'start_date' => $start_date,
            'end_date'   => $end_date,
            'category'   => $category,
            'per_page'   => $per_page,
            'q'          => $search,
            'view'       => $view_mode
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
        <!-- <label for="per_page" class="mr-2">Per Halaman:</label> -->
        <select name="per_page" id="per_page" class="form-control mr-2" onchange="this.form.submit()">
            <option value="10" <?php echo $per_page == 10 ? 'selected' : ''; ?>>10</option>
            <option value="25" <?php echo $per_page == 25 ? 'selected' : ''; ?>>25</option>
            <option value="50" <?php echo $per_page == 50 ? 'selected' : ''; ?>>50</option>
            <option value="100" <?php echo $per_page == 100 ? 'selected' : ''; ?>>100</option>
        </select>
        <input type="hidden" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
        <input type="hidden" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
        <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
        <input type="hidden" name="view" value="<?php echo htmlspecialchars($view_mode); ?>">
        <input type="hidden" name="q" value="<?php echo htmlspecialchars($search); ?>">
        <input type="hidden" name="page" value="1">
    </form>
</div>
<div class="mt-3">
    <button id="exportPdf" class="btn btn-secondary">Export PDF</button>
    <button id="exportExcel" class="btn btn-success ml-2">Export Excel</button>
</div>

<?php if ($view_mode === 'detail'): ?>
    <?php if ($category === 'product'): ?>
    <table id="allFinanceTable" style="display:none;">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nomor Nota</th>
                <th>Nama Member</th>
                <th>Nomor Member</th>
                <th>Nama Produk</th>
                <th>Harga Jual Produk</th>
                <th>Total Harga</th>
                <th>Uang Masuk</th>
                <th>Uang Keluar</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($all_details as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['tanggal']); ?></td>
                <td><?php echo htmlspecialchars($row['nomor_nota']); ?></td>
                <td><?php echo htmlspecialchars($row['nama_member']); ?></td>
                <td><?php echo htmlspecialchars($row['nomor_member']); ?></td>
                <td><?php echo htmlspecialchars($row['nama_produk']); ?></td>
                <td>Rp <?php echo number_format($row['harga_jual'], 0, ',', '.'); ?></td>
                <td>Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                <td>Rp <?php echo number_format($row['uang_masuk'], 0, ',', '.'); ?></td>
                <td>Rp <?php echo number_format($row['uang_keluar'], 0, ',', '.'); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="7">Total</th>
                <th>Rp <?php echo number_format($report['total_masuk'], 0, ',', '.'); ?></th>
                <th>Rp <?php echo number_format($report['total_keluar'], 0, ',', '.'); ?></th>
            </tr>
            <tr>
                <th colspan="7">Saldo</th>
                <th colspan="2">Rp <?php echo number_format($report['saldo'], 0, ',', '.'); ?></th>
            </tr>
        </tfoot>
    </table>
    <?php else: ?>
    <table id="allFinanceTable" style="display:none;">
        <thead>
            <tr>
                <th>Kode Booking</th>
                <th>Tanggal Booking</th>
                <th>Nama Member</th>
                <th>Nomor Member</th>
                <th>Poin Dipakai</th>
                <th>Diskon</th>
                <th>Total Harga</th>
                <th>Uang Masuk</th>
                <th>Uang Keluar</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($all_details as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['kode_booking']); ?></td>
                <td><?php echo htmlspecialchars($row['tanggal_booking']); ?></td>
                <td><?php echo htmlspecialchars($row['nama_member']); ?></td>
                <td><?php echo htmlspecialchars($row['nomor_member']); ?></td>
                <td><?php echo (int) $row['poin_dipakai']; ?></td>
                <td>Rp <?php echo number_format($row['diskon'], 0, ',', '.'); ?></td>
                <td>Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                <td>Rp <?php echo number_format($row['uang_masuk'], 0, ',', '.'); ?></td>
                <td>Rp <?php echo number_format($row['uang_keluar'], 0, ',', '.'); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="7">Total</th>
                <th>Rp <?php echo number_format($report['total_masuk'], 0, ',', '.'); ?></th>
                <th>Rp <?php echo number_format($report['total_keluar'], 0, ',', '.'); ?></th>
            </tr>
            <tr>
                <th colspan="7">Saldo</th>
                <th colspan="2">Rp <?php echo number_format($report['saldo'], 0, ',', '.'); ?></th>
            </tr>
        </tfoot>
    </table>
    <?php endif; ?>
<?php else: ?>
    <table id="allFinanceTable" style="display:none;">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Uang Masuk</th>
                <th>Uang Keluar</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($all_details as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['tanggal']); ?></td>
                <td><?php echo htmlspecialchars($row['keterangan']); ?></td>
                <td>Rp <?php echo number_format($row['uang_masuk'], 0, ',', '.'); ?></td>
                <td>Rp <?php echo number_format($row['uang_keluar'], 0, ',', '.'); ?></td>
            </tr>
        <?php endforeach; ?>
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
<?php endif; ?>

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
    doc.autoTable({ html: '#allFinanceTable', startY: 30, showFoot: 'lastPage' });
    doc.save('laporan_keuangan.pdf');
});

document.getElementById('exportExcel').addEventListener('click', function () {
    const table = document.getElementById('allFinanceTable');
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

