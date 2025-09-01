<?php $this->load->view('templates/header'); ?>
<?php $role  = $this->session->userdata('role'); ?>
<?php $sort   = isset($sort) ? $sort : 'jam_mulai'; ?>
<?php $order  = isset($order) ? $order : 'asc'; ?>
<?php $status = isset($status) ? $status : ''; ?>
<?php
function booking_sort_url($field, $date, $status, $sort, $order)
{
    $next = ($sort === $field && $order === 'asc') ? 'desc' : 'asc';
    if ($status === 'pending') {
        return site_url('booking') . '?status=pending&sort=' . $field . '&order=' . $next;
    }
    return site_url('booking') . '?date=' . urlencode($date) . '&sort=' . $field . '&order=' . $next;
}
?>
<h2>Jadwal Booking Lapangan</h2>
<form method="get" class="form-inline mb-3">
    <label for="date" class="mr-2">Tanggal:</label>
    <input type="date" id="date" name="date" class="form-control mr-2" value="<?php echo htmlspecialchars($date); ?>">
    <?php if ($role !== 'pelanggan'): ?>
        <label for="status" class="mr-2">Status:</label>
        <select id="status" name="status" class="form-control mr-2">
            <option value="">Semua</option>
            <option value="pending" <?php echo isset($status) && $status === 'pending' ? 'selected' : ''; ?>>Pending</option>
        </select>
        <button type="submit" class="btn btn-primary">Lihat</button>
    <?php endif; ?>
    <a href="<?php echo site_url('booking/create'); ?>" class="btn btn-success ml-2">Booking Baru</a>
</form>
<input type="text" id="search" class="form-control mb-3" placeholder="Cari booking..." style="width:250px;">

<?php if (!empty($bookings)): ?>
    <table class="table table-bordered" id="booking-table">
        <thead>
            <tr>
                <th><a href="<?php echo htmlspecialchars(booking_sort_url('tanggal_booking', $date, $status, $sort, $order)); ?>">Tanggal</a></th>
                <th><a href="<?php echo htmlspecialchars(booking_sort_url('id_court', $date, $status, $sort, $order)); ?>">Lapangan</a></th>
                <th><a href="<?php echo htmlspecialchars(booking_sort_url('jam_mulai', $date, $status, $sort, $order)); ?>">Jam Mulai</a></th>
                <th><a href="<?php echo htmlspecialchars(booking_sort_url('jam_selesai', $date, $status, $sort, $order)); ?>">Jam Selesai</a></th>
                <th><a href="<?php echo htmlspecialchars(booking_sort_url('kode_member', $date, $status, $sort, $order)); ?>">Kode Member</a></th>
                <th><a href="<?php echo htmlspecialchars(booking_sort_url('status_booking', $date, $status, $sort, $order)); ?>">Status</a></th>
                <th><a href="<?php echo htmlspecialchars(booking_sort_url('keterangan', $date, $status, $sort, $order)); ?>">Keterangan</a></th>
                <?php if ($role === 'kasir'): ?>
                    
                    <th style="width:280px;">Aksi</th>
                    <th>Nota</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($bookings as $b): ?>
            <tr>
                <td><?php echo htmlspecialchars($b->tanggal_booking); ?></td>
                <td><?php echo htmlspecialchars($b->nama_lapangan); ?></td>
                <td><?php echo htmlspecialchars(date('H:i', strtotime($b->jam_mulai))); ?></td>
                <td><?php echo htmlspecialchars(date('H:i', strtotime($b->jam_selesai))); ?></td>
                <td><?php echo htmlspecialchars(!empty($b->kode_member) ? $b->kode_member : 'non member'); ?></td>
                <td><?php echo htmlspecialchars($b->status_booking); ?></td>
                <td><?php echo htmlspecialchars($b->keterangan); ?></td>
                <?php if ($role === 'kasir'): ?>
                    
                    <td style="width:280px;">
                        <?php if ($b->status_booking === 'pending'): ?>
                            <form method="post" action="<?php echo site_url('booking/update_status/' . $b->id); ?>" style="display:inline-block">
                                <input type="hidden" name="status" value="confirmed">
                                <button type="submit" class="btn btn-sm btn-primary">Confirm</button>
                            </form>
                            <form method="post" action="<?php echo site_url('booking/update_status/' . $b->id); ?>" style="display:inline-block">
                                <input type="hidden" name="status" value="batal">
                                <input type="text" name="keterangan" class="form-control form-control-sm mb-1" placeholder="Keterangan" value="<?php echo htmlspecialchars($b->keterangan); ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Batal</button>
                            </form>
                        <?php elseif ($b->status_booking === 'confirmed'): ?>
                            <form method="post" action="<?php echo site_url('booking/update_status/' . $b->id); ?>" style="display:inline-block">
                                <input type="text" name="keterangan" class="form-control form-control-sm mb-1" placeholder="Keterangan" >
                                <button type="submit" name="status" value="selesai" class="btn btn-sm btn-success">Selesai</button>
                                <button type="submit" name="status" value="batal" class="btn btn-sm btn-danger">Batal</button>
                            </form>
                        <?php endif; ?>
                    </td>
                <?php endif; ?>
                <td>
                        <a href="<?php echo site_url('booking/print_receipt/' . $b->id); ?>" class="btn btn-sm btn-secondary">Reprint</a>
                    </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div class="d-flex justify-content-between align-items-center">
        <div>Show
            <select id="rows-per-page" class="custom-select w-auto d-inline-block">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            entries
        </div>
        <nav>
            <ul id="pagination" class="pagination mb-0"></ul>
        </nav>
    </div>
<?php else: ?>
    <p>Tidak ada booking pada tanggal ini.</p>
<?php endif; ?>
<script>
var statusEl = document.getElementById('status');
if (statusEl) {
    statusEl.addEventListener('change', function() {
        document.getElementById('date').disabled = this.value === 'pending';
    });
    statusEl.dispatchEvent(new Event('change'));
}
var table = document.getElementById('booking-table');
if (table) {
    var allRows = Array.from(table.querySelectorAll('tbody tr'));
    var rows = allRows.slice();
    var rowsPerPageSelect = document.getElementById('rows-per-page');
    var pagination = document.getElementById('pagination');

    function renderTable() {
        var rowsPerPage = parseInt(rowsPerPageSelect.value, 10);
        var totalRows = rows.length;
        var pageCount = Math.ceil(totalRows / rowsPerPage) || 1;
        var currentPage = 1;

        function displayPage(page) {
            currentPage = page;
            var start = (page - 1) * rowsPerPage;
            var end = start + rowsPerPage;
            allRows.forEach(function(row) {
                row.style.display = 'none';
            });
            rows.slice(start, end).forEach(function(row) {
                row.style.display = '';
            });
            pagination.innerHTML = '';
            for (var i = 1; i <= pageCount; i++) {
                var li = document.createElement('li');
                li.className = 'page-item' + (i === currentPage ? ' active' : '');
                var a = document.createElement('a');
                a.className = 'page-link';
                a.href = '#';
                a.textContent = i;
                (function(i){
                    a.addEventListener('click', function(e){
                        e.preventDefault();
                        displayPage(i);
                    });
                })(i);
                li.appendChild(a);
                pagination.appendChild(li);
            }
        }

        displayPage(1);
    }

    rowsPerPageSelect.addEventListener('change', renderTable);

    document.getElementById('search').addEventListener('keyup', function() {
        var filter = this.value.toLowerCase();
        rows = allRows.filter(function(row) {
            return row.textContent.toLowerCase().includes(filter);
        });
        renderTable();
    });

    renderTable();
}
</script>
<?php $this->load->view('templates/footer'); ?>
