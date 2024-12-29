<?php
session_start();
function redirectWithAlert($message, $alertType) {
    header("Location: ../index.php?alert_message=" . urlencode($message) . "&alert_type=$alertType");
    exit();
}

// Contoh penggunaan



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validasi
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        redirectWithAlert('Semua field harus diisi!', 'error');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirectWithAlert('Email tidak valid!', 'error');
    }

    if ($password !== $confirm_password) {
        redirectWithAlert('Password dan Konfirmasi Password tidak cocok!', 'error');
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $conn = new mysqli("localhost", "root", "", "fitness_db");
    if ($conn->connect_error) {
        redirectWithAlert('Gagal terhubung ke database: ' . $conn->connect_error, 'error');
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        $conn->close();
        redirectWithAlert('Email sudah terdaftar. Silakan gunakan email lain.', 'error');
    }

    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        redirectWithAlert(
            'Pendaftaran berhasil! Selamat datang di Fitness, ' . htmlspecialchars($username) . '. Silakan login.',
            'success'
        );
    } else {
        $stmt->close();
        $conn->close();
        redirectWithAlert('Gagal mendaftarkan pengguna baru. Silakan coba lagi.', 'error');
    }
} else {
    redirectWithAlert('Metode request tidak valid.', 'error');
}
?>
