<?php $this->load->view('templates/header'); ?>
<?php $role  = $this->session->userdata('role'); ?>
<?php $sort        = isset($sort) ? $sort : 'jam_mulai'; ?>
<?php $order       = isset($order) ? $order : 'asc'; ?>
<?php $status      = isset($status) ? $status : ''; ?>
<?php $start_date  = isset($start_date) ? $start_date : date('Y-m-d'); ?>
<?php $end_date    = isset($end_date) ? $end_date : $start_date; ?>
<?php $start_time  = isset($start_time) ? $start_time : ''; ?>
<?php $end_time    = isset($end_time) ? $end_time : ''; ?>
<?php
function booking_sort_url($field, $start, $end, $start_time, $end_time, $status, $sort, $order)
{
    $next = ($sort === $field && $order === 'asc') ? 'desc' : 'asc';
    if ($status === 'pending') {
        return site_url('booking') . '?status=pending&sort=' . $field . '&order=' . $next;
    }
    $url = site_url('booking') . '?start_date=' . urlencode($start) . '&end_date=' . urlencode($end);
    if (!empty($start_time)) {
        $url .= '&jam_mulai=' . urlencode($start_time);
    }
    if (!empty($end_time)) {
        $url .= '&jam_selesai=' . urlencode($end_time);
    }
    $url .= '&sort=' . $field . '&order=' . $next;
    return $url;
}
?>
<h2>Jadwal Booking Lapangan</h2>
<form method="get" class="form-inline mb-3">
    <label for="start_date" class="mr-2">Dari:</label>
    <input type="date" id="start_date" name="start_date" class="form-control mr-2" value="<?php echo htmlspecialchars($start_date); ?>">
    <label for="end_date" class="mr-2">Sampai:</label>
    <input type="date" id="end_date" name="end_date" class="form-control mr-2" value="<?php echo htmlspecialchars($end_date); ?>">
    <label for="jam_mulai" class="mr-2">Jam Mulai:</label>
    <input type="time" id="jam_mulai" name="jam_mulai" class="form-control mr-2" min="08:00" max="22:00" value="<?php echo htmlspecialchars($start_time); ?>">
    <label for="jam_selesai" class="mr-2">Jam Selesai:</label>
    <input type="time" id="jam_selesai" name="jam_selesai" class="form-control mr-2" min="08:00" max="22:00" value="<?php echo htmlspecialchars($end_time); ?>">
    <?php if ($role !== 'pelanggan'): ?>
        <label for="status" class="mr-2">Status:</label>
        <select id="status" name="status" class="form-control mr-2">
            <option value="">Semua</option>
            <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>Pending</option>
        </select>
    <?php endif; ?>
    <button type="submit" class="btn btn-primary">Lihat</button>
    <a href="<?php echo site_url('booking/create'); ?>" class="btn btn-success ml-2">Booking Baru</a>
</form>
<input type="text" id="search" class="form-control mb-3" placeholder="Cari booking..." style="width:250px;">

<?php if (!empty($bookings)): ?>
    <table class="table table-bordered" id="booking-table">
        <thead>
            <tr>
                <th><a href="<?php echo htmlspecialchars(booking_sort_url('tanggal_booking', $start_date, $end_date, $start_time, $end_time, $status, $sort, $order)); ?>">Tanggal</a></th>
                <th><a href="<?php echo htmlspecialchars(booking_sort_url('id_court', $start_date, $end_date, $start_time, $end_time, $status, $sort, $order)); ?>">Lapangan</a></th>
                <th><a href="<?php echo htmlspecialchars(booking_sort_url('jam_mulai', $start_date, $end_date, $start_time, $end_time, $status, $sort, $order)); ?>">Jam Mulai</a></th>
                <th><a href="<?php echo htmlspecialchars(booking_sort_url('jam_selesai', $start_date, $end_date, $start_time, $end_time, $status, $sort, $order)); ?>">Jam Selesai</a></th>
                <th><a href="<?php echo htmlspecialchars(booking_sort_url('kode_member', $start_date, $end_date, $start_time, $end_time, $status, $sort, $order)); ?>">Kode Member</a></th>
                <th><a href="<?php echo htmlspecialchars(booking_sort_url('status_booking', $start_date, $end_date, $start_time, $end_time, $status, $sort, $order)); ?>">Status</a></th>
                <th><a href="<?php echo htmlspecialchars(booking_sort_url('keterangan', $start_date, $end_date, $start_time, $end_time, $status, $sort, $order)); ?>">Keterangan</a></th>
                <?php if ($role === 'kasir'): ?>
                    <th style="width:280px;">Aksi</th>
                    <th>Nota</th>
                    <th>Bukti Pembayaran</th>
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
                    <td>
                        <a href="<?php echo site_url('booking/print_receipt/' . $b->id); ?>" class="btn btn-sm btn-secondary" title="Print nota" aria-label="Print nota"><i class="fas fa-print"></i></a>
                    </td>
                    <td>
                        <?php if (!empty($b->bukti_pembayaran)): ?>
                            <a href="#" class="preview-bukti" data-image="<?php echo base_url('uploads/payment_proofs/' . $b->bukti_pembayaran); ?>" title="Lihat bukti" aria-label="Lihat bukti"><i class="fas fa-eye"></i></a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                <?php endif; ?>
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
    <p>Tidak ada booking pada rentang tanggal ini.</p>
<?php endif; ?>
<script>
var statusEl = document.getElementById('status');
if (statusEl) {
    statusEl.addEventListener('change', function() {
        var disabled = this.value === 'pending';
        document.getElementById('start_date').disabled = disabled;
        document.getElementById('end_date').disabled = disabled;
        var startTimeEl = document.getElementById('jam_mulai');
        var endTimeEl   = document.getElementById('jam_selesai');
        if (startTimeEl) startTimeEl.disabled = disabled;
        if (endTimeEl) endTimeEl.disabled = disabled;
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

            var maxLinks = 5;
            var startPage = Math.max(1, currentPage - Math.floor(maxLinks / 2));
            var endPage = Math.min(pageCount, startPage + maxLinks - 1);
            startPage = Math.max(1, endPage - maxLinks + 1);

            function createItem(label, targetPage, disabled) {
                var li = document.createElement('li');
                li.className = 'page-item' + (disabled ? ' disabled' : '');
                var a = document.createElement('a');
                a.className = 'page-link';
                a.href = '#';
                a.textContent = label;
                if (!disabled) {
                    a.addEventListener('click', function(e){
                        e.preventDefault();
                        displayPage(targetPage);
                    });
                }
                li.appendChild(a);
                pagination.appendChild(li);
            }

            createItem('First', 1, currentPage === 1);
            createItem('Prev', currentPage - 1, currentPage === 1);

            for (var i = startPage; i <= endPage; i++) {
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

            createItem('Next', currentPage + 1, currentPage === pageCount);
            createItem('Last', pageCount, currentPage === pageCount);
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
<div class="modal fade" id="buktiModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img src="" alt="Bukti Pembayaran" class="img-fluid">
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var links = document.querySelectorAll('.preview-bukti');
    links.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var imgSrc = this.getAttribute('data-image');
            var modalImg = document.querySelector('#buktiModal img');
            modalImg.src = imgSrc;
            $('#buktiModal').modal('show');
        });
    });
});
</script>
<?php $this->load->view('templates/footer'); ?>
