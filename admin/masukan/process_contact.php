<?php
// Koneksi ke database
require '../../helper/koneksi.php';

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
        echo "Pesan berhasil dikirim!";
        header("Location: ../../index.php");
    } else {
        echo "Terjadi kesalahan. Silakan coba lagi.";
    }

    $stmt->close();
    $conn->close();
}
?>
