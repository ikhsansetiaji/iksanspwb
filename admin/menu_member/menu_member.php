<?php
require_once '../../helper/koneksi.php';
?>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/menu_member.css">
</head>
<section class="section">
  <div class="section-header d-flex justify-content-between">
    <h1>List Member</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createMemberModal">Tambah Data</button>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <input type="text" id="search-member" class="form-control mb-3" placeholder="Cari Member...">
          <div class="table-responsive">
            <table class="table table-hover table-striped w-100" id="table-1">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nama</th>
                  <th>Email</th>
                  <th>Nomor Telepon</th>
                  <th>Paket</th>
                  <th>Status</th>
                  <th style="width: 150px">Aksi</th>
                </tr>
              </thead>
              <tbody id="member-list"></tbody>
            </table>
          </div>
          <div class="pagination mt-3" id="pagination"></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Modal for Create and Edit -->
<div class="modal fade" id="createMemberModal" tabindex="-1" aria-labelledby="createMemberModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createMemberModalLabel">Tambah/Edit Member</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="create-member-form">
        <div class="modal-body">
          <input type="hidden" id="member-id" name="id">
          <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control" id="nama" name="nama" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          <div class="mb-3">
            <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
            <input type="text" class="form-control" id="nomor_telepon" name="nomor_telepon" required>
          </div>
          <div class="mb-3">
            <label for="menu_paket_id" class="form-label">Paket</label>
            <select class="form-select" id="menu_paket_id" name="menu_paket_id" required></select>
          </div>
          <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status" required>
              <option value="Aktif">Aktif</option>
              <option value="Nonaktif">Nonaktif</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="reset" class="btn btn-warning">Reset</button> <!-- Tombol Reset -->
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>



<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="../assets/js/menu_member.js"></script>
