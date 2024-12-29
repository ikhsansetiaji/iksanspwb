<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit();
}

// Dapatkan nama pengguna dari sesi
$username = $_SESSION['username'];

// Tentukan halaman berdasarkan parameter "page"
$page = $_GET['page'] ?? 'dashboard';

// Fungsi untuk memuat file berdasarkan jenis pengguna
function loadPage($page, $username) {
    $routes = [
        'login' => '../login.php',
        'logoutuser' => '../index.php',
        'admin' => [
            'allowed' => ['admin'],
            'file' => '../admin/index.php',
            'error' => '../errors/404.html',
        ],
        'staff_dashboard' => [
            'allowed' => ['staff'],
            'file' => 'staff/dashboard_staff.php',
            'error' => '../errors/403.php',
        ],
        'user_dashboard' => [
            'allowed' => ['user'], // Semua selain admin/staff dianggap user
            'file' => '../index.php',
            'error' => '../errors/403.php',
        ],
    ];

    // Validasi rute
    if (isset($routes[$page])) {
        $route = $routes[$page];

        if (is_array($route)) {
            // Validasi peran pengguna
            $isUser = ($route['allowed'] === ['user'] && !in_array($username, ['admin', 'staff']));
            if (in_array($username, $route['allowed']) || $isUser) {
                include $route['file'];
            } else {
                include $route['error'];
            }
        } else {
            include $route;
        }
    } else {
        include '../errors/404.html'; // Pastikan nama file dan folder benar
    }
}

// Panggil fungsi untuk memuat halaman
loadPage($page, $username);
?>
