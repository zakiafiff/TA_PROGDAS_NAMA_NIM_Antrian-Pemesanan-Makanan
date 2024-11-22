<?php
// Nama file yang menyimpan antrian
$file = 'antrian.txt';

// Cek apakah file antrian ada
if (file_exists($file)) {
    // Menghapus seluruh isi file dengan menulis string kosong
    file_put_contents($file, '');
}

// Redirect kembali ke halaman utama setelah mereset
header('Location: index.php'); // Sesuaikan dengan nama file utama kamu
exit;
?>
