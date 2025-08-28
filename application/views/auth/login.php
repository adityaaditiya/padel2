<?php $this->load->view('templates/header'); ?>
<h2>Login</h2>
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger">
        <?php echo $this->session->flashdata('error'); ?>
    </div>
<?php endif; ?>
<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
<form method="post" action="<?php echo site_url('auth/login'); ?>">
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo set_value('email'); ?>" required>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
    <p class="mt-2">Belum punya akun? <a href="<?php echo site_url('auth/register'); ?>">Daftar di sini</a>.</p>
</form>
<?php $this->load->view('templates/footer'); ?>