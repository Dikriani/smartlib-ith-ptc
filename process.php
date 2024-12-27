<?php
session_start();

// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "SmartLib_ITH";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari request dengan pemeriksaan ketersediaan
$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($action === 'pendataan') {
    // Fungsi Pendataan Pengunjung
    $nama = $_POST['nama'];
    $nim = $_POST['nim'];
    $prodi = $_POST['prodi'];

    // Cek duplikasi data pengunjung
    $check_stmt = $conn->prepare("SELECT * FROM pengunjung WHERE nim = ?");
    $check_stmt->bind_param("s", $nim);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows === 0) {
        $stmt = $conn->prepare("INSERT INTO pengunjung (nama, nim, prodi) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nama, $nim, $prodi);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Pendataan pengunjung berhasil.";
            $_SESSION['status'] = "success";
        } else {
            $_SESSION['message'] = "Gagal menyimpan data pengunjung: " . $stmt->error;
            $_SESSION['status'] = "error";
        }
        $stmt->close();
    } else {
        $_SESSION['message'] = "Pengunjung dengan NIM ini sudah terdata.";
        $_SESSION['status'] = "warning";
    }
    $check_stmt->close();
} elseif ($action === 'peminjaman') {
    // Fungsi Peminjaman Buku
    $nama = $_POST['nama_peminjam'];
    $nim = $_POST['nim'];
    $prodi = $_POST['prodi'];
    $judul_buku = $_POST['judul_peminjam'];
    $kode_buku = $_POST['kode'];

    // Cek apakah judul buku dan kode buku sudah ada dalam data peminjaman
    $check_stmt = $conn->prepare("SELECT judul_buku, kode_buku FROM peminjaman WHERE judul_buku = ? AND kode_buku = ?");
    $check_stmt->bind_param("ss", $judul_buku, $kode_buku);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // Jika ditemukan data duplikat, ambil judul buku dan kode buku
        $row = $result->fetch_assoc();
        $existing_judul = $row['judul_buku'];
        $existing_kode = $row['kode_buku'];

        // Tampilkan pesan bahwa buku sudah dipinjam
        $_SESSION['message'] = "Buku <b>'$existing_judul'</b> dengan kode <b>'$existing_kode'</b> sudah dipinjam oleh pengguna lain.";
        $_SESSION['status'] = "warning";
        
    } else {
        // Jika tidak ada duplikat, masukkan data baru ke database
        $stmt = $conn->prepare("INSERT INTO peminjaman (nama, nim, prodi, judul_buku, kode_buku) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nama, $nim, $prodi, $judul_buku, $kode_buku);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Peminjaman berhasil dicatat.";
            $_SESSION['status'] = "success";
        } else {
            $_SESSION['message'] = "Gagal mencatat peminjaman: " . $stmt->error;
            $_SESSION['status'] = "error";
        }
        $stmt->close();
    }

    $check_stmt->close();
} elseif ($action === 'pengembalian') {
    // Fungsi Pengembalian Buku
    $nama = $_POST['nama_pengembali'];
    $nim = $_POST['nim'];
    $prodi = $_POST['prodi'];
    $judul_buku = $_POST['judul_peminjam'];
    $kode_buku = $_POST['kode'];

    // Cek apakah data peminjaman ada
    $check_stmt = $conn->prepare("SELECT * FROM peminjaman WHERE nama = ? AND nim = ? AND prodi = ? AND judul_buku = ? AND kode_buku = ?");
    $check_stmt->bind_param("sssss", $nama, $nim, $prodi, $judul_buku, $kode_buku);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // Cek apakah data sudah ada di tabel pengembalian
        $duplicate_stmt = $conn->prepare("SELECT * FROM pengembalian WHERE nim = ? AND kode_buku = ?");
        $duplicate_stmt->bind_param("ss", $nim, $kode_buku);
        $duplicate_stmt->execute();
        $duplicate_result = $duplicate_stmt->get_result();

        if ($duplicate_result->num_rows === 0) {
            // Masukkan data ke tabel pengembalian
            $stmt = $conn->prepare("INSERT INTO pengembalian (nama, nim, prodi, judul_buku, kode_buku) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $nama, $nim, $prodi, $judul_buku, $kode_buku);

            if ($stmt->execute()) {
                // Hapus data dari tabel peminjaman
                $delete_stmt = $conn->prepare("DELETE FROM peminjaman WHERE nama = ? AND nim = ? AND prodi = ? AND judul_buku = ? AND kode_buku = ?");
                $delete_stmt->bind_param("sssss", $nama, $nim, $prodi, $judul_buku, $kode_buku);

                if ($delete_stmt->execute()) {
                    $_SESSION['message'] = "Pengembalian berhasil.";
                    $_SESSION['status'] = "success";
                } else {
                    $_SESSION['message'] = "Gagal menghapus data peminjaman: " . $delete_stmt->error;
                    $_SESSION['status'] = "warning";
                }
                $delete_stmt->close();
            } else {
                $_SESSION['message'] = "Gagal mencatat pengembalian: " . $stmt->error;
                $_SESSION['status'] = "error";
            }
            $stmt->close();
        } else {
            $_SESSION['message'] = "Buku ini sudah dikembalikan sebelumnya.";
            $_SESSION['status'] = "warning";
        }
        $duplicate_stmt->close();
    } else {
        $_SESSION['message'] = "Data peminjaman tidak ditemukan. Pastikan data yang dimasukkan sesuai.";
        $_SESSION['status'] = "error";
    }
    $check_stmt->close();
} elseif ($action === 'anggota') {
    // Fungsi Pendaftaran Anggota
    $nama = $_POST['nama_anggota'];
    $nim = $_POST['nim'];
    $prodi = $_POST['prodi'];
    $email = isset($_POST['email']) ? $_POST['email'] : '';

    // Cek duplikasi data anggota
    $check_stmt = $conn->prepare("SELECT * FROM anggota WHERE nim = ?");
    $check_stmt->bind_param("s", $nim);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows === 0) {
        $stmt = $conn->prepare("INSERT INTO anggota (nama, nim, prodi, email) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nama, $nim, $prodi, $email);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Pendaftaran anggota berhasil.";
            $_SESSION['status'] = "success";
        } else {
            $_SESSION['message'] = "Gagal mendaftarkan anggota: " . $stmt->error;
            $_SESSION['status'] = "error";
        }
        $stmt->close();
    } else {
        $_SESSION['message'] = "Anggota dengan NIM ini sudah terdaftar.";
        $_SESSION['status'] = "warning";
    }
    $check_stmt->close();
} else {
    $_SESSION['message'] = "Aksi tidak dikenali.";
    $_SESSION['status'] = "error";
}

$conn->close();
header("Location: home.php");
exit;
