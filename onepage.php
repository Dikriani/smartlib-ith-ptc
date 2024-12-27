<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartLib ITH</title>
    <link rel="stylesheet" href="page.css">
</head>

<body>
    <header>
        <h1>SMARTLIB ITH</h1>
    </header>

    <main>
        <!-- Wrapper untuk semua konten -->
        <div class="content-wrapper">
            <!-- Scan RFID -->
            <section id="scanRFID">
                <img src="card.png" Card Scan Icon" style="width: 420px; height: 160px; object-fit: contain;">
                <!-- <h2>Scan Kartu Anggota Anda</h2> -->
                <form id="formScan" action="home.php" method="POST">
                    <input type="text" id="rfid" name="rfid" placeholder="Tempelkan kartu RFID di scanner" required readonly>
                    <button type="submit">Proses Data</button>
                </form>
            </section>

            <!-- Link Pendaftaran Anggota -->
            <section id="pendaftaranAnggota">
                <p>Belum Punya Kartu Anggota? Daftar
                    <a href="home.php#pendaftaran">Disini</a>
                </p>
            </section>
        </div>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (window.location.hash) {
                const target = document.querySelector(window.location.hash);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            }
        });
    </script>
</body>

</html>