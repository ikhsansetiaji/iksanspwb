<?php
require_once '../../helper/koneksi.php';
?>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<section class="section">
    <div class="section-header d-flex justify-content-between">
        <h1>Absensi Staff</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAttendanceModal">Tambah Absensi</button>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <input type="text" id="search-attendance" class="form-control mb-3" placeholder="Cari Absensi...">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped w-100" id="table-attendance">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Staff</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th style="width: 150px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="attendance-list"></tbody>
                        </table>
                        <div id="pagination" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Konfirmasi PIN -->
<div class="modal fade" id="pinConfirmationModal" tabindex="-1" aria-labelledby="pinConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pinConfirmationModalLabel">Konfirmasi PIN</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="pin" class="form-label">Masukkan PIN Anda</label>
                    <input type="password" class="form-control" id="pin" placeholder="PIN konfirmasi" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="confirm-pin-btn" class="btn btn-primary">Konfirmasi</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Absensi -->
<div class="modal fade" id="createAttendanceModal" tabindex="-1" aria-labelledby="createAttendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createAttendanceModalLabel">Tambah/Edit Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="create-attendance-form">
                <div class="modal-body">
                    <input type="hidden" id="attendance-id" name="id">
                    <div class="mb-3">
                        <label for="staff_id" class="form-label">Nama Staff</label>
                        <select class="form-select" id="staff_id" name="staff_id" required>
                            <option value="" disabled selected>Pilih Nama Staff</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="Hadir">Hadir</option>
                            <option value="Tidak Hadir">Tidak Hadir</option>
                            <option value="Izin">Izin</option>
                            <option value="Sakit">Sakit</option>
                        </select>
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
<script src="../assets/js/menu_attendance.js"></script>
