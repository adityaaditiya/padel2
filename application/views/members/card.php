<?php $this->load->view('templates/header'); ?>
<h2>Kartu Member</h2>
<div class="card mx-auto text-center" style="width:18rem;background:linear-gradient(135deg,#00b4d8,#90e0ef);color:#fff;">
    <img src="<?php echo base_url('uploads/default-profile.svg'); ?>" class="card-img-top p-4" alt="Profile Icon">
    <div class="card-body">
        <h5 class="card-title"><?php echo htmlspecialchars($member->nama_lengkap); ?></h5>
        <p class="card-text mb-1"><?php echo htmlspecialchars($member->kode_member); ?></p>
        <p class="display-4"><?php echo (int) $member->poin; ?><small class="h6"> pts</small></p>
    </div>
</div>
<?php $this->load->view('templates/footer'); ?>
