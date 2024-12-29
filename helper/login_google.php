<?php
require_once '../vendor/autoload.php';
session_start();

$client = new Google_Client();
$client->setClientId('591786728508-hj9c3aiovhg9mogsdvr7ha11d4q7j1sc.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/projectpwbsip/helper/login_google.php');
$client->addScope('email');
$client->addScope('profile');

if (!isset($_GET['code'])) {
    header('Location: ' . $client->createAuthUrl());
    exit();
} else {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    $google_service = new Google_Service_Oauth2($client);
    $user = $google_service->userinfo->get();

    // Periksa apakah email sudah terdaftar di database
    require './koneksi.php';
    $stmt = $conn->prepare("SELECT id, username FROM users WHERE email = ?");
    $stmt->bind_param("s", $user->email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username);
        $stmt->fetch();
        $_SESSION['username'] = $username;
        $_SESSION['message'] = "Login berhasil dengan Google.";
        $_SESSION['alert_type'] = "success";
    } else {
        $_SESSION['message'] = "Email tidak ditemukan. Silakan daftar terlebih dahulu.";
        $_SESSION['alert_type'] = "danger";
    }

    $stmt->close();
    $conn->close();
    header("Location: ../index.php");
    exit();
}
?>
