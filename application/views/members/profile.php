<?php $this->load->view('templates/header'); ?>
<h2>Data Member</h2>
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>
<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
<form method="post" action="<?php echo site_url('members/update_profile'); ?>">
    <div class="form-group">
        <label for="nama_lengkap">Nama Lengkap</label>
        <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" value="<?php echo set_value('nama_lengkap', $member->nama_lengkap); ?>" required>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control" value="<?php echo set_value('email', $member->email); ?>" required>
    </div>
    <div class="form-group">
        <label for="no_telepon">No Telepon</label>
        <input type="text" name="no_telepon" id="no_telepon" class="form-control" value="<?php echo set_value('no_telepon', $member->no_telepon); ?>" required>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" class="form-control" placeholder="Kosongkan jika tidak diubah">
    </div>
    <div class="form-group">
        <label for="kode_member">Kode Member</label>
        <input type="text" id="kode_member" class="form-control" value="<?php echo $member->kode_member; ?>" readonly>
    </div>
    <div class="form-group">
        <label for="alamat">Alamat / Jalan</label>
        <input type="text" name="alamat" id="alamat" class="form-control" value="<?php echo set_value('alamat', $member->alamat); ?>" required>
    </div>
    <div class="form-group">
        <label for="kecamatan">Kecamatan</label>
        <input type="text" name="kecamatan" id="kecamatan" class="form-control" value="<?php echo set_value('kecamatan', $member->kecamatan); ?>" required>
    </div>
    <div class="form-group">
        <label for="kota">Kota</label>
        <input type="text" name="kota" id="kota" class="form-control" value="<?php echo set_value('kota', $member->kota); ?>" required>
    </div>
    <div class="form-group">
        <label for="provinsi">Provinsi</label>
        <input type="text" name="provinsi" id="provinsi" class="form-control" value="<?php echo set_value('provinsi', $member->provinsi); ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
</form>
<?php $this->load->view('templates/footer'); ?>
