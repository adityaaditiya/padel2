<?php $this->load->view('templates/header'); ?>
<h2>Dashboard Pelanggan</h2>
<p>Selamat datang di PadelPro. Gunakan menu Booking untuk melakukan custom jam booking lapangan.</p>

<div class="row">
    <?php if (!empty($courts)): ?>
        <?php foreach ($courts as $court): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="<?php echo base_url('uploads/courts/' . $court->gambar); ?>" class="card-img-top" alt="Gambar Lapangan">
                    <div class="card-body d-flex flex-column">
                        <form method="get" action="<?php echo site_url('booking/create'); ?>" class="d-flex flex-column flex-grow-1">
                            <h5 class="card-title"><?php echo htmlspecialchars($court->nama_lapangan); ?></h5>
                            <p class="card-text">Harga per jam: <?php echo number_format($court->harga_per_jam, 0, ',', '.'); ?></p>
                            <input type="hidden" name="id_court" value="<?php echo $court->id; ?>">
                            <input type="hidden" name="date" value="<?php echo date('Y-m-d'); ?>">
                            <?php if (!empty($court->available_slots)): ?>
                                <p class="card-text">Jam kosong hari ini:</p>
                                <ul class="list-unstyled mb-3 row">
                                    <?php $i = 0; foreach ($court->available_slots as $slot): $i++; ?>
                                        <li class="col-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="slot" id="slot_<?php echo $court->id . '_' . $i; ?>" value="<?php echo $slot['start'] . '-' . $slot['end']; ?>">
                                                <label class="form-check-label" for="slot_<?php echo $court->id . '_' . $i; ?>"><?php echo htmlspecialchars($slot['label']); ?></label>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p class="card-text mb-3">Tidak ada jadwal kosong hari ini.</p>
                            <?php endif; ?>
                            <button type="submit" class="btn btn-primary mt-auto">Booking Sekarang</button>
                        </form>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12"><p>Tidak ada lapangan tersedia.</p></div>
    <?php endif; ?>
</div>

<?php $this->load->view('templates/footer'); ?>

