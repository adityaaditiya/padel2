<?php $this->load->view('templates/header'); ?>
<h2>Edit Lapangan</h2>
<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
<?php if (!empty($upload_error)) echo $upload_error; ?>
<form method="post" action="<?php echo site_url('courts/update/'.$court->id); ?>" enctype="multipart/form-data">
    <div class="form-group">
        <label for="nama_lapangan">Nama Lapangan</label>
        <input type="text" name="nama_lapangan" id="nama_lapangan" class="form-control" value="<?php echo set_value('nama_lapangan', $court->nama_lapangan); ?>" required>
    </div>
    <div class="form-group">
        <label for="harga_per_jam">Harga per Jam</label>
        <input type="number" step="0.01" name="harga_per_jam" id="harga_per_jam" class="form-control" value="<?php echo set_value('harga_per_jam', $court->harga_per_jam); ?>" required>
    </div>
    <div class="form-group">
        <label for="gambar">Gambar Lapangan</label>
        <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*" required>
        <?php if (!empty($court->gambar)): ?>
            <img src="<?php echo base_url('uploads/courts/'.$court->gambar); ?>" alt="Gambar Lapangan" class="mt-2" width="100">
        <?php endif; ?>
    </div>
    <div class="form-group">
        <label for="status">Status</label>
        <select name="status" id="status" class="form-control">
            <option value="tersedia" <?php echo $court->status == 'tersedia' ? 'selected' : ''; ?>>Tersedia</option>
            <option value="perbaikan" <?php echo $court->status == 'perbaikan' ? 'selected' : ''; ?>>Perbaikan</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="<?php echo site_url('courts'); ?>" class="btn btn-secondary">Batal</a>
</form>
<?php $this->load->view('templates/footer'); ?>