<?php
// Koneksi ke database
require '../../helper/koneksi.php';

// Ambil parameter pencarian
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Query untuk mengambil data dengan pencarian
if (!empty($search)) {
    $stmt = $conn->prepare("SELECT * FROM contact_messages 
                            WHERE name LIKE ? OR email LIKE ? OR message LIKE ? 
                            ORDER BY created_at DESC");
    $likeSearch = "%$search%";
    $stmt->bind_param("sss", $likeSearch, $likeSearch, $likeSearch);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
}

// Simpan data pesan
$messages = ($result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>
<head>
    <!-- <meta charset="UTF-8"> -->
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <title>Admin - Pesan Kontak</title>
    <!-- <link rel="stylesheet" href="path/to/bootstrap.css"> -->
</head>
<body>
    <div class="container mt-5">
        <h1>Pesan dari Pengguna</h1>

        <!-- Form Pencarian -->
        <form action="admin_contact_messages.php" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari nama, email, atau pesan..." value="<?= htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary">Cari</button>
            </div>
        </form>

        <!-- Tabel Pesan -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Pesan</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($messages)): ?>
                    <?php foreach ($messages as $index => $message): ?>
                        <tr>
                            <td><?= $index + 1; ?></td>
                            <td><?= htmlspecialchars($message['name']); ?></td>
                            <td><?= htmlspecialchars($message['email']); ?></td>
                            <td><?= htmlspecialchars($message['message']); ?></td>
                            <td><?= htmlspecialchars($message['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada pesan yang ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
