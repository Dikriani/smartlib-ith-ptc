<?php
session_start();

// Menghapus semua session
session_unset();

// Menghancurkan session
session_destroy();

// Mengarahkan pengguna kembali ke halaman login
header("Location: onepage.php");
exit();
