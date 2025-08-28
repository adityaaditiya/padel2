<?php $this->load->view('templates/header'); ?>
<h2>Tanggal Toko</h2>
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>
<?php $role = $this->session->userdata('role'); ?>
<?php if ($store && $store->is_open): ?>
    <?php $next_date = date('Y-m-d', strtotime($store->store_date . ' +1 day')); ?>
    <p>Toko dibuka pada tanggal: <strong><?php echo $store->store_date; ?></strong></p>
    <form method="post" action="<?php echo site_url('store/close'); ?>">
        <button type="submit" class="btn btn-danger" onclick="return confirm('Tutup toko pada tanggal <?php echo $store->store_date; ?>? Tanggal berikutnya: <?php echo $next_date; ?>');">Tutup Toko</button>
    </form>
<?php else: ?>
    <?php $open_date = $store ? $store->store_date : date('Y-m-d'); ?>
    <form method="post" action="<?php echo site_url('store/open'); ?>" <?php if ($role === 'owner'): ?>onsubmit="return confirm('Buka toko untuk tanggal ' + document.getElementById('store_date').value + '?');"<?php else: ?>onsubmit="return confirm('Buka toko pada tanggal <?php echo $open_date; ?>?');"<?php endif; ?>>
        <?php if ($role === 'owner'): ?>
        <div class="form-group">
            <label for="store_date">Tanggal Toko</label>
            <input type="date" name="store_date" id="store_date" class="form-control" value="<?php echo $open_date; ?>" required>
        </div>
        <?php endif; ?>
        <button type="submit" class="btn btn-primary">Buka Toko</button>
    </form>
<?php endif; ?>
<?php $this->load->view('templates/footer'); ?>
