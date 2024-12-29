<?php
// Koneksi ke database
require '../../helper/koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars($_POST['message']);

    // Validasi input
    if (empty($name) || empty($email) || empty($message)) {
        die('Semua field wajib diisi.');
    }

    // Simpan data ke database
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        // Simpan pesan sukses di session
        $_SESSION['toast_message'] = "Pesan berhasil dikirim! Terima kasih telah menghubungi kami.";
        header("Location: ../../index.php");
        exit();
    } else {
        echo "Terjadi kesalahan. Silakan coba lagi.";
    }

    $stmt->close();
    $conn->close();
}
?>
