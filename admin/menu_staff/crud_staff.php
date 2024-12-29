<?php
require_once '../../helper/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $name = trim($_POST['name'] ?? '');
        $position = trim($_POST['position'] ?? '');

        if (empty($name) || empty($position)) {
            echo json_encode(['success' => false, 'message' => 'Nama dan Posisi tidak boleh kosong.']);
            exit();
        }

        $query = "INSERT INTO staff (name, position) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'ss', $name, $position);

        if ($stmt && mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message' => 'Staff berhasil ditambahkan.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menambahkan staff.']);
        }

        mysqli_stmt_close($stmt);
    } elseif ($action === 'update') {
        $id = intval($_POST['id']);
        $name = trim($_POST['name'] ?? '');
        $position = trim($_POST['position'] ?? '');

        if (empty($name) || empty($position)) {
            echo json_encode(['success' => false, 'message' => 'Nama dan Posisi tidak boleh kosong.']);
            exit();
        }

        $query = "UPDATE staff SET name = ?, position = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'ssi', $name, $position, $id);

        if ($stmt && mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message' => 'Staff berhasil diperbarui.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal memperbarui staff.']);
        }

        mysqli_stmt_close($stmt);
    } elseif ($action === 'delete') {
        $id = intval($_POST['id']);

        $query = "DELETE FROM staff WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $id);

        if ($stmt && mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message' => 'Staff berhasil dihapus.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus staff.']);
        }

        mysqli_stmt_close($stmt);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';

    if ($action === 'get') {
        $page = intval($_GET['page'] ?? 1);
        $limit = intval($_GET['limit'] ?? 10);
        $offset = ($page - 1) * $limit;
        $search = mysqli_real_escape_string($conn, $_GET['search'] ?? '');

        $query = "SELECT * FROM staff WHERE name LIKE ? ORDER BY id DESC LIMIT ? OFFSET ?";
        $stmt = mysqli_prepare($conn, $query);
        $searchTerm = "%{$search}%";
        mysqli_stmt_bind_param($stmt, 'sii', $searchTerm, $limit, $offset);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $staff = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $staff[] = $row;
        }

        $totalQuery = "SELECT COUNT(*) AS total FROM staff WHERE name LIKE ?";
        $stmtTotal = mysqli_prepare($conn, $totalQuery);
        mysqli_stmt_bind_param($stmtTotal, 's', $searchTerm);
        mysqli_stmt_execute($stmtTotal);
        $totalResult = mysqli_stmt_get_result($stmtTotal);
        $totalRows = mysqli_fetch_assoc($totalResult)['total'];
        $totalPages = ceil($totalRows / $limit);

        echo json_encode([
            'success' => true,
            'data' => $staff,
            'pagination' => [
                'totalPages' => $totalPages,
                'currentPage' => $page,
            ]
        ]);
    } elseif ($action === 'get_staff') {
        $id = intval($_GET['id']);

        $query = "SELECT * FROM staff WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $staff = mysqli_fetch_assoc($result);

        if ($staff) {
            echo json_encode(['success' => true, 'data' => $staff]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Data staff tidak ditemukan.']);
        }
    }
}

mysqli_close($conn);
?>
