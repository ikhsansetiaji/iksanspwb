<?php
require_once '../../helper/koneksi.php';
?>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="../assets/css/menu_member.css">
</head>
<section class="section">
    <div class="section-header d-flex justify-content-between">
        <h1>List Staff</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createStaffModal">Tambah Staff</button>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <input type="text" id="search-staff" class="form-control mb-3" placeholder="Cari Staff...">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped w-100" id="table-staff">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Posisi</th>
                                    <th style="width: 150px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="staff-list"></tbody>
                        </table>
                        <div id="pagination" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Tambah/Edit Staff -->
<div class="modal fade" id="createStaffModal" tabindex="-1" aria-labelledby="createStaffModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createStaffModalLabel">Tambah/Edit Staff</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="create-staff-form">
                <div class="modal-body">
                    <input type="hidden" id="staff-id" name="id">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama staff" required>
                    </div>
                    <div class="mb-3">
                        <label for="position" class="form-label">Posisi</label>
                        <input type="text" class="form-control" id="position" name="position" placeholder="Masukkan posisi staff" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="reset" class="btn btn-warning">Reset</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../assets/js/menu_staff.js"></script>
