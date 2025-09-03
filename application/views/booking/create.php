<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('store/overlay'); ?>
<h2>Booking Baru</h2>
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>
<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
<form method="post" action="<?php echo site_url('booking/store'); ?>" enctype="multipart/form-data">
    <input type="hidden" name="device_date" id="device_date">
    <?php if ($this->session->userdata('role') === 'kasir'): ?>
    <input type="hidden" name="customer_id" id="customer-id">
    <div class="form-group">
        <label for="customer-type">Tipe Customer</label>
        <select name="customer_type" id="customer-type" class="form-control">
            <option value="member">Member</option>
            <option value="non">Non Member</option>
        </select>
    </div>
    <div class="form-group">
        <label for="member-number">Nomor Member</label>
        <input type="text" name="member_number" id="member-number" class="form-control">
    </div>
    <div class="form-group">
        <label for="customer-name">Nama</label>
        <input type="text" name="customer_name" id="customer-name" class="form-control" readonly>
    </div>
    <div class="form-group">
        <label for="customer-phone">No Telepon</label>
        <input type="text" name="customer_phone" id="customer-phone" class="form-control" readonly>
    </div>
    <div class="form-group">
        <label for="customer-address">Alamat</label>
        <textarea name="customer_address" id="customer-address" class="form-control" readonly></textarea>
    </div>
    <?php endif; ?>
    <div class="form-group">
        <label for="id_court">Lapangan</label>
        <select name="id_court" id="id_court" class="form-control" required>
            <option value="">-- Pilih Lapangan --</option>
            <?php foreach ($courts as $court): ?>
                <option value="<?php echo $court->id; ?>" data-price="<?php echo $court->harga_per_jam; ?>" <?php echo set_select('id_court', $court->id, isset($selected_court) && (int)$selected_court === (int)$court->id); ?>><?php echo htmlspecialchars($court->nama_lapangan); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="tanggal_booking">Tanggal</label>
        <input type="date" name="tanggal_booking" id="tanggal_booking" class="form-control" value="<?php echo set_value('tanggal_booking', isset($selected_date) ? $selected_date : date('Y-m-d')); ?>" min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d', strtotime('+2 months')); ?>" required>
    </div>
    <div class="form-group">
        <label for="jam_mulai">Jam Mulai</label>
        <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" value="<?php echo set_value('jam_mulai', isset($selected_start) ? $selected_start : ''); ?>" required>
    </div>
    <div class="form-group">
        <label for="jam_selesai">Jam Selesai</label>
        <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" value="<?php echo set_value('jam_selesai', isset($selected_end) ? $selected_end : ''); ?>" required>
    </div>
<?php if ($this->session->userdata('role') === 'pelanggan'): ?>
    <div class="form-group">
        <label for="harga-booking">Total Harga</label>
        <input type="text" id="harga-booking" class="form-control" readonly>
    </div>
    <div class="form-group">
        <label for="bukti_pembayaran">Bukti Pembayaran</label>
        <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="form-control" accept="image/*" required>
        <small class="form-text text-muted">Pembayaran Sesuai Tagihan, Tanpa Biaya Tambahan</small>
    </div>
<?php endif; ?>
    <?php if ($this->session->userdata('role') === 'kasir'): ?>
    <div class="form-group">
        <label for="harga-booking">Harga Booking</label>
        <input type="text" id="harga-booking" class="form-control" readonly>
    </div>
    <div class="form-group">
        <label for="diskon-persen">Diskon (%)</label>
        <input type="number" name="diskon_persen" id="diskon-persen" class="form-control" min="0" max="100" step="0.01">
    </div>
    <div class="form-group">
        <label for="diskon-rupiah">Diskon (Rp)</label>
        <input type="number" name="diskon_rupiah" id="diskon-rupiah" class="form-control" min="0" step="100">
    </div>
    <div class="form-group">
        <label for="total-bayar">Total Harga</label>
        <input type="text" id="total-bayar" class="form-control" readonly>
    </div>
    <?php endif; ?>
    <button type="submit" class="btn btn-primary">Simpan Booking</button>
    <a href="<?php echo site_url('booking'); ?>" class="btn btn-secondary">Batal</a>
</form>
<script>
var now = new Date();
document.getElementById('device_date').value = now.getFullYear() + '-' + ('0' + (now.getMonth() + 1)).slice(-2) + '-' + ('0' + now.getDate()).slice(-2);
<?php if ($this->session->userdata('role') === 'kasir'): ?>
var typeSelect = document.getElementById('customer-type');
var numberInput = document.getElementById('member-number');
var nameInput = document.getElementById('customer-name');
var phoneInput = document.getElementById('customer-phone');
var addressInput = document.getElementById('customer-address');
var customerIdInput = document.getElementById('customer-id');
var lookupUrl = '<?php echo site_url('pos/member_lookup'); ?>';
if (typeSelect && typeSelect.value === 'non') {
    numberInput.value = 'non member';
    numberInput.disabled = true;
    nameInput.readOnly = false;
    phoneInput.readOnly = false;
    addressInput.readOnly = false;
}
if (typeSelect) {
    typeSelect.addEventListener('change', function() {
        if (this.value === 'member') {
            numberInput.disabled = false;
            numberInput.value = '';
            nameInput.readOnly = true;
            phoneInput.readOnly = true;
            addressInput.readOnly = true;
            nameInput.value = '';
            phoneInput.value = '';
            addressInput.value = '';
            if (customerIdInput) customerIdInput.value = '';
            numberInput.focus();
        } else {
            numberInput.value = 'non member';
            numberInput.disabled = true;
            nameInput.readOnly = false;
            phoneInput.readOnly = false;
            addressInput.readOnly = false;
            nameInput.value = '';
            phoneInput.value = '';
            addressInput.value = '';
            if (customerIdInput) customerIdInput.value = '';
        }
    });
}
if (numberInput) {
    numberInput.addEventListener('keyup', function() {
        var kode = this.value;
        if (kode.length > 0) {
            fetch(lookupUrl + '?kode=' + encodeURIComponent(kode))
                .then(function(r){ return r.json(); })
                .then(function(m){
                    if (m) {
                        if (customerIdInput) customerIdInput.value = m.id;
                        nameInput.value = m.nama_lengkap;
                        phoneInput.value = m.no_telepon || '';
                        addressInput.value = m.alamat || '';
                    } else {
                        if (customerIdInput) customerIdInput.value = '';
                        nameInput.value = '';
                        phoneInput.value = '';
                        addressInput.value = '';
                    }
                });
        } else {
            if (customerIdInput) customerIdInput.value = '';
            nameInput.value = '';
            phoneInput.value = '';
            addressInput.value = '';
        }
    });
}
<?php endif; ?>
var courtSelect = document.getElementById('id_court');
var startInput = document.getElementById('jam_mulai');
var endInput = document.getElementById('jam_selesai');
var hargaBookingInput = document.getElementById('harga-booking');
var diskonPersenInput = document.getElementById('diskon-persen');
var diskonRupiahInput = document.getElementById('diskon-rupiah');
var totalBayarInput = document.getElementById('total-bayar');
function timeToMinutes(t){var p=t.split(':');return p.length===2?parseInt(p[0],10)*60+parseInt(p[1],10):0;}
function updatePayment(e){
    var pricePerHour = 0;
    if (courtSelect) {
        var opt = courtSelect.options[courtSelect.selectedIndex];
        pricePerHour = parseFloat(opt.getAttribute('data-price')) || 0;
    }
    var start = startInput.value;
    var end = endInput.value;
var durasi = 0;
if (start && end) {
    durasi = timeToMinutes(end) - timeToMinutes(start);
    if (durasi < 0) durasi = 0;
}
var harga = pricePerHour * (durasi / 60);
    if (hargaBookingInput) hargaBookingInput.value = harga ? harga.toFixed(0) : '';
    var discPercent = diskonPersenInput ? parseFloat(diskonPersenInput.value) || 0 : 0;
    var discAmount = diskonRupiahInput ? parseFloat(diskonRupiahInput.value) || 0 : 0;
    if (diskonPersenInput && e && e.target === diskonPersenInput) {
        discAmount = harga * (discPercent / 100);
        if (diskonRupiahInput) diskonRupiahInput.value = discAmount ? discAmount.toFixed(0) : '';
    } else if (diskonRupiahInput && e && e.target === diskonRupiahInput) {
        discPercent = harga ? (discAmount / harga) * 100 : 0;
        if (diskonPersenInput) diskonPersenInput.value = discPercent ? discPercent.toFixed(2) : '';
    } else {
        if (diskonPersenInput && discPercent) {
            discAmount = harga * (discPercent / 100);
            if (diskonRupiahInput) diskonRupiahInput.value = discAmount ? discAmount.toFixed(0) : '';
        } else if (diskonRupiahInput && discAmount) {
            discPercent = harga ? (discAmount / harga) * 100 : 0;
            if (diskonPersenInput) diskonPersenInput.value = discPercent ? discPercent.toFixed(2) : '';
        }
    }
    var total = harga - discAmount;
    if (totalBayarInput) totalBayarInput.value = total > 0 ? total.toFixed(0) : '0';
}
if (courtSelect) courtSelect.addEventListener('change', updatePayment);
if (startInput) startInput.addEventListener('change', updatePayment);
if (endInput) endInput.addEventListener('change', updatePayment);
<?php if ($this->session->userdata('role') === 'kasir'): ?>
if (diskonPersenInput) diskonPersenInput.addEventListener('input', updatePayment);
if (diskonRupiahInput) diskonRupiahInput.addEventListener('input', updatePayment);
<?php endif; ?>
updatePayment();
</script>
<?php $this->load->view('templates/footer'); ?>
