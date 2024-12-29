<?php
require_once '../vendor/autoload.php';
session_start();

$fb = new \Facebook\Facebook([
    'app_id' => '1757172818451131',
    'app_secret' => '48945a9f1efde2cb828480904d4e12d2',
    'default_graph_version' => 'v10.0',
]);

$helper = $fb->getRedirectLoginHelper();

if (!isset($_GET['code'])) {
    $permissions = ['public_profile', 'email']; // Tambahkan izin lain jika diperlukan
    $loginUrl = $helper->getLoginUrl('http://localhost/projectpwbsip/helper/login_facebook.php', $permissions);
    header('Location: ' . $loginUrl);
    exit();
} else {
    try {
        $accessToken = $helper->getAccessToken();
        $response = $fb->get('/me?fields=id,name,email', $accessToken);
        $user = $response->getGraphUser();

        // Periksa apakah email sudah terdaftar
        require './koneksi.php';
        $stmt = $conn->prepare("SELECT id, username FROM users WHERE email = ?");
        $stmt->bind_param("s", $user['email']);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $username);
            $stmt->fetch();
            $_SESSION['username'] = $username;
            $_SESSION['message'] = "Login berhasil dengan Facebook.";
            $_SESSION['alert_type'] = "success";
        } else {
            $_SESSION['message'] = "Email tidak ditemukan. Silakan daftar terlebih dahulu.";
            $_SESSION['alert_type'] = "danger";
        }

        $stmt->close();
        $conn->close();
        header("Location: ../index.php");
        exit();
    } catch (Facebook\Exceptions\FacebookResponseException $e) {
        error_log($e->getMessage());
        $_SESSION['message'] = "Login dengan Facebook gagal.";
        $_SESSION['alert_type'] = "danger";
        header("Location: ../index.php");
        exit();
    }
}
?>
