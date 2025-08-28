<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('store/overlay'); ?>
<h2>Tambah Uang Kas</h2>
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>
<form method="post">
    <input type="hidden" name="device_date" id="device_date">
    <div class="form-group">
        <label for="category">Kategori</label>
        <select name="category" id="category" class="form-control">
            <option value="BON OPERASIONAL">BON OPERASIONAL</option>
            <option value="BON TRANSFER BANK">BON TRANSFER BANK</option>
            <option value="DEBIT CREDIT CARD">DEBIT CREDIT CARD</option>
            <option value="MODAL">MODAL</option>
        </select>
    </div>
    <div class="form-group">
        <label for="amount">Nominal</label>
        <input type="number" step="0.01" name="amount" id="amount" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="note">Keterangan</label>
        <input type="text" name="note" id="note" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
</form>
<script>
var now = new Date();
document.getElementById('device_date').value = now.getFullYear() + '-' + ('0' + (now.getMonth() + 1)).slice(-2) + '-' + ('0' + now.getDate()).slice(-2);
</script>
<?php $this->load->view('templates/footer'); ?>

