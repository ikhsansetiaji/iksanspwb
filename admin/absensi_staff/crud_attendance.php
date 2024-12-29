<?php
require_once '../../helper/koneksi.php';

function validatePin($pin) {
    $correctPin = '1234'; // Ganti PIN dengan PIN yang valid
    return $pin === $correctPin;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $staff_id = intval($_POST['staff_id']);
        $date = $_POST['date'];
        $status = $_POST['status'];

        if (empty($staff_id) || empty($date) || empty($status)) {
            echo json_encode(['success' => false, 'message' => 'Semua data wajib diisi.']);
            exit();
        }

        $query = "INSERT INTO staff_attendance (staff_id, date, status) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'iss', $staff_id, $date, $status);

        if ($stmt && mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message' => 'Absensi berhasil ditambahkan.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menambahkan absensi.']);
        }

        mysqli_stmt_close($stmt);
    } elseif ($action === 'validate_pin') {
        $pin = $_POST['pin'] ?? '';
        echo json_encode(['success' => validatePin($pin)]);
    } elseif ($action === 'update') {
        $id = intval($_POST['id']);
        $staff_id = intval($_POST['staff_id']);
        $date = $_POST['date'];
        $status = $_POST['status'];

        if (empty($staff_id) || empty($date) || empty($status)) {
            echo json_encode(['success' => false, 'message' => 'Semua data wajib diisi.']);
            exit();
        }

        $query = "UPDATE staff_attendance SET staff_id = ?, date = ?, status = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'issi', $staff_id, $date, $status, $id);

        if ($stmt && mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message' => 'Absensi berhasil diperbarui.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal memperbarui absensi.']);
        }

        mysqli_stmt_close($stmt);
    } elseif ($action === 'delete') {
        $id = intval($_POST['id']);

        $query = "DELETE FROM staff_attendance WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $id);

        if ($stmt && mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message' => 'Absensi berhasil dihapus.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus absensi.']);
        }

        mysqli_stmt_close($stmt);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';

    if ($action === 'get') {
        $page = intval($_GET['page'] ?? 1);
        $limit = intval($_GET['limit'] ?? 10);
        $offset = ($page - 1) * $limit;

        $query = "SELECT sa.id, sa.date, sa.status, s.name AS staff_name 
                  FROM staff_attendance sa 
                  JOIN staff s ON sa.staff_id = s.id 
                  ORDER BY sa.date DESC LIMIT ? OFFSET ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'ii', $limit, $offset);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $attendance = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $attendance[] = $row;
        }

        $totalQuery = "SELECT COUNT(*) AS total FROM staff_attendance";
        $totalResult = mysqli_query($conn, $totalQuery);
        $totalRows = mysqli_fetch_assoc($totalResult)['total'];
        $totalPages = ceil($totalRows / $limit);

        echo json_encode([
            'success' => true,
            'data' => $attendance,
            'pagination' => [
                'totalPages' => $totalPages,
                'currentPage' => $page,
            ]
        ]);
    } elseif ($action === 'get_staff') {
        $query = "SELECT id, name FROM staff";
        $result = mysqli_query($conn, $query);

        $staff = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $staff[] = $row;
        }

        echo json_encode($staff);
    }
}

mysqli_close($conn);
