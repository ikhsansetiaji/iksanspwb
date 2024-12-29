<?php
// Verifikasi PIN di server
session_start();

// Misalnya PIN yang valid disimpan di database atau di session
$validPin = '1234';  // PIN yang valid (bisa diganti dengan mekanisme yang lebih aman, misal di database)

if (isset($_POST['pin'])) {
    $pin = $_POST['pin'];
    
    // Verifikasi PIN
    if ($pin === $validPin) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
