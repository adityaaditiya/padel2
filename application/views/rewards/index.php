<?php $this->load->view('templates/header'); ?>
<h2>Penukaran Poin</h2>
<div id="message"></div>
<div class="row mb-4">
    <div class="col-md-8">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="kode_member">Kode Member</label>
                <input type="text" id="kode_member" class="form-control" placeholder="0000000001">
            </div>
            <div class="form-group col-md-8">
                <label for="nama_member">Nama Member</label>
                <input type="text" id="nama_member" class="form-control" readonly>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-8">
                <label for="alamat">Alamat</label>
                <input type="text" id="alamat" class="form-control" readonly>
            </div>
            <div class="form-group col-md-4">
                <label for="no_hp">No Hp</label>
                <input type="text" id="no_hp" class="form-control" readonly>
            </div>
        </div>
    </div>
    <div class="col-md-4 text-center">
        <h4>Sisa Poin</h4>
        <div id="sisaPoin" class="display-4 text-success">0</div>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-6">
        <input type="text" id="search" class="form-control" placeholder="Cari hadiah...">
    </div>
</div>
<table class="table table-bordered" id="rewardTable">
    <thead>
        <tr>
            <th>Nama Produk</th>
            <th>Poin</th>
            <th>Stock</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $p): ?>
        <tr data-name="<?= strtolower(htmlspecialchars($p->nama_produk)); ?>">
            <td><?= htmlspecialchars($p->nama_produk); ?></td>
            <td class="poin"><?= (int)$p->poin; ?></td>
            <td class="stok"><?= (int)$p->stok; ?></td>
            <td><button class="btn btn-primary btn-sm redeem" data-id="<?= $p->id; ?>"><i class="fas fa-exchange-alt"></i></button></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
(function(){
    function loadMember(kode){
        if(!kode) return;
        fetch('<?= site_url('rewards/member_lookup'); ?>', {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: 'kode_member=' + encodeURIComponent(kode)
        }).then(r => r.json()).then(function(res){
            if(res.status === 'ok'){
                document.getElementById('nama_member').value = res.member.nama_lengkap;
                document.getElementById('alamat').value = res.member.alamat;
                document.getElementById('no_hp').value = res.member.no_telepon;
                document.getElementById('sisaPoin').textContent = res.member.poin;
            }else{
                document.getElementById('nama_member').value = '';
                document.getElementById('alamat').value = '';
                document.getElementById('no_hp').value = '';
                document.getElementById('sisaPoin').textContent = '0';
                alert(res.message);
            }
        });
    }
    var kodeInput = document.getElementById('kode_member');
    kodeInput.addEventListener('keydown', function(e){
        if(e.key === 'Enter' || e.key === 'Tab'){
            e.preventDefault();
            loadMember(kodeInput.value);
        }
    });
    document.getElementById('search').addEventListener('keyup', function(){
        var q = this.value.toLowerCase();
        document.querySelectorAll('#rewardTable tbody tr').forEach(function(row){
            var name = row.getAttribute('data-name');
            row.style.display = name.indexOf(q) !== -1 ? '' : 'none';
        });
    });
    document.querySelectorAll('#rewardTable .redeem').forEach(function(btn){
        btn.addEventListener('click', function(){
            var kode = kodeInput.value;
            if(!kode){
                alert('Isi kode member terlebih dahulu.');
                return;
            }
            var id = this.getAttribute('data-id');
            fetch('<?= site_url('rewards/redeem'); ?>/' + id, {
                method: 'POST',
                headers: {'Content-Type':'application/x-www-form-urlencoded'},
                body: 'kode_member=' + encodeURIComponent(kode)
            }).then(r => r.json()).then(function(res){
                var msgDiv = document.getElementById('message');
                if(res.status === 'ok'){
                    document.getElementById('sisaPoin').textContent = res.poin;
                    var row = document.querySelector('#rewardTable button[data-id="'+id+'"]').closest('tr');
                    row.querySelector('.stok').textContent = res.stok;
                    msgDiv.innerHTML = '<div class="alert alert-success">Penukaran berhasil!</div>';
                }else{
                    msgDiv.innerHTML = '<div class="alert alert-danger">'+res.message+'</div>';
                }
            });
        });
    });
})();
</script>
<?php $this->load->view('templates/footer'); ?>
