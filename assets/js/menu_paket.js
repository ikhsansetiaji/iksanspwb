$(document).ready(function () {
    let currentPage = 1;
    const limit = 10;

    // Function to load packages
    function loadPackages(page = 1, search = '') {
        currentPage = page;
        $('#paket-list').html('<tr><td colspan="6" class="text-center">Loading...</td></tr>');

        $.ajax({
            url: '../admin/menu_paket/crud_paket.php',
            method: 'GET',
            data: { action: 'get', page: page, search: search, limit: limit },
            success: function (response) {
                const { packages, pagination } = JSON.parse(response);
                let rows = '';

                if (packages.length === 0) {
                    rows = `<tr><td colspan="6" class="text-center">Paket tidak ditemukan</td></tr>`;
                } else {
                    packages.forEach(pkg => {
                        rows += `<tr>
                                  <td>${pkg.id}</td>
                                  <td>${pkg.nama}</td>
                                  <td>${pkg.deskripsi}</td>
                                  <td>${pkg.harga}</td>
                                  <td>${pkg.durasi}</td>
                                  <td>
                                    <button class="btn btn-warning btn-sm edit-btn" data-id="${pkg.id}">Edit</button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="${pkg.id}">Hapus</button>
                                  </td>
                                </tr>`;
                    });
                }

                $('#paket-list').html(rows);
                renderPagination(pagination.totalPages, pagination.currentPage);
            }
        });
    }

    // Function to render pagination
    function renderPagination(totalPages, currentPage) {
        let paginationHTML = '';
        for (let i = 1; i <= totalPages; i++) {
            paginationHTML += `<button class="btn ${i === currentPage ? 'btn-primary' : 'btn-outline-primary'} pagination-btn" data-page="${i}">${i}</button>`;
        }
        $('#pagination').html(paginationHTML);
    }

    // Handle form submission for creating or updating a package
    $('#create-paket-form').submit(function (e) {
        e.preventDefault();

        const id = $('#paket-id').val();
        const formData = $(this).serialize();

        const action = id ? 'update' : 'add';

        $.ajax({
            url: '../admin/menu_paket/crud_paket.php',
            method: 'POST',
            data: formData + `&action=${action}`,
            success: function (response) {
                const result = JSON.parse(response);
                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: result.message,
                        toast: true,
                        position: 'top-right',
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                    $('#createPaketModal').modal('hide');
                    loadPackages(currentPage);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: result.message,
                        toast: true,
                        position: 'top-right',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                }
            }
        });
    });

    // Handle editing of a package
    $(document).on('click', '.edit-btn', function () {
        const id = $(this).data('id');

        $.ajax({
            url: '../admin/menu_paket/crud_paket.php',
            method: 'GET',
            data: { action: 'get_package', id: id },
            success: function (response) {
                const pkg = JSON.parse(response);

                $('#paket-id').val(pkg.id);
                $('#nama').val(pkg.nama);
                $('#deskripsi').val(pkg.deskripsi);
                $('#harga').val(pkg.harga);
                $('#durasi').val(pkg.durasi);

                $('#createPaketModalLabel').text('Edit Paket');
                $('#createPaketModal').modal('show');
            }
        });
    });

    // Handle deletion of a package
    $(document).on('click', '.delete-btn', function () {
        const id = $(this).data('id');

        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus paket ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../admin/menu_paket/crud_paket.php',
                    method: 'POST',
                    data: { action: 'delete', id: id },
                    success: function (response) {
                        const result = JSON.parse(response);
                        if (result.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: result.message,
                                toast: true,
                                position: 'top-right',
                                timer: 2000,
                                timerProgressBar: true,
                                showConfirmButton: false
                            });
                            loadPackages(currentPage);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: result.message,
                                toast: true,
                                position: 'top-right',
                                timer: 3000,
                                timerProgressBar: true,
                                showConfirmButton: false
                            });
                        }
                    }
                });
            }
        });
    });

    // Handle pagination
    $(document).on('click', '.pagination-btn', function () {
        const page = $(this).data('page');
        loadPackages(page, $('#search-paket').val());
    });

    // Handle search
    $('#search-paket').on('input', function () {
        const search = $(this).val();
        loadPackages(1, search);
    });

    // Initialize data
    loadPackages();
});
