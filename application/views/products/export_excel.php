<?php echo "\xEF\xBB\xBF"; // UTF-8 BOM for Excel ?>
<table border="1">
    <tr>
        <th colspan="5"><?php echo $title; ?></th>
    </tr>
    <tr>
        <td colspan="5">Tanggal: <?php echo $start_date ?: '-'; ?> s/d <?php echo $end_date ?: '-'; ?></td>
    </tr>
    <tr>
        <th>ID</th>
        <th>Nama Produk</th>
        <th>Harga Jual</th>
        <th>Stok</th>
        <th>Kategori</th>
    </tr>
    <?php foreach ($products as $product): ?>
    <tr>
        <td><?php echo $product->id; ?></td>
        <td><?php echo $product->nama_produk; ?></td>
        <td><?php echo $product->harga_jual; ?></td>
        <td><?php echo $product->stok; ?></td>
        <td><?php echo $product->kategori; ?></td>
    </tr>
    <?php endforeach; ?>
</table>
