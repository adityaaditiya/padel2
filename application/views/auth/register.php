<?php $this->load->view('templates/header'); ?>
<h2>Registrasi Pelanggan</h2>
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success">
        <?php echo $this->session->flashdata('success'); ?>
    </div>
<?php endif; ?>
<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
<form method="post" action="<?php echo site_url('auth/register'); ?>">
    <div class="form-group">
        <label for="nama_lengkap">Nama Lengkap</label>
        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?php echo set_value('nama_lengkap'); ?>" required>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo set_value('email'); ?>" required>
    </div>
    <div class="form-group">
        <label for="no_telepon">No. Telepon</label>
        <input type="text" class="form-control" id="no_telepon" name="no_telepon" value="<?php echo set_value('no_telepon'); ?>">
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div class="form-group">
        <label for="password_confirm">Konfirmasi Password</label>
        <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
    </div>
    <button type="submit" class="btn btn-primary">Daftar</button>
    <p class="mt-2">Sudah punya akun? <a href="<?php echo site_url('auth/login'); ?>">Login di sini</a>.</p>
</form>
<?php $this->load->view('templates/footer'); ?>