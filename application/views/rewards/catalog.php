<?php $this->load->view('templates/header'); ?>
<h2>Hadiah Poin</h2>
<div class="row">
<?php if (!empty($products)): ?>
    <?php foreach ($products as $p): ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body text-center">
                <h5 class="card-title"><?= htmlspecialchars($p->nama_produk); ?></h5>
                <p class="card-text"><span class="badge badge-primary"><?= (int)$p->poin; ?> Poin</span></p>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="col-12"><div class="alert alert-info">Belum ada hadiah poin tersedia.</div></div>
<?php endif; ?>
</div>
<?php $this->load->view('templates/footer'); ?>

