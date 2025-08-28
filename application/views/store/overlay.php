<div id="store-block" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.75);color:#fff;z-index:9999;align-items:center;justify-content:center;text-align:center;">
    <div>
        <p id="store-block-message" style="font-size:1.5em;"></p>
        <?php if ($this->session->userdata('role') !== 'pelanggan'): ?>
            <a href="<?php echo site_url('store'); ?>" class="btn btn-light mt-3">Pengaturan Tanggal Toko</a>
        <?php endif; ?>
    </div>
</div>
<script>
(function(){
    var storeDate = '<?php echo isset($store->store_date) ? $store->store_date : ''; ?>';
    var isOpen = <?php echo isset($store->is_open) && $store->is_open ? 'true' : 'false'; ?>;
    var now = new Date();
    var deviceDate = now.getFullYear() + '-' + ('0' + (now.getMonth() + 1)).slice(-2) + '-' + ('0' + now.getDate()).slice(-2);
    var message = '';
    if (!isOpen) {
        message = 'Toko belum dibuka';
    } else if (storeDate < deviceDate) {
        message = 'Toko belum ditutup';
    } else if (storeDate !== deviceDate) {
        message = 'Tanggal perangkat tidak sesuai dengan tanggal toko';
    }
    if (message) {
        var overlay = document.getElementById('store-block');
        document.getElementById('store-block-message').innerText = message;
        overlay.style.display = 'flex';
    }
})();
</script>
