<?php $this->load->view('templates/header'); ?>
<h2>Daftar Transaksi POS</h2>
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>
<form method="get" class="form-inline mb-3">
    <input type="date" name="start" class="form-control mr-2" value="<?php echo set_value('', date('Y-m-d')); ?>">
    <input type="date" name="end" class="form-control mr-2" value="<?php echo set_value('', date('Y-m-d')); ?>">
    <input type="hidden" name="q" value="<?php echo html_escape($search_query); ?>">
    <button type="submit" class="btn btn-primary">Cari</button>
</form>

<?php if ($filter_start && $filter_end): ?>
    <?php if (!empty($sales)): ?>
        <form method="get" class="form-inline mb-2" style="max-width:200px;">
            <input type="text" name="q" class="form-control <?php echo ($search_query && empty($sales)) ? 'is-invalid' : ''; ?>" placeholder="Cari transaksi..." value="<?php echo html_escape($search_query); ?>">
            <div class="invalid-feedback">Data tidak ditemukan.</div>
            <input type="hidden" name="start" value="<?php echo htmlspecialchars($filter_start); ?>">
            <input type="hidden" name="end" value="<?php echo htmlspecialchars($filter_end); ?>">
            <input type="hidden" name="per_page" value="<?php echo $per_page; ?>">
            <input type="hidden" name="page" value="1">
        </form>
        <table class="table table-bordered table-sm" id="transaction-table">
            <thead>
                <tr>
                    <th>Nota</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($sales as $s): ?>
                <tr>
                    <td><?php echo htmlspecialchars($s->nomor_nota); ?></td>
                    <td><?php echo htmlspecialchars($s->customer_name ?: ($s->member_name ?: 'non member')); ?></td>
                    <td>Rp <?php echo number_format($s->total_belanja, 0, ',', '.'); ?></td>
                    <td><?php echo htmlspecialchars($s->tanggal_transaksi); ?></td>
                    <td>
                        <a href="<?php echo site_url('pos/reprint/'.$s->id); ?>" class="btn btn-sm btn-secondary" title="Print nota" aria-label="Print nota"><i class="fas fa-print"></i></a>
                        <?php $can_cancel = date('Y-m-d', strtotime($s->tanggal_transaksi)) === date('Y-m-d'); ?>
                        <?php if ($can_cancel): ?>
                            <a href="<?php echo site_url('pos/cancel/'.$s->id); ?>" class="btn btn-sm btn-secondary" title="Batal" aria-label="Batal" onclick="return confirm('Apakah Anda yakin ingin membatalkan transaksi ini? Tindakan ini tidak dapat dibatalkan.');"><i class="fas fa-times text-white"></i></a>
                        <?php else: ?>
                            <button class="btn btn-sm btn-secondary" title="Tidak dapat dibatalkan" disabled><i class="fas fa-times text-white"></i></button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <!-- <th colspan="2" class="text-right">Total Halaman</th> -->
                    <th id="page-total">Rp <?php echo number_format($page_total, 0, ',', '.'); ?></th>
                    <th colspan="2"></th>
                </tr>
            </tfoot>
        </table>
        <div class="d-flex align-items-center">
            <?php if ($total_pages > 1): ?>
            <?php
                $base_params = [
                    'start'    => $filter_start,
                    'end'      => $filter_end,
                    'per_page' => $per_page,
                    'q'        => $search_query
                ];
                $max_links  = 5;
                $start_page = max(1, $page - intdiv($max_links, 2));
                $end_page   = min($total_pages, $start_page + $max_links - 1);
                $start_page = max(1, $end_page - $max_links + 1);
            ?>
            <nav>
                <ul class="pagination mb-0">
                    <?php if ($page > 1): ?>
                        <li class="page-item"><a class="page-link" href="?<?php echo http_build_query($base_params + ['page'=>1]); ?>">First</a></li>
                        <li class="page-item"><a class="page-link" href="?<?php echo http_build_query($base_params + ['page'=>$page-1]); ?>">Prev</a></li>
                    <?php else: ?>
                        <li class="page-item disabled"><span class="page-link">First</span></li>
                        <li class="page-item disabled"><span class="page-link">Prev</span></li>
                    <?php endif; ?>
                    <?php for ($p = $start_page; $p <= $end_page; $p++): ?>
                        <li class="page-item <?php echo $p === $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?<?php echo http_build_query($base_params + ['page'=>$p]); ?>"><?php echo $p; ?></a>
                        </li>
                    <?php endfor; ?>
                    <?php if ($page < $total_pages): ?>
                        <li class="page-item"><a class="page-link" href="?<?php echo http_build_query($base_params + ['page'=>$page+1]); ?>">Next</a></li>
                        <li class="page-item"><a class="page-link" href="?<?php echo http_build_query($base_params + ['page'=>$total_pages]); ?>">Last</a></li>
                    <?php else: ?>
                        <li class="page-item disabled"><span class="page-link">Next</span></li>
                        <li class="page-item disabled"><span class="page-link">Last</span></li>
                    <?php endif; ?>
                </ul>
            </nav>
            <?php endif; ?>
            <form method="get" class="form-inline ml-3" id="perPageForm">
                <label for="per_page" class="mr-2">Per Halaman:</label>
                <select name="per_page" id="per_page" class="form-control mr-2" onchange="this.form.submit()">
                    <option value="10" <?php echo $per_page == 10 ? 'selected' : ''; ?>>10</option>
                    <option value="25" <?php echo $per_page == 25 ? 'selected' : ''; ?>>25</option>
                    <option value="50" <?php echo $per_page == 50 ? 'selected' : ''; ?>>50</option>
                    <option value="100" <?php echo $per_page == 100 ? 'selected' : ''; ?>>100</option>
                </select>
                <input type="hidden" name="start" value="<?php echo htmlspecialchars($filter_start); ?>">
                <input type="hidden" name="end" value="<?php echo htmlspecialchars($filter_end); ?>">
                <input type="hidden" name="q" value="<?php echo html_escape($search_query); ?>">
                <input type="hidden" name="page" value="1">
            </form>
        </div>
    <?php else: ?>
        <p>Tidak ada transaksi pada rentang tanggal tersebut.</p>
    <?php endif; ?>
<?php else: ?>
    <p>Silakan pilih rentang tanggal untuk melihat transaksi.</p>
<?php endif; ?>
<?php $this->load->view('templates/footer'); ?>
