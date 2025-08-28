<?php $this->load->view('templates/header'); ?>
<?php $role  = $this->session->userdata('role'); ?>
<?php $sort  = isset($sort) ? $sort : 'jam_mulai'; ?>
<?php $order = isset($order) ? $order : 'asc'; ?>
<?php
function booking_sort_url($field, $date, $sort, $order)
{
    $next = ($sort === $field && $order === 'asc') ? 'desc' : 'asc';
    return site_url('booking') . '?date=' . urlencode($date) . '&sort=' . $field . '&order=' . $next;
}
?>
<h2>Jadwal Booking Lapangan</h2>
<form method="get" class="form-inline mb-3">
    <label for="date" class="mr-2">Tanggal:</label>
    <input type="date" id="date" name="date" class="form-control mr-2" value="<?php echo htmlspecialchars($date); ?>">
    <button type="submit" class="btn btn-primary">Lihat</button>
    <a href="<?php echo site_url('booking/create'); ?>" class="btn btn-success ml-2">Booking Baru</a>
</form>

<?php if (!empty($bookings)): ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th><a href="<?php echo htmlspecialchars(booking_sort_url('id_court', $date, $sort, $order)); ?>">Lapangan</a></th>
                <th><a href="<?php echo htmlspecialchars(booking_sort_url('kode_member', $date, $sort, $order)); ?>">Kode Member</a></th>
                <th><a href="<?php echo htmlspecialchars(booking_sort_url('jam_mulai', $date, $sort, $order)); ?>">Jam Mulai</a></th>
                <th><a href="<?php echo htmlspecialchars(booking_sort_url('jam_selesai', $date, $sort, $order)); ?>">Jam Selesai</a></th>
                <th><a href="<?php echo htmlspecialchars(booking_sort_url('status_booking', $date, $sort, $order)); ?>">Status</a></th>
                <th><a href="<?php echo htmlspecialchars(booking_sort_url('keterangan', $date, $sort, $order)); ?>">Keterangan</a></th>
                <?php if ($role === 'kasir'): ?>
                    <th>Aksi</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($bookings as $b): ?>
            <tr>
                <td><?php echo htmlspecialchars($b->id_court); ?></td>
                <td><?php echo htmlspecialchars($b->kode_member); ?></td>
                <td><?php echo htmlspecialchars($b->jam_mulai); ?></td>
                <td><?php echo htmlspecialchars($b->jam_selesai); ?></td>
                <td><?php echo htmlspecialchars($b->status_booking); ?></td>
                <td><?php echo htmlspecialchars($b->keterangan); ?></td>
                <?php if ($role === 'kasir'): ?>
                    <td>
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
                                <input type="text" name="keterangan" class="form-control form-control-sm mb-1" placeholder="Keterangan" value="<?php echo htmlspecialchars($b->keterangan); ?>">
                                <button type="submit" name="status" value="selesai" class="btn btn-sm btn-success">Selesai</button>
                                <button type="submit" name="status" value="batal" class="btn btn-sm btn-danger">Batal</button>
                            </form>
                        <?php endif; ?>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Tidak ada booking pada tanggal ini.</p>
<?php endif; ?>
<?php $this->load->view('templates/footer'); ?>
