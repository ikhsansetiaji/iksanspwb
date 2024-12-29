<?php
require_once '../../helper/koneksi.php';
?>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/menu_member.css">
</head>
<section class="section">
  <div class="section-header d-flex justify-content-between">
    <h1>List Paket</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPaketModal">Tambah Paket</button>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <input type="text" id="search-paket" class="form-control mb-3" placeholder="Cari Paket...">
          <div class="table-responsive">
            <table class="table table-hover table-striped w-100" id="table-1">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nama</th>
                  <th>Deskripsi</th>
                  <th>Harga</th>
                  <th>Durasi (hari)</th>
                  <th style="width: 150px">Aksi</th>
                </tr>
              </thead>
              <tbody id="paket-list"></tbody>
            </table>
          </div>
          <div class="pagination mt-3" id="pagination"></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Modal for Create and Edit -->
<div class="modal fade" id="createPaketModal" tabindex="-1" aria-labelledby="createPaketModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createPaketModalLabel">Tambah/Edit Paket</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="create-paket-form">
        <div class="modal-body">
          <input type="hidden" id="paket-id" name="id">
          <div class="mb-3">
            <label for="nama" class="form-label">Nama Paket</label>
            <input type="text" class="form-control" id="nama" name="nama" required>
          </div>
          <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" required></textarea>
          </div>
          <div class="mb-3">
            <label for="harga" class="form-label">Harga</label>
            <input type="number" class="form-control" id="harga" name="harga" step="0.01" required>
          </div>
          <div class="mb-3">
            <label for="durasi" class="form-label">Durasi (hari)</label>
            <input type="number" class="form-control" id="durasi" name="durasi" required>
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

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="../assets/js/menu_paket.js"></script>
