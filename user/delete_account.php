<?php
session_start();
require '../helper/koneksi.php'; // Koneksi ke database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = filter_var($_POST['user_id'], FILTER_SANITIZE_NUMBER_INT);

    if (!$user_id) {
        echo json_encode(['status' => 'error', 'message' => 'ID pengguna tidak valid']);
        exit();
    }

    // Hapus data pengguna dari database
    try {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data']);
        }

        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan']);
    }
}
?>
