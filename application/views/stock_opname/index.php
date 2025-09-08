<?php $this->load->view('templates/header'); ?>
<h2>Stock Opname</h2>
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>
<form method="post" action="<?php echo site_url('stock_opname/save'); ?>">
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama Produk</th>
            <th>Stok Sistem</th>
            <th>Jumlah Fisik</th>
            <th>Selisih</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product): ?>
        <tr>
            <td><?php echo htmlspecialchars($product->nama_produk); ?></td>
            <td><?php echo $product->stok; ?></td>
            <td>
                <input type="number" name="physical[<?php echo $product->id; ?>]" class="form-control physical-input" data-system="<?php echo $product->stok; ?>" required>
            </td>
            <td class="difference">0</td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<button type="submit" class="btn btn-primary">Simpan</button>
</form>
<script>
document.querySelectorAll('.physical-input').forEach(function(input){
    input.addEventListener('input', function(){
        var system = parseInt(this.getAttribute('data-system'), 10) || 0;
        var physical = parseInt(this.value, 10) || 0;
        var diff = physical - system;
        this.closest('tr').querySelector('.difference').textContent = diff;
    });
});
</script>
<?php $this->load->view('templates/footer'); ?>
