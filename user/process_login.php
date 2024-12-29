
<?php
session_start([
    'cookie_httponly' => true,
    'cookie_secure' => isset($_SERVER['HTTPS']), // Pastikan hanya pada koneksi HTTPS
    'use_strict_mode' => true
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require '../helper/koneksi.php';

    // Sanitasi input
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    // Validasi input
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($password)) {
        $_SESSION['message'] = "Email atau password tidak valid.";
        $_SESSION['alert_type'] = "danger";
        $_SESSION['show_modal'] = true; // Tampilkan modal login
        header("Location: ../index.php");
        exit();
    }

    try {
        // Query database untuk email
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
        if (!$stmt) {
            throw new Exception("Prepare statement gagal: " . $conn->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        // Periksa apakah email ditemukan
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $username, $hashed_password);
            $stmt->fetch();

            // Verifikasi password
            if (password_verify($password, $hashed_password)) {
                // Login berhasil
                $_SESSION['username'] = $username;
                $_SESSION['message'] = "Login berhasil! Selamat datang di Sistem Informasi Lembah Fitness.";
                $_SESSION['alert_type'] = "success";
                $_SESSION['show_login_toast'] = true;
                $stmt->close();
                $conn->close();
                header("Location: ../index.php");
                exit();
            }
        }

        // Jika login gagal
        $_SESSION['message'] = "Email atau password salah.";
        $_SESSION['alert_type'] = "danger";
        $_SESSION['show_modal'] = true; // Tampilkan modal login
        $stmt->close();
        $conn->close();
        header("Location: ../index.php");
        exit();
    } catch (Exception $e) {
        // Log error (jangan tampilkan kepada user)
        error_log($e->getMessage());

        $_SESSION['message'] = "Terjadi kesalahan sistem. Silakan coba lagi.";
        $_SESSION['alert_type'] = "danger";
        $_SESSION['show_modal'] = true; // Tampilkan modal login
        header("Location: ../index.php");
        exit();
    }
}
?>


