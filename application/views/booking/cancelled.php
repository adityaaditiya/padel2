<?php $this->load->view('templates/header'); ?>
<h2>Booking Batal</h2>
<form method="get" action="<?php echo site_url('booking/cancelled'); ?>" class="form-inline mb-3">
    <label for="date" class="mr-2">Tanggal:</label>
    <input type="date" id="date" name="date" class="form-control mr-2" value="<?php echo htmlspecialchars($date); ?>">
    <button type="submit" class="btn btn-primary">Lihat</button>
</form>

<?php if (!empty($bookings)): ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Lapangan</th>
                <th>Pelanggan</th>
                <th>Jam Mulai</th>
                <th>Jam Selesai</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($bookings as $b): ?>
            <tr>
                <td><?php echo htmlspecialchars($b->id_court); ?></td>
                <td><?php echo htmlspecialchars($b->id_user); ?></td>
                <td><?php echo htmlspecialchars($b->jam_mulai); ?></td>
                <td><?php echo htmlspecialchars($b->jam_selesai); ?></td>
                <td><?php echo htmlspecialchars($b->keterangan); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php elseif ($date): ?>
    <p>Tidak ada booking batal pada tanggal ini.</p>
<?php else: ?>
    <p>Silakan pilih tanggal.</p>
<?php endif; ?>
<?php $this->load->view('templates/footer'); ?>
