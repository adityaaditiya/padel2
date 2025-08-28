<?php $this->load->view('templates/header'); ?>
<h2>Edit Lapangan</h2>
<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
<form method="post" action="<?php echo site_url('courts/update/'.$court->id); ?>">
    <div class="form-group">
        <label for="nama_lapangan">Nama Lapangan</label>
        <input type="text" name="nama_lapangan" id="nama_lapangan" class="form-control" value="<?php echo set_value('nama_lapangan', $court->nama_lapangan); ?>" required>
    </div>
    <div class="form-group">
        <label for="harga_per_jam">Harga per Jam</label>
        <input type="number" step="0.01" name="harga_per_jam" id="harga_per_jam" class="form-control" value="<?php echo set_value('harga_per_jam', $court->harga_per_jam); ?>" required>
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