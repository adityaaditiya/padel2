<?php $this->load->view('templates/header'); ?>
<h2>Dashboard Pelanggan</h2>
<p>Selamat datang di PadelPro. Gunakan menu untuk melakukan booking lapangan.</p>

<div class="row">
    <?php if (!empty($courts)): ?>
        <?php foreach ($courts as $court): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="<?php echo base_url('uploads/courts/' . $court->gambar); ?>" class="card-img-top" alt="Gambar Lapangan">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo htmlspecialchars($court->nama_lapangan); ?></h5>
                        <p class="card-text mb-4">Harga per jam: <?php echo number_format($court->harga_per_jam, 0, ',', '.'); ?></p>
                        <a href="<?php echo site_url('booking'); ?>" class="btn btn-primary mt-auto">Lihat Jadwal Booking</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12"><p>Tidak ada lapangan tersedia.</p></div>
    <?php endif; ?>
</div>

<?php $this->load->view('templates/footer'); ?>