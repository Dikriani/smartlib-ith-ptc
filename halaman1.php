<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartLib ITH</title>
    <link rel="stylesheet" href="gaya.css">
</head>

<body>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert">
            <?php
            echo $_SESSION['message'];
            unset($_SESSION['message']);
            ?>
        </div>
    <?php endif; ?>

    <header>
        <h1 class="moving-text">SELAMAT DATANG HABIBIE MUDA</h1>
    </header>

    <main>
        <div class="feature-container">
            <div class="feature" onclick="location.href='#pendataan'">
                <h3>PENDATAAN PENGUNJUNG</h3>
            </div>
            <div class="feature" onclick="location.href='#peminjaman'">
                <h3>PEMINJAMAN BUKU</h3>
            </div>
            <div class="feature" onclick="location.href='#pengembalian'">
                <h3>PENGEMBALIAN BUKU</h3>
            </div>
        </div>

        <!-- Halaman Pendataan Pengunjung -->
        <section id="pendataan">
            <h2>PENDATAAN PENGUNJUNG</h2>
            <form id="formPendataan" action="process.php" method="POST">
                <input type="hidden" name="action" value="pendataan">
                <label for="nama">Nama :</label>
                <input type="text" id="nama" name="nama" required>
                <label for="nim">NIM :</label>
                <input type="text" id="nim_pengunjung" name="nim" required>
                <label for="prodi">Prodi :</label>
                <input type="text" id="prodi_pengunjung" name="prodi" required>
                <button type="submit">Submit</button>
            </form>
        </section>

        <!-- Halaman Peminjaman Buku -->
        <section id="peminjaman">
            <h2>PEMINJAMAN BUKU</h2>
            <form id="formPeminjaman" action="process.php" method="POST">
                <input type="hidden" name="action" value="peminjaman">
                <label for="nama_peminjam">Nama :</label>
                <input type="text" id="nama_peminjam" name="nama_peminjam" required>
                <label for="nim_peminjam">NIM :</label>
                <input type="text" id="nim_peminjam" name="nim" required>
                <label for="prodi_peminjam">Prodi :</label>
                <input type="text" id="prodi_peminjam" name="prodi" required>
                <label for="judul_peminjam">Judul Buku:</label>
                <input type="text" id="judul_peminjam" name="judul_peminjam" required>
                <label for="kode_peminjaman">Kode Buku :</label>
                <input type="text" id="kode_peminjaman" name="kode" required>
                <button type="submit">Submit</button>
            </form>
        </section>

        <!-- Halaman Pengembalian Buku -->
        <section id="pengembalian">
            <h2>PENGEMBALIAN BUKU</h2>
            <form id="formPengembalian" action="process.php" method="POST">
                <input type="hidden" name="action" value="pengembalian">
                <label for="nama_pengembali">Nama :</label>
                <input type="text" id="nama_pengembali" name="nama_pengembali" required>
                <label for="nim_pengembalian">NIM :</label>
                <input type="text" id="nim_pengembalian" name="nim" required>
                <label for="prodi_pengembalian">Prodi :</label>
                <input type="text" id="prodi_pengembalian" name="prodi" required>
                <label for="judul_pengembalian">Judul Buku:</label>
                <input type="text" id="judul_pengembalian" name="judul_peminjam" required>
                <label for="kode_pengembalian">Kode Buku :</label>
                <input type="text" id="kode_pengembalian" name="kode" required>
                <button type="submit">Submit</button>
            </form>
        </section>

        <!-- Halaman Pendaftaran Anggota -->
        <section id="pendaftaran">>
            <h2>PENDAFTARAN ANGGOTA</h2>
            <form id="formPendaftaran" action="process.php" method="POST">
                <input type="hidden" name="action" value="anggota">
                <label for="nama_anggota">Nama Anggota:</label>
                <input type="text" id="nama_anggota" name="nama_anggota" required>
                <label for="nim_pendaftaran">NIM :</label>
                <input type="text" id="nim_pendaftaran" name="nim" required>
                <label for="prodi_pendaftaran">Prodi :</label>
                <input type="text" id="prodi_pendaftaran" name="prodi" required>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <button type="submit">Submit</button>
            </form>
        </section>

        </section>

        <!-- Tombol "Kembali ke Atas" -->
        <button id="scrollTopBtn" onclick="scrollToTop()">⬆</button>
    </main>

    <footer>
        <p>&copy; Hak Cipta 2024 SmartLib ITH</p>
    </footer>


    <!-- Script JavaScript untuk simulasi scan RFID -->
    <script>
        // Fungsi untuk tombol "Kembali ke Atas"
        const scrollTopBtn = document.getElementById("scrollTopBtn");

        window.onscroll = function() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                scrollTopBtn.style.display = "block";
            } else {
                scrollTopBtn.style.display = "none";
            }
        };

        function scrollToTop() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }
    </script>
</body>

</html>