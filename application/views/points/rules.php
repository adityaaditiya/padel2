<?php $this->load->view('templates/header'); ?>
<div class="card" style="background-color:#f0f9ff;">
    <div class="card-body">
        <h2 class="card-title text-center">Perhitungan Poin</h2>
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success text-center"><?= $this->session->flashdata('success'); ?></div>
        <?php endif; ?>
        <?php echo form_open('points'); ?>
            <div class="form-group">
                <label>Rp per 1 poin belanja produk</label>
                <input type="number" name="product_rate" class="form-control" value="<?= isset($rules->product_rate) ? $rules->product_rate : 200; ?>" min="1" required>
            </div>
            <div class="form-group mt-2">
                <label>Rp per 1 poin sewa lapangan</label>
                <input type="number" name="booking_rate" class="form-control" value="<?= isset($rules->booking_rate) ? $rules->booking_rate : 100; ?>" min="1" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Simpan</button>
        <?php echo form_close(); ?>
    </div>
</div>
<?php $this->load->view('templates/footer'); ?>
