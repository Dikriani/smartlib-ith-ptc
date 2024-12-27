<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartLib ITH</title>
    <link rel="stylesheet" href="styles.css">
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
        <h1>SmartLib ITH</h1>
    </header>

    <main>
        <h2>Fitur Utama</h2>
        <div class="feature-container">
            <div class="feature" onclick="location.href='#pendataan'">
                <h3>Pendataan Pengunjung</h3>
            </div>
            <div class="feature" onclick="location.href='#peminjaman'">
                <h3>Peminjaman Buku</h3>
            </div>
            <div class="feature" onclick="location.href='#pengembalian'">
                <h3>Pengembalian Buku</h3>
            </div>
            <div class="feature" onclick="location.href='#pendaftaran'">
                <h3>Pendaftaran Anggota</h3>
            </div>
        </div>

        <!-- Halaman Pendataan Pengunjung -->
        <section id="pendataan">
            <h2>Pendataan Pengunjung</h2>
            <form id="formPendataan" action="process.php" method="POST">
                <input type="hidden" name="action" value="pendataan">
                <label for="nama">Nama :</label>
                <input type="text" id="nama" name="nama" required>

                <label for="nim">NIM :</label>
                <input type="text" id="nim_pengunjung" name="nim" required>

                <label for="prodi">Prodi :</label>
                <input type="text" id="prodi_pengunjung" name="prodi" required>

                <button type="button" onclick="scanRFIDPengunjung()">Scan Kartu</button>
                <button type="submit">Submit</button>
            </form>
        </section>

        <!-- Halaman Peminjaman Buku -->
        <section id="peminjaman">
            <h2>Peminjaman Buku</h2>
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

                <button type="button" onclick="scanRFIDPeminjaman()">Scan Kartu</button>
                <button type="submit">Submit</button>
            </form>
        </section>

        <!-- Halaman Pengembalian Buku -->
        <section id="pengembalian">
            <h2>Pengembalian Buku</h2>
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

                <button type="button" onclick="scanRFIDPengembalian()">Scan Kartu</button>
                <button type="submit">Submit</button>
            </form>
        </section>

        <!-- Halaman Pendaftaran Anggota -->
        <section id="pendaftaran">
            <h2>Pendaftaran Anggota</h2>
            <form id="formPendaftaran" action="process.php" method="POST">
                <input type="hidden" name="action" value="pendaftaran">

                <label for="nama_anggota">Nama Anggota:</label>
                <input type="text" id="nama_anggota" name="nama_anggota" required>

                <label for="nim_pendaftaran">NIM :</label>
                <input type="text" id="nim_pendaftaran" name="nim" required>

                <label for="prodi_pendaftaran">Prodi :</label>
                <input type="text" id="prodi_pendaftaran" name="prodi" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <button type="button" onclick="scanRFIDPendaftaran()">Scan Kartu</button>
                <button type="submit">Submit</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 SmartLib ITH</p>
    </footer>

    <!-- Script JavaScript untuk simulasi scan RFID -->
    <script>
        function scanRFIDPengunjung() {
            const dataRFID = {
                nama: "Dikriani",
                nim: "221011057",
                prodi: "Ilmu Komputer"
            };

            document.getElementById('nama').value = dataRFID.nama;
            document.getElementById('nim_pengunjung').value = dataRFID.nim;
            document.getElementById('prodi_pengunjung').value = dataRFID.prodi;
        }

        function scanRFIDPeminjaman() {
            const dataRFIDPeminjaman = {
                nama: "Reza Afriansyah",
                nim: "221011050",
                prodi: "Ilmu Komputer",
                judul_buku: "Struktur Data",
                kode_buku: "231311"
            };

            document.getElementById('nama_peminjam').value = dataRFIDPeminjaman.nama;
            document.getElementById('nim_peminjam').value = dataRFIDPeminjaman.nim;
            document.getElementById('prodi_peminjam').value = dataRFIDPeminjaman.prodi;
            document.getElementById('judul_peminjam').value = dataRFIDPeminjaman.judul_buku;
            document.getElementById('kode_peminjaman').value = dataRFIDPeminjaman.kode_buku;
        }

        function scanRFIDPengembalian() {
            const dataRFIDPengembalian = {
                nama: "Reza Afriansyah",
                nim: "221011050",
                prodi: "Ilmu Komputer",
                judul_buku: "Struktur Data",
                kode_buku: "231311"
            };

            document.getElementById('nama_pengembali').value = dataRFIDPengembalian.nama;
            document.getElementById('nim_pengembalian').value = dataRFIDPengembalian.nim;
            document.getElementById('prodi_pengembalian').value = dataRFIDPengembalian.prodi;
            document.getElementById('judul_pengembalian').value = dataRFIDPengembalian.judul_buku;
            document.getElementById('kode_pengembalian').value = dataRFIDPengembalian.kode_buku;
        }

        function scanRFIDPendaftaran() {
            const dataRFIDPendaftaran = {
                nama_anggota: "Reza Afriansyah",
                nim: "221011050",
                prodi: "Ilmu Komputer",
                email: "reza12@gmail.com"
            };

            document.getElementById('nama_anggota').value = dataRFIDPendaftaran.nama_anggota;
            document.getElementById('nim_pendaftaran').value = dataRFIDPendaftaran.nim;
            document.getElementById('prodi_pendaftaran').value = dataRFIDPendaftaran.prodi;
            document.getElementById('email').value = dataRFIDPendaftaran.email;
        }
    </script>
</body>

</html>