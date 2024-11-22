<?php
#Cek jika data nama, nomor meja, dan menu sudah ada
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nama'], $_POST['nomor_meja'], $_POST['menu'])) {
    #Mengambil dan membersihkan input dari form
    $nama = htmlspecialchars($_POST['nama']);
    $nomorMeja = htmlspecialchars($_POST['nomor_meja']);
    $menu = $_POST['menu'];

    #Daftar menu
    $menuList = [
        1 => 'Nasi Goreng',
        2 => 'Mie Goreng',
        3 => 'Sate Ayam',
        4 => 'Ayam Penyet',
        5 => 'Bakso'
    ];

    #Pilihan makanan berdasarkan index
    if (isset($menuList[$menu])) {
        $menuItem = $menuList[$menu];
        #Format data antrian: Nama, Nomor Meja, dan Makanan
        $entry = "Nama: $nama | Nomor Meja: $nomorMeja | Menu: $menuItem";

        #Menyimpan data antrian baru ke file
        file_put_contents('antrian.txt', $entry . PHP_EOL, FILE_APPEND);

        #Back ke halaman utama setelah data disubmit
        header('Location: index.php');
        exit();
    }
}
?>
