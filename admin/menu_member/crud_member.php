<?php
require_once '../../helper/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Add Member
    if ($action === 'add') {
        $nama = $_POST['nama'];
        $email = $_POST['email'];
        $nomor_telepon = $_POST['nomor_telepon'];
        $menu_paket_id = $_POST['menu_paket_id'];
        $status = $_POST['status'];

        $query = "INSERT INTO member (nama, email, nomor_telepon, menu_paket_id, status) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'sssss', $nama, $email, $nomor_telepon, $menu_paket_id, $status);

        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message' => 'Member berhasil ditambahkan.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menambahkan member.']);
        }

        mysqli_stmt_close($stmt);
    }

    // Delete Member
    elseif ($action === 'delete') {
        $id = $_POST['id'];

        $query = "DELETE FROM member WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $id);

        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message' => 'Member berhasil dihapus.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus member.']);
        }

        mysqli_stmt_close($stmt);
    }

    elseif ($action === 'update') {
        $id = $_POST['id'];
        $nama = $_POST['nama'];
        $email = $_POST['email'];
        $nomor_telepon = $_POST['nomor_telepon'];
        $menu_paket_id = $_POST['menu_paket_id'];
        $status = $_POST['status'];
    
        $query = "UPDATE member SET nama = ?, email = ?, nomor_telepon = ?, menu_paket_id = ?, status = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'sssssi', $nama, $email, $nomor_telepon, $menu_paket_id, $status, $id);
    
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message' => 'Member berhasil diperbarui.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal memperbarui member.']);
        }
    
        mysqli_stmt_close($stmt);
    }
    
}

elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';

    // Get Members
    if ($action === 'get') {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
        $search = mysqli_real_escape_string($conn, $_GET['search'] ?? '');
        $offset = ($page - 1) * $limit;

        $countQuery = "SELECT COUNT(*) AS total FROM member m 
                       JOIN menu_paket mp ON m.menu_paket_id = mp.id 
                       WHERE m.nama LIKE '%$search%'";
        $countResult = mysqli_query($conn, $countQuery);
        $totalRows = mysqli_fetch_assoc($countResult)['total'];
        $totalPages = ceil($totalRows / $limit);

        $query = "SELECT m.id, m.nama, m.email, m.nomor_telepon, mp.nama AS menu_paket_id, m.status 
                  FROM member m 
                  JOIN menu_paket mp ON m.menu_paket_id = mp.id 
                  WHERE m.nama LIKE '%$search%' 
                  LIMIT $limit OFFSET $offset";
        $result = mysqli_query($conn, $query);

        $members = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $members[] = $row;
        }

        echo json_encode([
            'members' => $members,
            'pagination' => [
                'totalPages' => $totalPages,
                'currentPage' => $page
            ]
        ]);
    }

    // Get Packages
    elseif ($action === 'get_packages') {
        $query = "SELECT id, nama FROM menu_paket";
        $result = mysqli_query($conn, $query);

        $packages = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $packages[] = $row;
        }

        echo json_encode($packages);
    }

    // Get Member by ID for Editing
    // Get Member by ID for Editing
    elseif ($action === 'get_member') {
        $id = $_GET['id'];
        $query = "SELECT * FROM member WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $member = mysqli_fetch_assoc($result);
    
        echo json_encode($member);
    }    

}

mysqli_close($conn);

?>
