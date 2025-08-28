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
                    <option value="<?php echo $c->kategori; ?>" <?php echo ($selected_category == $c->kategori) ? 'selected' : ''; ?>><?php echo htmlspecialchars($c->kategori); ?></option>
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
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($products as $p): ?>
                <tr>
                    <td><?php echo htmlspecialchars($p->nama_produk); ?></td>
                    <td>Rp <?php echo number_format($p->harga_jual, 0, ',', '.'); ?></td>
                    <td><?php echo htmlspecialchars($p->kategori); ?></td>
                    <td><a href="<?php echo site_url('pos/add/'.$p->id); ?>" class="btn btn-sm btn-success">Tambah</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <h4>Keranjang</h4>
        <?php if (!empty($cart)): ?>
            <form method="post" action="<?php echo site_url('pos/update_cart'); ?>">
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
                            <td><input type="number" name="qty[<?php echo $item['id']; ?>]" value="<?php echo $item['qty']; ?>" min="1" class="form-control form-control-sm cart-qty" data-price="<?php echo $item['harga_jual']; ?>"></td>
                            <td class="subtotal">Rp <?php echo number_format($item['harga_jual'] * $item['qty'], 0, ',', '.'); ?></td>
                            <td><a href="<?php echo site_url('pos/remove/'.$item['id']); ?>" class="btn btn-sm btn-danger">Hapus</a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                          <th></th>
                            <th>Total</th>
                            <th id="cart-total">Rp <?php echo number_format($total, 0, ',', '.'); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </form>
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
        <div class="form-inline mb-2">
          <input type="text" id="member-search" class="form-control mr-2" placeholder="Cari customer">
          <button type="button" id="member-search-btn" class="btn btn-primary btn-sm">Cari</button>
        </div>
        <table class="table table-bordered table-sm" id="member-table">
          <thead>
            <tr>
              <th>Kode</th>
              <th>Nama</th>
              <th>Telepon</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($members as $m): ?>
            <tr>
              <td><?php echo htmlspecialchars($m->kode_member); ?></td>
              <td><?php echo htmlspecialchars($m->nama_lengkap); ?></td>
              <td><?php echo htmlspecialchars($m->no_telepon); ?></td>
              <td><button type="button" class="btn btn-sm btn-success select-member" data-id="<?php echo $m->id; ?>" data-name="<?php echo htmlspecialchars($m->nama_lengkap); ?>">Pilih</button></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
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

function renderProducts(items) {
    productsBody.innerHTML = '';
    items.forEach(function(p) {
        var tr = document.createElement('tr');
        tr.innerHTML = '<td>' + p.nama_produk + '</td>' +
                       '<td>Rp ' + Number(p.harga_jual).toLocaleString('id-ID') + '</td>' +
                       '<td>' + p.kategori + '</td>' +
                       '<td><a href="' + addUrl + p.id + '" class="btn btn-sm btn-success">Tambah</a></td>';
        productsBody.appendChild(tr);
    });
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

var qtyInputs = document.querySelectorAll('.cart-qty');
var totalCell = document.getElementById('cart-total');

function recalcTotal() {
    var total = 0;
    qtyInputs.forEach(function(input) {
        var price = parseFloat(input.dataset.price);
        var qty = parseFloat(input.value) || 0;
        total += price * qty;
    });
    if (totalCell) {
        totalCell.textContent = 'Rp ' + total.toLocaleString('id-ID');
    }
}

qtyInputs.forEach(function(input) {
    input.addEventListener('input', function() {
        var price = parseFloat(this.dataset.price);
        var qty = parseFloat(this.value) || 0;
        var subtotal = price * qty;
        var cell = this.closest('tr').querySelector('.subtotal');
        if (cell) {
            cell.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
        }
        recalcTotal();
    });
});
var memberSearchInput = document.getElementById('member-search');
var memberSearchBtn = document.getElementById('member-search-btn');
var memberTableBody = document.querySelector('#member-table tbody');
var memberSearchUrl = '<?php echo site_url('pos/member_search'); ?>';

function renderMembers(list) {
    memberTableBody.innerHTML = '';
    list.forEach(function(m) {
        var tr = document.createElement('tr');
        tr.innerHTML = '<td>' + (m.kode_member || '') + '</td>' +
                       '<td>' + m.nama_lengkap + '</td>' +
                       '<td>' + (m.no_telepon || '') + '</td>' +
                       '<td><button type="button" class="btn btn-sm btn-success select-member" data-id="' + m.id + '" data-name="' + m.nama_lengkap + '">Pilih</button></td>';
        memberTableBody.appendChild(tr);
    });
}

function searchMembers() {
    var params = new URLSearchParams();
    if (memberSearchInput.value) params.append('q', memberSearchInput.value);
    fetch(memberSearchUrl + '?' + params.toString())
        .then(function(r){ return r.json(); })
        .then(renderMembers);
}

if (memberSearchBtn && memberSearchInput) {
    memberSearchBtn.addEventListener('click', searchMembers);
    memberSearchInput.addEventListener('keyup', function(e){ if (e.key === 'Enter') searchMembers(); });
}

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('select-member')) {
        var id = e.target.getAttribute('data-id');
        var name = e.target.getAttribute('data-name');
        document.getElementById('customer-id').value = id;
        document.getElementById('customer-name').value = name;
        $('#memberModal').modal('hide');
    }
});

var checkoutForm = document.getElementById('checkout-form');
if (checkoutForm) {
    checkoutForm.addEventListener('submit', function(e) {
        var customerId = document.getElementById('customer-id').value;
        if (!customerId) {
            e.preventDefault();
            alert('Pilih customer terlebih dahulu.');
        }
    });
}
</script>
<?php $this->load->view('templates/footer'); ?>
