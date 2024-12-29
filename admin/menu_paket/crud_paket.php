<?php
require_once '../../helper/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Add Package
    if ($action === 'add') {
        $nama = $_POST['nama'];
        $deskripsi = $_POST['deskripsi'];
        $harga = $_POST['harga'];
        $durasi = $_POST['durasi'];

        $query = "INSERT INTO menu_paket (nama, deskripsi, harga, durasi) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'ssdi', $nama, $deskripsi, $harga, $durasi);

        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message' => 'Paket berhasil ditambahkan.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menambahkan paket.']);
        }

        mysqli_stmt_close($stmt);
    }

    // Delete Package
    elseif ($action === 'delete') {
        $id = $_POST['id'];

        $query = "DELETE FROM menu_paket WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $id);

        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message' => 'Paket berhasil dihapus.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus paket.']);
        }

        mysqli_stmt_close($stmt);
    }

    // Update Package
    elseif ($action === 'update') {
        $id = $_POST['id'];
        $nama = $_POST['nama'];
        $deskripsi = $_POST['deskripsi'];
        $harga = $_POST['harga'];
        $durasi = $_POST['durasi'];

        $query = "UPDATE menu_paket SET nama = ?, deskripsi = ?, harga = ?, durasi = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'ssdii', $nama, $deskripsi, $harga, $durasi, $id);

        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message' => 'Paket berhasil diperbarui.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal memperbarui paket.']);
        }

        mysqli_stmt_close($stmt);
    }
}

elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';

    // Get Packages
    if ($action === 'get') {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $search = mysqli_real_escape_string($conn, $_GET['search'] ?? '');
        $offset = ($page - 1) * $limit;

        $countQuery = "SELECT COUNT(*) AS total FROM menu_paket WHERE nama LIKE '%$search%'";
        $countResult = mysqli_query($conn, $countQuery);
        $totalRows = mysqli_fetch_assoc($countResult)['total'];
        $totalPages = ceil($totalRows / $limit);

        $query = "SELECT * FROM menu_paket WHERE nama LIKE '%$search%' LIMIT $limit OFFSET $offset";
        $result = mysqli_query($conn, $query);

        $packages = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $packages[] = $row;
        }

        echo json_encode([
            'packages' => $packages,
            'pagination' => [
                'totalPages' => $totalPages,
                'currentPage' => $page
            ]
        ]);
    }

    // Get Package by ID
    elseif ($action === 'get_package') {
        $id = $_GET['id'];
        $query = "SELECT * FROM menu_paket WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $package = mysqli_fetch_assoc($result);

        echo json_encode($package);
    }
}

mysqli_close($conn);
?>
