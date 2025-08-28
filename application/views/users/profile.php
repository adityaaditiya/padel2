<?php $this->load->view('templates/header'); ?>
<h1><?php echo !empty($editing_self) ? 'Edit Profil' : 'Edit User'; ?></h1>
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>
<?php echo form_open(); ?>
<div class="form-group">
    <label>Nama Lengkap</label>
    <input type="text" name="nama_lengkap" class="form-control" value="<?php echo set_value('nama_lengkap', $user->nama_lengkap); ?>">
</div>
<div class="form-group">
    <label>Email</label>
    <input type="email" name="email" class="form-control" value="<?php echo set_value('email', $user->email); ?>">
</div>
<div class="form-group">
    <label>No Telepon</label>
    <input type="text" name="no_telepon" class="form-control" value="<?php echo set_value('no_telepon', $user->no_telepon); ?>">
</div>
<div class="form-group">
    <label>Password (kosongkan jika tidak ganti)</label>
    <input type="password" name="password" class="form-control">
</div>
<?php if ($this->session->userdata('role') === 'owner'): ?>
<div class="form-group">
    <label>Role</label>
    <select name="role" class="form-control">
        <?php $roles = ['pelanggan' => 'Pelanggan', 'kasir' => 'Kasir', 'admin_keuangan' => 'Admin Keuangan', 'owner' => 'Owner']; ?>
        <?php foreach ($roles as $key => $label): ?>
            <option value="<?php echo $key; ?>" <?php echo set_select('role', $key, $user->role === $key); ?>><?php echo $label; ?></option>
        <?php endforeach; ?>
    </select>
</div>
<?php endif; ?>
<button type="submit" class="btn btn-primary">Simpan</button>
<?php echo form_close(); ?>
<?php $this->load->view('templates/footer'); ?>
