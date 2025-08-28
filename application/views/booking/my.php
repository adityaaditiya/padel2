<?php $this->load->view('templates/header'); ?>
<h2>Booking Saya</h2>
<a href="<?php echo site_url('booking/create'); ?>" class="btn btn-success mb-3">Booking Baru</a>
<?php if (empty($bookings)): ?>
    <p>Belum ada booking.</p>
<?php else: ?>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Lapangan</th>
            <th>Jam Mulai</th>
            <th>Jam Selesai</th>
            <th>Status</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($bookings as $b): ?>
        <tr>
            <td><?php echo htmlspecialchars($b->tanggal_booking); ?></td>
            <td><?php echo htmlspecialchars($b->nama_lapangan); ?></td>
            <td><?php echo htmlspecialchars($b->jam_mulai); ?></td>
            <td><?php echo htmlspecialchars($b->jam_selesai); ?></td>
            <td><?php echo htmlspecialchars(ucfirst($b->status_booking)); ?></td>
            <td><?php echo htmlspecialchars($b->keterangan); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
<?php $this->load->view('templates/footer'); ?>

