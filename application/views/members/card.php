<?php $this->load->view('templates/header'); ?>
<h2>Member Card</h2>
<div class="card mx-auto" style="width: 18rem;">
    <img src="https://via.placeholder.com/150?text=Profile" class="card-img-top" alt="Profile Icon">
    <div class="card-body text-center">
        <h5 class="card-title"><?php echo htmlspecialchars($member->nama_lengkap); ?></h5>
        <p class="card-text"><?php echo htmlspecialchars($member->kode_member); ?></p>
    </div>
</div>
<?php $this->load->view('templates/footer'); ?>
