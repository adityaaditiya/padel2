<?php $this->load->view('templates/header'); ?>
<h2>Stock Opname</h2>
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>

<form class="form-inline mb-2" onsubmit="return false;">
    <select name="kategori" id="category-filter" class="form-control mr-2">
        <option value="">Semua Kategori</option>
        <?php foreach ($categories as $c): ?>
            <option value="<?php echo $c; ?>" <?php echo ($selected_category == $c) ? 'selected' : ''; ?>><?php echo htmlspecialchars($c); ?></option>
        <?php endforeach; ?>
    </select>
    <input type="text" name="q" id="product-search" value="<?php echo htmlspecialchars($search_query); ?>" class="form-control" placeholder="Cari produk">
</form>

<form id="stock-form" method="post" action="<?php echo site_url('stock_opname/save'); ?>">
<table class="table table-bordered" id="products-table">
    <thead>
        <tr>
            <th>Nama Produk</th>
            <th>Kategori</th>
            <th>Stok Sistem</th>
            <th>Jumlah Fisik</th>
            <th>Selisih</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product): ?>
        <tr>
            <td><?php echo htmlspecialchars($product->nama_produk); ?></td>
            <td><?php echo htmlspecialchars($product->kategori); ?></td>
            <td><?php echo $product->stok; ?></td>
            <td>
                <input type="number" name="physical[<?php echo $product->id; ?>]" class="form-control physical-input" data-system="<?php echo $product->stok; ?>" required>
            </td>
            <td class="difference">0</td>
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
<button type="submit" class="btn btn-primary mt-2">Simpan Data Opname</button>
</form>

<script>
var searchInput = document.getElementById('product-search');
var categorySelect = document.getElementById('category-filter');
var productsBody = document.querySelector('#products-table tbody');
var rowsPerPageSelect = document.getElementById('product-rows-per-page');
var pagination = document.getElementById('product-pagination');
var searchUrl = '<?php echo site_url('stock_opname/search'); ?>';
var stockForm = document.getElementById('stock-form');
var physicalValues = {};

function attachDiffListeners() {
    if (!productsBody) return;
    productsBody.querySelectorAll('.physical-input').forEach(function(input){
        input.addEventListener('input', function(){
            var system = parseInt(this.getAttribute('data-system'), 10) || 0;
            var physical = parseInt(this.value, 10) || 0;
            var diff = physical - system;
            this.closest('tr').querySelector('.difference').textContent = diff;
            var idMatch = this.name.match(/physical\[(\d+)\]/);
            if (idMatch) {
                physicalValues[idMatch[1]] = this.value;
            }
        });
    });
}

function renderProducts(items) {
    productsBody.innerHTML = '';
    items.forEach(function(p){
        var stored = physicalValues[p.id] !== undefined ? physicalValues[p.id] : '';
        var diff = stored ? (parseInt(stored, 10) - parseInt(p.stok, 10)) : 0;
        var tr = document.createElement('tr');
        tr.innerHTML = '<td>' + p.nama_produk + '</td>' +
                       '<td>' + (p.kategori || '') + '</td>' +
                       '<td>' + p.stok + '</td>' +
                       '<td><input type="number" name="physical[' + p.id + ']" class="form-control physical-input" data-system="' + p.stok + '" value="' + stored + '" required></td>' +
                       '<td class="difference">' + diff + '</td>';
        productsBody.appendChild(tr);
    });
    attachDiffListeners();
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
    if (productsBody) {
        productsBody.querySelectorAll('.physical-input').forEach(function(inp){
            var idMatch = inp.name.match(/physical\[(\d+)\]/);
            if (idMatch) {
                physicalValues[idMatch[1]] = inp.value;
            }
        });
    }
    var params = new URLSearchParams();
    if (categorySelect.value) params.append('kategori', categorySelect.value);
    if (searchInput.value) params.append('q', searchInput.value);
    fetch(searchUrl + '?' + params.toString())
        .then(function(r){ return r.json(); })
        .then(renderProducts);
}

if (stockForm) {
    stockForm.addEventListener('submit', function(){
        if (productsBody) {
            productsBody.querySelectorAll('.physical-input').forEach(function(inp){
                var idMatch = inp.name.match(/physical\[(\d+)\]/);
                if (idMatch) {
                    physicalValues[idMatch[1]] = inp.value;
                }
            });
        }
        this.querySelectorAll('.hidden-physical').forEach(function(el){ el.remove(); });
        for (var id in physicalValues) {
            if (!physicalValues.hasOwnProperty(id)) continue;
            if (!this.querySelector('input[name="physical[' + id + ']"]')) {
                var hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'physical[' + id + ']';
                hidden.value = physicalValues[id];
                hidden.className = 'hidden-physical';
                this.appendChild(hidden);
            } else {
                this.querySelector('input[name="physical[' + id + ']"]').value = physicalValues[id];
            }
        }
    });
}

if (searchInput && categorySelect) {
    searchInput.addEventListener('input', updateProducts);
    categorySelect.addEventListener('change', updateProducts);
}

if (rowsPerPageSelect && pagination && productsBody) {
    rowsPerPageSelect.addEventListener('change', setupProductPagination);
    setupProductPagination();
}

attachDiffListeners();
</script>
<?php $this->load->view('templates/footer'); ?>
