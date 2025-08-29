<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('store/overlay'); ?>
<h2>Booking Baru</h2>
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>
<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
<form method="post" action="<?php echo site_url('booking/store'); ?>">
    <input type="hidden" name="device_date" id="device_date">
    <div class="form-group">
        <label for="member-name">Member</label>
        <div class="input-group">
            <input type="text" id="member-name" class="form-control" readonly>
            <input type="hidden" name="member_id" id="member-id">
            <div class="input-group-append">
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#memberModal">Pilih</button>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="id_court">Lapangan</label>
        <select name="id_court" id="id_court" class="form-control" required>
            <option value="">-- Pilih Lapangan --</option>
            <?php foreach ($courts as $court): ?>
                <option value="<?php echo $court->id; ?>"><?php echo htmlspecialchars($court->nama_lapangan); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="tanggal_booking">Tanggal</label>
        <input type="date" name="tanggal_booking" id="tanggal_booking" class="form-control" value="<?php echo set_value('tanggal_booking', date('Y-m-d')); ?>" min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d', strtotime('+2 months')); ?>" required>
    </div>
    <div class="form-group">
        <label for="jam_mulai">Jam Mulai</label>
        <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="jam_selesai">Jam Selesai</label>
        <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Simpan Booking</button>
    <a href="<?php echo site_url('booking'); ?>" class="btn btn-secondary">Batal</a>
</form>

<!-- Modal pilih member -->
<div class="modal fade" id="memberModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pilih Member</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="member-type">Pilihan</label>
          <select id="member-type" class="form-control">
            <option value="non">Non Member</option>
            <option value="member">Member</option>
          </select>
        </div>
        <div class="form-group">
          <label for="member-number">Nomor Member</label>
          <input type="text" id="member-number" class="form-control" disabled>
        </div>
        <div class="form-group">
          <label for="modal-member-name">Nama</label>
          <input type="text" id="modal-member-name" class="form-control">
        </div>
        <div class="form-group">
          <label for="modal-member-phone">No Telepon</label>
          <input type="text" id="modal-member-phone" class="form-control">
        </div>
        <div class="form-group">
          <label for="modal-member-address">Alamat</label>
          <textarea id="modal-member-address" class="form-control"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="choose-member">Pilih</button>
      </div>
    </div>
  </div>
</div>

<script>
var now = new Date();
document.getElementById('device_date').value = now.getFullYear() + '-' + ('0' + (now.getMonth() + 1)).slice(-2) + '-' + ('0' + now.getDate()).slice(-2);

var typeSelect = document.getElementById('member-type');
var numberInput = document.getElementById('member-number');
var nameInput = document.getElementById('modal-member-name');
var phoneInput = document.getElementById('modal-member-phone');
var addressInput = document.getElementById('modal-member-address');
var chooseBtn = document.getElementById('choose-member');
var lookupUrl = '<?php echo site_url('booking/member_lookup'); ?>';

if (typeSelect && typeSelect.value === 'non') {
    numberInput.value = 'non member';
    document.getElementById('member-id').value = '';
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
            document.getElementById('member-id').value = '';
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
            document.getElementById('member-id').value = '';
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
                        document.getElementById('member-id').value = m.id;
                        nameInput.value = m.nama_lengkap;
                        phoneInput.value = m.no_telepon || '';
                        addressInput.value = m.alamat || '';
                    } else {
                        document.getElementById('member-id').value = '';
                        nameInput.value = '';
                        phoneInput.value = '';
                        addressInput.value = '';
                    }
                });
        } else {
            document.getElementById('member-id').value = '';
            nameInput.value = '';
            phoneInput.value = '';
            addressInput.value = '';
        }
    });
}

if (chooseBtn) {
    chooseBtn.addEventListener('click', function() {
        document.getElementById('member-name').value = nameInput.value || 'non member';
        $('#memberModal').modal('hide');
    });
}
</script>
<?php $this->load->view('templates/footer'); ?>
