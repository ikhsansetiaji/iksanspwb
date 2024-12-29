<?php
require_once '../vendor/autoload.php'; // Facebook SDK
session_start();

use Facebook\Facebook;

$appId = '1757172818451131'; // Ganti dengan App ID Anda
$appSecret = '48945a9f1efde2cb828480904d4e12d2'; // Ganti dengan App Secret Anda
$callbackUrl = 'http://localhost/projectpwbsip/helper/facebook_register.php'; // Ganti dengan URL callback Anda

$fb = new Facebook([
    'app_id' => $appId,
    'app_secret' => $appSecret,
    'default_graph_version' => 'v15.0',
]);

$helper = $fb->getRedirectLoginHelper();

if (isset($_GET['state'])) {
    $helper->getPersistentDataHandler()->set('state', $_GET['state']);
}

try {
    if (isset($_GET['code'])) {
        // User granted access
        $accessToken = $helper->getAccessToken($callbackUrl);

        if (!$accessToken) {
            throw new Exception('Access token tidak tersedia.');
        }

        // Ambil data pengguna
        $response = $fb->get('/me?fields=id,name,email', $accessToken);
        $user = $response->getGraphUser();

        $facebookId = $user->getId();
        $name = $user->getName();
        $email = $user->getEmail();

        // Simpan ke database
        $conn = new mysqli("localhost", "root", "", "fitness_db");
        if ($conn->connect_error) {
            throw new Exception("Gagal terhubung ke database: " . $conn->connect_error);
        }

        // Cek apakah email sudah ada
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Email sudah ada
            $_SESSION['message'] = 'Akun sudah terdaftar. Silakan login.';
            $_SESSION['alert_type'] = 'warning';
        } else {
            // Registrasi pengguna baru
            $stmt = $conn->prepare("INSERT INTO users (username, email, oauth_provider, oauth_id) VALUES (?, ?, 'facebook', ?)");
            $stmt->bind_param("sss", $name, $email, $facebookId);

            if ($stmt->execute()) {
                $_SESSION['message'] = 'Registrasi berhasil! Selamat datang, ' . htmlspecialchars($name) . '.';
                $_SESSION['alert_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Gagal menyimpan data pengguna.';
                $_SESSION['alert_type'] = 'danger';
            }
        }

        $stmt->close();
        $conn->close();

        // Redirect ke halaman utama
        header("Location: ../index.php");
        exit();
    } else {
        // Redirect ke Facebook login page
        $permissions = ['public_profile', 'email']; // Tambahkan izin lain jika diperlukan
        $loginUrl = $helper->getLoginUrl($callbackUrl, $permissions);
        header("Location: " . $loginUrl);
        exit();
    }
} catch (Exception $e) {
    echo 'Error: ' . htmlspecialchars($e->getMessage());
}
?>
