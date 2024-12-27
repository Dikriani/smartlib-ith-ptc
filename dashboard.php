<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header('Location: admin_login.php');
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "SmartLib_ITH";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Variabel untuk menyimpan data yang akan diedit
$edit_data = null;
$table = $id = $column = null;

// Aksi: Edit Data (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $table = $_POST['table'];
    $id = $_POST['id'];
    $column = $_POST['column'];

    // Ambil data dari tabel berdasarkan ID
    $result = $conn->query("SELECT * FROM $table WHERE $column = '$id'");
    if ($result->num_rows > 0) {
        $edit_data = $result->fetch_assoc();
        echo json_encode(['status' => 'success', 'data' => $edit_data]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
    }
    exit(); // Mengakhiri eksekusi setelah mendapatkan data edit
}

// Aksi: Simpan Edit Data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_edit'])) {
    $table = $_POST['table'];
    $id = $_POST['id'];
    $column = $_POST['column'];

    // Ambil data yang akan diedit
    $update_data = [];
    foreach ($_POST as $key => $value) {
        if ($key != 'save_edit' && $key != 'table' && $key != 'id' && $key != 'column') {
            $update_data[$key] = $value;
        }
    }

    // Update data di database
    $update_fields = [];
    $update_values = [];
    foreach ($update_data as $field => $value) {
        $update_fields[] = "$field = ?";
        $update_values[] = $value;
    }

    $update_values[] = $id;

    $stmt = $conn->prepare("UPDATE $table SET " . implode(", ", $update_fields) . " WHERE $column = ?");
    $stmt->bind_param(str_repeat("s", count($update_values)), ...$update_values);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Data berhasil disimpan!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data!']);
    }

    exit();
}

// Aksi: Hapus Data (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $table = $_POST['table'];
    $id = $_POST['id'];
    $column = $_POST['column'];

    $stmt = $conn->prepare("DELETE FROM $table WHERE $column = ?");
    $stmt->bind_param("s", $id);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Data berhasil dihapus!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data!']);
    }
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SmartLib ITH</title>
    <link rel="stylesheet" href="dashboard.css">
</head>

<body>
    <h1>PENDATAAN PERPUSTAKAAN ITH</h1>

    <!-- Form Edit Data -->
    <div id="editFormContainer" style="display:none;">
        <h2>Edit Data</h2>
        <form id="editForm" method="POST">
            <div id="editFields"></div>
            <input type="hidden" id="editTable" name="table">
            <input type="hidden" id="editId" name="id">
            <input type="hidden" id="editColumn" name="column">
            <button type="submit" name="save_edit">Simpan</button>
        </form>
    </div>

    <!-- Data Pengunjung -->
    <section id="pengunjung">
        <h2>Data Pengunjung</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>NIM</th>
                    <th>Prodi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT nama, nim, prodi FROM pengunjung");
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nim']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['prodi']) . "</td>";
                        echo "<td>
                                <form method='POST' style='display:inline;'>
                                    <input type='hidden' name='table' value='pengunjung'>
                                    <input type='hidden' name='id' value='" . htmlspecialchars($row['nim']) . "'>
                                    <input type='hidden' name='column' value='nim'>
                                    <button type='submit' name='delete'>Hapus</button>
                                </form>
                                <form method='POST' style='display:inline;'>
                                    <input type='hidden' name='table' value='pengunjung'>
                                    <input type='hidden' name='id' value='" . htmlspecialchars($row['nim']) . "'>
                                    <input type='hidden' name='column' value='nim'>
                                    <button type='submit' name='edit'>Edit</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Belum ada data pengunjung.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>

    <!-- Data Peminjaman -->
    <section id="peminjaman">
        <h2>Data Peminjaman Buku</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>NIM</th>
                    <th>Prodi</th>
                    <th>Judul Buku</th>
                    <th>Kode Buku</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT nama, nim, prodi, judul_buku, kode_buku FROM peminjaman");
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nim']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['prodi']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['judul_buku']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['kode_buku']) . "</td>";
                        echo "<td>
                                <form method='POST' style='display:inline;'>
                                    <input type='hidden' name='table' value='peminjaman'>
                                    <input type='hidden' name='id' value='" . htmlspecialchars($row['kode_buku']) . "'>
                                    <input type='hidden' name='column' value='kode_buku'>
                                    <button type='submit' name='delete'>Hapus</button>
                                </form>
                                <form method='POST' style='display:inline;'>
                                    <input type='hidden' name='table' value='peminjaman'>
                                    <input type='hidden' name='id' value='" . htmlspecialchars($row['kode_buku']) . "'>
                                    <input type='hidden' name='column' value='kode_buku'>
                                    <button type='submit' name='edit'>Edit</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Belum ada data peminjaman.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>

    <!-- Data Pengembalian -->
    <section id="pengembalian">
        <h2>Data Pengembalian Buku</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>NIM</th>
                    <th>Judul Buku</th>
                    <th>Kode Buku</th>
                    <th>Tanggal Kembali</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT nama, nim, judul_buku, kode_buku, tgl_pengembalian FROM pengembalian");
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nim']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['judul_buku']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['kode_buku']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['tgl_pengembalian']) . "</td>";
                        echo "<td>
                                <form method='POST' style='display:inline;'>
                                    <input type='hidden' name='table' value='pengembalian'>
                                    <input type='hidden' name='id' value='" . htmlspecialchars($row['nim']) . "'>
                                    <input type='hidden' name='column' value='nim'>
                                    <button type='submit' name='delete'>Hapus</button>
                                </form>
                                <form method='POST' style='display:inline;'>
                                    <input type='hidden' name='table' value='pengembalian'>
                                    <input type='hidden' name='id' value='" . htmlspecialchars($row['nim']) . "'>
                                    <input type='hidden' name='column' value='nim'>
                                    <button type='submit' name='edit'>Edit</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Belum ada data pengembalian.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>

    <!-- Data Pendaftaran Anggota -->
    <section id="anggota">
        <h2>Data Pendaftaran Anggota</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>NIM</th>
                    <th>Prodi</th>
                    <th>Email</th>
                    <th>Tanggal Pendaftaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM anggota");
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nim']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['prodi']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['tanggal_pendaftaran']) . "</td>";
                        echo "<td>
                                <form method='POST' style='display:inline;'>
                                    <input type='hidden' name='table' value='anggota'>
                                    <input type='hidden' name='id' value='" . htmlspecialchars($row['id']) . "'>
                                    <input type='hidden' name='column' value='id'>
                                    <button type='submit' name='delete'>Hapus</button>
                                </form>
                                <form method='POST' style='display:inline;'>
                                    <input type='hidden' name='table' value='anggota'>
                                    <input type='hidden' name='id' value='" . htmlspecialchars($row['id']) . "'>
                                    <input type='hidden' name='column' value='id'>
                                    <button type='submit' name='edit'>Edit</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Belum ada data anggota.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>

    <script>
        // Handle Edit Button Click
        document.querySelectorAll("button[name='edit']").forEach(button => {
            button.addEventListener("click", function(e) {
                e.preventDefault();

                const form = this.closest("form");
                const formData = new FormData(form);
                formData.append("action", "edit");

                fetch("", {
                        method: "POST",
                        body: formData,
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            const editFields = document.getElementById("editFields");
                            editFields.innerHTML = ""; // Clear any previous form fields

                            // Dynamically create form fields for editing
                            for (const [key, value] of Object.entries(data.data)) {
                                const label = document.createElement("label");
                                label.textContent = key;

                                const input = document.createElement("input");
                                input.type = "text";
                                input.name = key;
                                input.value = value;

                                const div = document.createElement("div");
                                div.appendChild(label);
                                div.appendChild(input);

                                editFields.appendChild(div);
                            }

                            // Set hidden inputs for table, ID, and column
                            document.getElementById("editTable").value = formData.get("table");
                            document.getElementById("editId").value = formData.get("id");
                            document.getElementById("editColumn").value = formData.get("column");

                            // Show the edit form
                            document.getElementById("editFormContainer").style.display = "block";
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Gagal memuat data untuk edit!');
                    });
            });
        });

        document.addEventListener("DOMContentLoaded", () => {
            // Menangani Form Edit
            document.querySelectorAll("button[name='edit']").forEach(button => {
                button.addEventListener("click", function(e) {
                    e.preventDefault();

                    const form = this.closest("form");
                    const formData = new FormData(form);
                    formData.append("action", "edit");

                    fetch("", {
                            method: "POST",
                            body: formData,
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === "success") {
                                // Menampilkan form edit dan mengisinya dengan data
                                const editFormContainer = document.getElementById("editFormContainer");
                                editFormContainer.style.display = "block";

                                const editFieldsContainer = document.getElementById("editFields");
                                editFieldsContainer.innerHTML = ""; // Mereset field

                                // Loop melalui field dan buat input form untuk pengeditan
                                for (const field in data.data) {
                                    const value = data.data[field];
                                    const label = document.createElement("label");
                                    label.textContent = field;
                                    const input = document.createElement("input");
                                    input.type = "text";
                                    input.name = field;
                                    input.value = value;

                                    editFieldsContainer.appendChild(label);
                                    editFieldsContainer.appendChild(input);
                                    editFieldsContainer.appendChild(document.createElement("br"));
                                }

                                // Menambahkan input tersembunyi untuk table, id, dan column tanpa menampilkan id di form
                                document.getElementById("editTable").value = formData.get('table');
                                document.getElementById("editId").value = formData.get('id'); // Menyimpan ID dalam input tersembunyi
                                document.getElementById("editColumn").value = formData.get('column');
                            } else {
                                alert(data.message);
                            }
                        })
                        .catch(error => console.error("Error:", error));
                });
            });

            // Menangani Hapus Data
            document.querySelectorAll("button[name='delete']").forEach(button => {
                button.addEventListener("click", function(e) {
                    e.preventDefault();

                    const form = this.closest("form");
                    const formData = new FormData(form);
                    formData.append("action", "delete");

                    fetch("", {
                            method: "POST",
                            body: formData,
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === "success") {
                                alert(data.message);
                                form.closest("tr").remove(); // Menghapus baris tabel setelah data dihapus
                            } else {
                                alert(data.message);
                            }
                        })
                        .catch(error => console.error("Error:", error));
                });
            });

            // Menyimpan Data Edit
            const editForm = document.getElementById("editForm");
            editForm.addEventListener("submit", function(e) {
                e.preventDefault();

                const formData = new FormData(editForm);
                formData.append("save_edit", true);

                fetch("", {
                        method: "POST",
                        body: formData,
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            alert(data.message);

                            // Memperbarui baris di tabel dengan nilai baru setelah edit berhasil
                            const updatedRow = document.querySelector(`tr[data-id='${formData.get('id')}']`);
                            updatedRow.querySelectorAll("td").forEach((td, index) => {
                                const field = Object.keys(data.data)[index];
                                td.textContent = data.data[field]; // Memperbarui sel tabel sesuai data terbaru
                            });

                            // Menutup form edit
                            document.getElementById("editFormContainer").style.display = "none";

                            // OPTIONAL: Reload bagian tabel atau data yang relevan jika perlu
                            // Bisa digunakan jika ingin memuat ulang data setelah edit, misalnya dengan mengirim permintaan fetch baru
                            // fetchData(); // Menyertakan fungsi untuk memuat ulang data jika diperlukan
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => console.error("Error:", error));
            });
            fetch("", {
                    method: "POST",
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const editFormContainer = document.getElementById('editFormContainer');
                        const editFields = document.getElementById('editFields');
                        const editTable = document.getElementById('editTable');
                        const editId = document.getElementById('editId');
                        const editColumn = document.getElementById('editColumn');

                        // Clear the previous fields
                        editFields.innerHTML = '';

                        // Set the table, ID, and column for the edit form
                        editTable.value = formData.get('table');
                        editId.value = formData.get('id');
                        editColumn.value = formData.get('column');

                        // Populate the form with the fetched data
                        for (let key in data.data) {
                            if (data.data.hasOwnProperty(key)) {
                                const inputField = document.createElement('div');
                                inputField.innerHTML = `<label for="${key}">${key.charAt(0).toUpperCase() + key.slice(1)}</label>
                                                            <input type="text" name="${key}" id="${key}" value="${data.data[key]}">`;
                                editFields.appendChild(inputField);
                            }
                        }

                        // Show the form container
                        editFormContainer.style.display = 'block';
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        });

        // Handling form submission to save edited data
        const editForm = document.getElementById('editForm');
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(editForm);
            formData.append("save_edit", "true");

            fetch("", {
                    method: "POST",
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        // Optionally, you can refresh the page or update the table here
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
</body>

</html>