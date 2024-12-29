<?php
// Mulai sesi jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sertakan file koneksi database
require_once './koneksi.php';

// Cek apakah form login dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validasi input
    if (!empty($username) && !empty($password)) {
        try {
            // Ambil data pengguna berdasarkan username
            $stmt = $conn->prepare("SELECT * FROM login WHERE username = ? LIMIT 1");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();

                // Verifikasi password
                if (md5($password) === $user['password']) {
                    // Simpan data ke sesi
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['nama_pengguna'] = $user['nama_pengguna'];

                    // Tentukan hak akses berdasarkan username
                    if ($username === 'admin') {
                        header('Location: ../routes/index.php?page=admin');
                    } elseif ($username === 'staff') {
                        header('Location: ../../admin\index.php?page=staff');
                    } else {
                        header('Location: ../../index.php?page=user');
                    }
                    exit();
                } else {
                    $_SESSION['error_message'] = "Password salah.";
                    header('Location: ../login.php');
                    exit();
                }
            } else {
                $_SESSION['error_message'] = "Username tidak ditemukan.";
                header('Location: ../login.php');
                exit();
            }
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
            $_SESSION['error_message'] = "Terjadi kesalahan pada sistem.";
            header('Location: ../login.php');
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Harap isi semua kolom.";
        header('Location: ../login.php');
        exit();
    }
}
?>
