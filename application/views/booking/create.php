<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('store/overlay'); ?>
<h2>Booking Baru</h2>
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>
<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
<form method="post" action="<?php echo site_url('booking/store'); ?>">
    <input type="hidden" name="device_date" id="device_date">
    <div class="form-group">
        <label for="id_court">Lapangan</label>
        <select name="id_court" id="id_court" class="form-control" required>
            <option value="">-- Pilih Lapangan --</option>
            <?php foreach ($courts as $court): ?>
                <option value="<?php echo $court->id; ?>"><?php echo htmlspecialchars($court->nama_lapangan); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="tanggal_booking">Tanggal</label>
        <input type="date" name="tanggal_booking" id="tanggal_booking" class="form-control" value="<?php echo set_value('tanggal_booking', date('Y-m-d')); ?>" min="<?php echo date('Y-m-d'); ?>" required>
    </div>
    <div class="form-group">
        <label for="jam_mulai">Jam Mulai</label>
        <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="jam_selesai">Jam Selesai</label>
        <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Simpan Booking</button>
    <a href="<?php echo site_url('booking'); ?>" class="btn btn-secondary">Batal</a>
</form>
<script>
var now = new Date();
document.getElementById('device_date').value = now.getFullYear() + '-' + ('0' + (now.getMonth() + 1)).slice(-2) + '-' + ('0' + now.getDate()).slice(-2);
</script>
<?php $this->load->view('templates/footer'); ?>
