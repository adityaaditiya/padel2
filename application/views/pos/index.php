<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('store/overlay'); ?>
<h2>Point of Sale</h2>
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-md-6">
        <h4>Daftar Produk</h4>
        <form class="form-inline mb-2" method="get" id="filter-form">
            <select name="kategori" id="category-filter" class="form-control mr-2">
                <option value="">Semua Kategori</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?php echo $c; ?>" <?php echo ($selected_category == $c) ? 'selected' : ''; ?>><?php echo htmlspecialchars($c); ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="q" id="product-search" value="<?php echo htmlspecialchars($search_query); ?>" class="form-control mr-2" placeholder="Cari produk">
            <button type="submit" class="btn btn-primary btn-sm">Cari</button>
        </form>
        <div class="row" id="products-grid">
        <?php foreach ($products as $p): ?>
            <div class="col-6 col-md-4 col-lg-3 mb-3">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title mb-1"><?php echo htmlspecialchars($p->nama_produk); ?></h6>
                        <p class="mb-1">Harga: Rp <?php echo number_format($p->harga_jual, 0, ',', '.'); ?></p>
                        <p class="mb-2">Kategori: <?php echo htmlspecialchars($p->kategori); ?></p>
                        <div class="input-group input-group-sm mt-auto">
                            <input type="number" value="1" min="1" class="form-control product-qty" data-id="<?php echo $p->id; ?>">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-success add-to-cart" data-id="<?php echo $p->id; ?>">Tambah</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
        <?php if ($total_pages > 1): ?>
        <nav>
            <ul class="pagination">
                <?php $query = ''; if ($selected_category) $query .= '&kategori=' . urlencode($selected_category); if ($search_query) $query .= '&q=' . urlencode($search_query); ?>
                <?php if ($page > 1): ?>
                    <li class="page-item"><a class="page-link" href="<?php echo site_url('pos?page=' . ($page - 1) . $query); ?>">&laquo;</a></li>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>"><a class="page-link" href="<?php echo site_url('pos?page=' . $i . $query); ?>"><?php echo $i; ?></a></li>
                <?php endfor; ?>
                <?php if ($page < $total_pages): ?>
                    <li class="page-item"><a class="page-link" href="<?php echo site_url('pos?page=' . ($page + 1) . $query); ?>">&raquo;</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
    <div class="col-md-6">
        <h4>Keranjang</h4>
        <?php if (!empty($cart)): ?>
            <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nota</th>
                            <th>Customer</th>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $first = true; $rowspan = count($cart); foreach ($cart as $item): ?>
                        <tr>
                            <?php if ($first): ?>
                                <td rowspan="<?php echo $rowspan; ?>"><?php echo $nota; ?></td>
                                <td rowspan="<?php echo $rowspan; ?>">
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="customer_name" id="customer-name" class="form-control" readonly form="checkout-form">
                                        <input type="hidden" name="customer_id" id="customer-id" form="checkout-form">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#memberModal">Pilih</button>
                                        </div>
                                    </div>
                                </td>
                            <?php $first = false; endif; ?>
                            <td><?php echo htmlspecialchars($item['nama_produk']); ?></td>
                            <td><span class="cart-qty" data-price="<?php echo $item['harga_jual']; ?>"><?php echo $item['qty']; ?></span></td>
                            <td class="subtotal">Rp <?php echo number_format($item['harga_jual'] * $item['qty'], 0, ',', '.'); ?></td>
                            <td><a href="<?php echo site_url('pos/remove/'.$item['id']); ?>" class="btn btn-sm btn-danger">Hapus</a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>Total</th>
                            <th id="cart-total" data-total="<?php echo $total; ?>">Rp <?php echo number_format($total, 0, ',', '.'); ?></th>
                        </tr>
                        <tr>
                            <th></th>
                            <th>Bayar</th>
                            <th><input type="number" min="0" class="form-control form-control-sm" id="pay-input" name="bayar" form="checkout-form"></th>
                        </tr>
                        <tr>
                            <th></th>
                            <th>Kembali</th>
                            <th id="change-output">Rp 0</th>
                        </tr>
                    </tfoot>
            </table>
            <form method="post" action="<?php echo site_url('pos/checkout'); ?>" id="checkout-form">
                <input type="hidden" name="device_date" id="device_date">
                <input type="hidden" name="nota" value="<?php echo $nota; ?>">
                <button type="submit" class="btn btn-primary">Checkout</button>
            </form>
        <?php else: ?>
            <p>Keranjang kosong.</p>
<?php endif; ?>
    </div>
</div>

<!-- Modal pilih customer -->
<div class="modal fade" id="memberModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pilih Customer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="customer-type">Pilihan</label>
          <select id="customer-type" class="form-control">
            <option value="non">Non Member</option>
            <option value="member">Member</option>
          </select>
        </div>
        <div class="form-group">
          <label for="member-number">Nomor Member</label>
          <input type="text" id="member-number" class="form-control" disabled>
        </div>
        <div class="form-group">
          <label for="modal-name">Nama</label>
          <input type="text" id="modal-name" class="form-control">
        </div>
        <div class="form-group">
          <label for="modal-phone">No Telepon</label>
          <input type="text" id="modal-phone" class="form-control">
        </div>
        <div class="form-group">
          <label for="modal-address">Alamat</label>
          <textarea id="modal-address" class="form-control"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="choose-member">Pilih</button>
      </div>
    </div>
  </div>
</div>


<script>
var deviceInput = document.getElementById('device_date');
if (deviceInput) {
    var now = new Date();
    deviceInput.value = now.getFullYear() + '-' + ('0' + (now.getMonth() + 1)).slice(-2) + '-' + ('0' + now.getDate()).slice(-2);
}

var addUrl = '<?php echo site_url('pos/add/'); ?>';
var filterForm = document.getElementById('filter-form');
var categorySelect = document.getElementById('category-filter');
if (categorySelect && filterForm) {
    categorySelect.addEventListener('change', function(){ filterForm.submit(); });
}

var qtyCells = document.querySelectorAll('.cart-qty');
var totalCell = document.getElementById('cart-total');
var payInput = document.getElementById('pay-input');
var changeOutput = document.getElementById('change-output');

function recalcTotal() {
    var total = 0;
    for (var i = 0; i < qtyCells.length; i++) {
        var cell = qtyCells[i];
        var price = parseFloat(cell.getAttribute('data-price'));
        var qty = parseFloat(cell.textContent) || 0;
        total += price * qty;
    }
    if (totalCell) {
        totalCell.textContent = 'Rp ' + total.toLocaleString('id-ID');
        totalCell.setAttribute('data-total', total);
    }
    if (payInput && changeOutput) {
        var bayar = parseFloat(payInput.value) || 0;
        var kembali = bayar - total;
        if (kembali < 0) kembali = 0;
        changeOutput.textContent = 'Rp ' + kembali.toLocaleString('id-ID');
    }
}

recalcTotal();

if (payInput && changeOutput && totalCell) {
    payInput.addEventListener('input', function() {
        var total = parseFloat(totalCell.getAttribute('data-total')) || 0;
        var bayar = parseFloat(this.value) || 0;
        var kembali = bayar - total;
        if (kembali < 0) kembali = 0;
        changeOutput.textContent = 'Rp ' + kembali.toLocaleString('id-ID');
    });
}

var typeSelect = document.getElementById('customer-type');
var numberInput = document.getElementById('member-number');
var nameInput = document.getElementById('modal-name');
var phoneInput = document.getElementById('modal-phone');
var addressInput = document.getElementById('modal-address');
var chooseBtn = document.getElementById('choose-member');
var lookupUrl = '<?php echo site_url('pos/member_lookup'); ?>';
var customerIdInput = document.getElementById('customer-id');
var customerNameInput = document.getElementById('customer-name');
if (typeSelect && typeSelect.value === 'non') {
    numberInput.value = 'non member';
    if (customerIdInput) customerIdInput.value = '';
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

if (chooseBtn && customerNameInput) {
    chooseBtn.addEventListener('click', function() {
        customerNameInput.value = nameInput.value;
        $('#memberModal').modal('hide');
    });
}

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('add-to-cart')) {
        var pid = e.target.getAttribute('data-id');
        var qtyInput = document.querySelector('input.product-qty[data-id="' + pid + '"]');
        var qty = qtyInput ? parseInt(qtyInput.value, 10) : 1;
        if (!qty || qty < 1) { qty = 1; }
        window.location.href = addUrl + pid + '?qty=' + qty;
    }
});
</script>
<?php $this->load->view('templates/footer'); ?>
