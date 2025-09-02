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
            <th>Bukti Pembayaran</th>
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
            <td>
                <?php if (!empty($b->bukti_pembayaran)): ?>
                    <a href="#" class="preview-bukti" data-image="<?php echo base_url('uploads/payment_proofs/' . $b->bukti_pembayaran); ?>" title="Lihat bukti" aria-label="Lihat bukti"><i class="fas fa-eye"></i></a>
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
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

