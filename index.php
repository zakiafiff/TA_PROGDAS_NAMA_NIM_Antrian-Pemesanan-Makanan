<?php
// Mendefinisikan password untuk Mereset antrian
define('RESET_PASSWORD', 'TA123'); 


class Pemesanan {
    private $nama;
    private $nomorMeja;
    private $menu;
    private $totalHarga;
    private $pesanan = [];

    public function setNama($nama) {
        if (preg_match("/^[a-zA-Z ]*$/", $nama)) { 
            $this->nama = htmlspecialchars($nama);  #hanya huruf dan spasi yang diperbolehkan
        } else {
            $this->nama = '';
            echo "Nama hanya boleh mengandung huruf dan spasi."; #Jika nama tidak valid,maka akan muncul pesan error
        }
    }
    

    public function getNama() {
        return $this->nama;
    }

    // Setter Getter untuk Nomor Meja
    public function setNomorMeja($nomorMeja) {
        $this->nomorMeja = htmlspecialchars($nomorMeja);
    }

    public function getNomorMeja() {
        return $this->nomorMeja;
    }

    // Setter Getter untuk Menu
    public function setMenu($menu) {
        $this->menu = $menu;
    }

    public function getMenu() {
        return $this->menu;
    }

    // Getter untuk Total Harga
    public function getTotalHarga() {
        return $this->totalHarga;
    }

    // Method untuk memproses pesanan
    public function prosesPesanan($menuMakanan, $menuMinuman) {
        $this->totalHarga = 0;
        $this->pesanan = [];

        foreach ($this->menu as $item) {
            if (isset($menuMakanan[$item])) {
                $this->pesanan[] = $menuMakanan[$item]['item'] . ' - Rp ' . number_format($menuMakanan[$item]['harga'], 0, ',', '.');
                $this->totalHarga += $menuMakanan[$item]['harga'];
            } elseif (isset($menuMinuman[$item])) {
                $this->pesanan[] = $menuMinuman[$item]['item'] . ' - Rp ' . number_format($menuMinuman[$item]['harga'], 0, ',', '.');
                $this->totalHarga += $menuMinuman[$item]['harga'];
            }
        }
    }

    // Method KE dalam string
    public function getPesanan() {
        return implode(", ", $this->pesanan);
    }
}

// Mengecek jika data yaitu nama, nomor meja, dan menu sudah ada
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nama'], $_POST['nomor_meja'], $_POST['menu'])) {
    $pemesanan = new Pemesanan();

    // Mengatur data yang diterima
    $pemesanan->setNama($_POST['nama']);
    $pemesanan->setNomorMeja($_POST['nomor_meja']);
    $pemesanan->setMenu($_POST['menu']);

    // Data makanan dan minuman
    $menuMakanan = [
        1 => ['item' => 'Nasi Goreng', 'harga' => 20000],
        2 => ['item' => 'Mie Goreng', 'harga' => 18000],
        3 => ['item' => 'Sate Ayam', 'harga' => 25000],
        4 => ['item' => 'Ayam Penyet', 'harga' => 22000],
        5 => ['item' => 'Bakso', 'harga' => 15000]
    ];

    $menuMinuman = [
        6 => ['item' => 'Es Teh', 'harga' => 5000],
        7 => ['item' => 'Es Jeruk', 'harga' => 6000],
        8 => ['item' => 'Kopi', 'harga' => 8000],
        9 => ['item' => 'Air Mineral', 'harga' => 3000],
        10 => ['item' => 'Jus Buah', 'harga' => 10000]
    ];

    // Memproses pesanan
    $pemesanan->prosesPesanan($menuMakanan, $menuMinuman);

    // Jika ada pesanan
    if (!empty($pemesanan->getPesanan())) {
        // Format data antrian: Nama, Nomor Meja, Pesanan, dan Total Harga
        $entry = "Nama: " . $pemesanan->getNama() . " | Nomor Meja: " . $pemesanan->getNomorMeja() . " | Pesanan: " . $pemesanan->getPesanan() . " | Total: Rp " . number_format($pemesanan->getTotalHarga(), 0, ',', '.') . " | Waktu: " . date('Y-m-d H:i:s');

        // Menyimpan data antrian baru ke dalam file
        file_put_contents('antrian.txt', $entry . PHP_EOL, FILE_APPEND);

        // Kembali ke halaman utama setelah data disubmit
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Reset antrian jika tombol reset ditekan dan password benar
if (isset($_POST['reset'])) {
    // Mengecek password yang dimasukkan benar
    if (isset($_POST['password']) && $_POST['password'] === RESET_PASSWORD) {
        file_put_contents('antrian.txt', ''); #untuk menghapus isi file antrian
        $message = "Antrian berhasil direset.";
    } else {
        $message = "Password salah. Antrian tidak direset.";
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pemesanan</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <div class="container">
        <h2>Form Pemesanan Makanan dan Minuman</h2>
        <form action="" method="POST">
            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" required><br><br>

            <label for="nomor_meja">Nomor Meja:</label>
            <select id="nomor_meja" name="nomor_meja" required>
                <?php for ($i = 1; $i <= 25; $i++) : ?>
                    <option value="<?= $i ?>">Meja <?= $i ?></option>
                <?php endfor; ?>
            </select><br><br>

            <h3>Pilih Makanan</h3>
            <input type="checkbox" name="menu[]" value="1"> Nasi Goreng - Rp 20.000<br>
            <input type="checkbox" name="menu[]" value="2"> Mie Goreng - Rp 18.000<br>
            <input type="checkbox" name="menu[]" value="3"> Sate Ayam - Rp 25.000<br>
            <input type="checkbox" name="menu[]" value="4"> Ayam Penyet - Rp 22.000<br>
            <input type="checkbox" name="menu[]" value="5"> Bakso - Rp 15.000<br><br>

            <h3>Pilih Minuman</h3>
            <input type="checkbox" name="menu[]" value="6"> Es Teh - Rp 5.000<br>
            <input type="checkbox" name="menu[]" value="7"> Es Jeruk - Rp 6.000<br>
            <input type="checkbox" name="menu[]" value="8"> Kopi - Rp 8.000<br>
            <input type="checkbox" name="menu[]" value="9"> Air Mineral - Rp 3.000<br>
            <input type="checkbox" name="menu[]" value="10"> Jus Buah - Rp 10.000<br><br>

            <button type="submit">Pesan</button>
        </form>

        <form action="" method="POST">
            <h3>Reset Antrian</h3>
            <label for="password">Password (Khusus Pelayan):</label>
            <input type="password" id="password" name="password" required><br><br>
            <button type="submit" name="reset">Reset Antrian</button>
        </form>

        <h3>Daftar Antrian</h3>
       <pre>
    <?php
    // Mengecek apakah file antrian.txt ada atau tidak kosong
    if (file_exists('antrian.txt') && filesize('antrian.txt') > 0) {
        $antrian = file('antrian.txt'); #Membaca semua baris dalam file antrian.txt
        $nomorAntrian = 1; #untuk nomor urut

        // nomor urut dan pesanan
        foreach ($antrian as $entry) {
            echo "No. " . $nomorAntrian++ . " - " . $entry;
        }
    } else {
        echo "Belum ada pesanan.";
    }

    // Menampilkan pesan reset
    if (isset($message)) {
        echo "\n" . $message;
    }
    ?>
     </pre>

    </div>
</body>
</html>
