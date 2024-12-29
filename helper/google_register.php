<?php
require_once '../vendor/autoload.php';
if (!class_exists('Google\Client')) {
    die('Google API Client tidak dikenali. Periksa instalasi Composer Anda.');
}

session_start();

$client = new Google_Client();
$client->setClientId('591786728508-hj9c3aiovhg9mogsdvr7ha11d4q7j1sc.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/projectpwbsip/helper/google_register.php');
$client->addScope("email");
$client->addScope("profile");

if (isset($_GET['code'])) {
    try {
        // Fetch access token
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $client->setAccessToken($token);

        // Get user info from Google
        $google_oauth = new Google_Service_Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();
        $email = $google_account_info->email;
        $name = $google_account_info->name;

        // Connect to database
        $conn = new mysqli("localhost", "root", "", "fitness_db");
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Email already exists, return error
            throw new Exception("Email sudah terdaftar. Silakan gunakan akun lain.");
        }

        $stmt->close();

        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (username, email, oauth_provider, oauth_id) VALUES (?, ?, 'google', ?)");
        $stmt->bind_param("sss", $name, $email, $google_account_info->id);
        if (!$stmt->execute()) {
            throw new Exception("Gagal mendaftarkan pengguna baru.");
        }

        $stmt->close();
        $conn->close();

        // Set session for successful registration
        $_SESSION['username'] = $name;

        // Menampilkan pesan sukses
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Pendaftaran Berhasil!</strong> Selamat datang, ' . htmlspecialchars($name) . '.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
        header("refresh:3;url=../index.php"); // Redirect setelah 3 detik
        exit();
    } catch (Exception $e) {
        // Menampilkan pesan error
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Gagal!</strong> ' . htmlspecialchars($e->getMessage()) . '.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
        header("refresh:5;url=../index.php"); // Redirect setelah 5 detik
        exit();
    }
} else {
    // Redirect to Google login
    header("Location: " . $client->createAuthUrl());
    exit();
}
?>
