<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nota Booking</title>
    <style>
        body { font-family: monospace; width: 80mm; margin: 0 auto; }
        h2 { text-align: center; margin: 0 0 10px 0; font-size: 16px; }
        table { width: 100%; font-size: 12px; }
        td { padding: 2px 0; }
        @media print {
            @page { size: 80mm 50mm; margin: 0; }
            body { width: 80mm; margin: 0; }
        }
    </style>
</head>
<body>
<div class="receipt">
    <h2>Nota Booking</h2>
    <table>
        <tr><td>ID Booking</td><td>: <?= $booking->id ?></td></tr>
        <tr><td>Tanggal</td><td>: <?= $booking->tanggal_booking ?></td></tr>
        <tr><td>Lapangan</td><td>: <?= $booking->nama_lapangan ?></td></tr>
        <tr><td>Mulai</td><td>: <?= $booking->jam_mulai ?></td></tr>
        <tr><td>Selesai</td><td>: <?= $booking->jam_selesai ?></td></tr>
        <tr><td>Durasi</td><td>: <?= $booking->durasi ?> jam</td></tr>
        <tr><td>Harga</td><td>: Rp <?= number_format($booking->harga_booking,0,',','.') ?></td></tr>
        <tr><td>Diskon</td><td>: Rp <?= number_format($booking->diskon,0,',','.') ?></td></tr>
        <tr><td>Total</td><td>: Rp <?= number_format($booking->total_harga,0,',','.') ?></td></tr>
    </table>
</div>
<script>
window.onload = function() {
    window.print();
};
</script>
</body>
</html>
