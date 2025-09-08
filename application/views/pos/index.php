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
        <form class="form-inline mb-2" onsubmit="return false;">
            <select name="kategori" id="category-filter" class="form-control mr-2">
                <option value="">Semua Kategori</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?php echo $c; ?>" <?php echo ($selected_category == $c) ? 'selected' : ''; ?>><?php echo htmlspecialchars($c); ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="q" id="product-search" value="<?php echo htmlspecialchars($search_query); ?>" class="form-control mr-2" placeholder="Cari produk">
        </form>
        <table id="products-table" class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Kategori</th>
                    <th>Qty</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($products as $p): ?>
                <tr>
                    <td><?php echo htmlspecialchars($p->nama_produk); ?></td>
                    <td>Rp <?php echo number_format($p->harga_jual, 0, ',', '.'); ?></td>
                    <td><?php echo htmlspecialchars($p->kategori); ?></td>
                    <td><input type="number" value="1" min="1" class="form-control form-control-sm product-qty" data-id="<?php echo $p->id; ?>" style="width:60px"></td>
                    <td><button type="button" class="btn btn-sm btn-success add-to-cart" data-id="<?php echo $p->id; ?>">Tambah</button></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <select id="product-rows-per-page" class="custom-select w-auto d-inline-block">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <nav>
                <ul id="product-pagination" class="pagination mb-0"></ul>
            </nav>
        </div>
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
                                        <input type="hidden" name="customer_phone" id="customer-phone-hidden" form="checkout-form">
                                        <input type="hidden" name="customer_address" id="customer-address-hidden" form="checkout-form">
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

var searchInput = document.getElementById('product-search');
var categorySelect = document.getElementById('category-filter');
var productsBody = document.querySelector('#products-table tbody');
var searchUrl = '<?php echo site_url('pos/search'); ?>';
var addUrl = '<?php echo site_url('pos/add/'); ?>';
var rowsPerPageSelect = document.getElementById('product-rows-per-page');
var pagination = document.getElementById('product-pagination');

function renderProducts(items) {
    productsBody.innerHTML = '';
    for (var i = 0; i < items.length; i++) {
        var p = items[i];
        var tr = document.createElement('tr');
        tr.innerHTML = '<td>' + p.nama_produk + '</td>' +
                       '<td>Rp ' + Number(p.harga_jual).toLocaleString('id-ID') + '</td>' +
                       '<td>' + p.kategori + '</td>' +
                       '<td><input type="number" value="1" min="1" class="form-control form-control-sm product-qty" style="width:60px" data-id="' + p.id + '"></td>' +
                       '<td><button type="button" class="btn btn-sm btn-success add-to-cart" data-id="' + p.id + '">Tambah</button></td>';
        productsBody.appendChild(tr);
    }
    setupProductPagination();
}

function setupProductPagination() {
    if (!productsBody || !rowsPerPageSelect || !pagination) return;
    var allRows = Array.from(productsBody.querySelectorAll('tr'));
    var rows = allRows.slice();
    var rowsPerPage = parseInt(rowsPerPageSelect.value, 10);
    var pageCount = Math.ceil(rows.length / rowsPerPage) || 1;
    var currentPage = 1;

    function displayPage(page) {
        currentPage = page;
        var start = (page - 1) * rowsPerPage;
        var end = start + rowsPerPage;
        allRows.forEach(function(row){ row.style.display = 'none'; });
        rows.slice(start, end).forEach(function(row){ row.style.display = ''; });
        pagination.innerHTML = '';

        var maxLinks = 5;
        var startPage = Math.max(1, currentPage - Math.floor(maxLinks / 2));
        var endPage = Math.min(pageCount, startPage + maxLinks - 1);
        startPage = Math.max(1, endPage - maxLinks + 1);

        function createItem(label, targetPage, disabled) {
            var li = document.createElement('li');
            li.className = 'page-item' + (disabled ? ' disabled' : '');
            var a = document.createElement('a');
            a.className = 'page-link';
            a.href = '#';
            a.textContent = label;
            if (!disabled) {
                a.addEventListener('click', function(e){
                    e.preventDefault();
                    displayPage(targetPage);
                });
            }
            li.appendChild(a);
            pagination.appendChild(li);
        }

        createItem('First', 1, currentPage === 1);
        createItem('Prev', currentPage - 1, currentPage === 1);

        for (var i = startPage; i <= endPage; i++) {
            var li = document.createElement('li');
            li.className = 'page-item' + (i === currentPage ? ' active' : '');
            var a = document.createElement('a');
            a.className = 'page-link';
            a.href = '#';
            a.textContent = i;
            (function(i){
                a.addEventListener('click', function(e){
                    e.preventDefault();
                    displayPage(i);
                });
            })(i);
            li.appendChild(a);
            pagination.appendChild(li);
        }

        createItem('Next', currentPage + 1, currentPage === pageCount);
        createItem('Last', pageCount, currentPage === pageCount);
    }

    displayPage(1);
}

function updateProducts() {
    var params = new URLSearchParams();
    if (categorySelect.value) params.append('kategori', categorySelect.value);
    if (searchInput.value) params.append('q', searchInput.value);
    fetch(searchUrl + '?' + params.toString())
        .then(function(r){ return r.json(); })
        .then(renderProducts);
}

if (searchInput && categorySelect) {
    searchInput.addEventListener('input', updateProducts);
    categorySelect.addEventListener('change', updateProducts);
}

if (rowsPerPageSelect && pagination && productsBody) {
    rowsPerPageSelect.addEventListener('change', setupProductPagination);
    setupProductPagination();
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
var customerPhoneHidden = document.getElementById('customer-phone-hidden');
var customerAddressHidden = document.getElementById('customer-address-hidden');
if (typeSelect && typeSelect.value === 'non') {
    numberInput.value = 'non member';
    if (customerIdInput) customerIdInput.value = '';
    if (customerPhoneHidden) customerPhoneHidden.value = '';
    if (customerAddressHidden) customerAddressHidden.value = '';
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
            if (customerPhoneHidden) customerPhoneHidden.value = '';
            if (customerAddressHidden) customerAddressHidden.value = '';
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
            if (customerPhoneHidden) customerPhoneHidden.value = '';
            if (customerAddressHidden) customerAddressHidden.value = '';
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
                        if (customerPhoneHidden) customerPhoneHidden.value = m.no_telepon || '';
                        if (customerAddressHidden) customerAddressHidden.value = m.alamat || '';
                    } else {
                        if (customerIdInput) customerIdInput.value = '';
                        nameInput.value = '';
                        phoneInput.value = '';
                        addressInput.value = '';
                        if (customerPhoneHidden) customerPhoneHidden.value = '';
                        if (customerAddressHidden) customerAddressHidden.value = '';
                    }
                });
        } else {
            if (customerIdInput) customerIdInput.value = '';
            nameInput.value = '';
            phoneInput.value = '';
            addressInput.value = '';
            if (customerPhoneHidden) customerPhoneHidden.value = '';
            if (customerAddressHidden) customerAddressHidden.value = '';
        }
    });
}

if (chooseBtn && customerNameInput) {
    chooseBtn.addEventListener('click', function() {
        customerNameInput.value = nameInput.value;
        if (customerPhoneHidden) customerPhoneHidden.value = phoneInput.value;
        if (customerAddressHidden) customerAddressHidden.value = addressInput.value;
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
