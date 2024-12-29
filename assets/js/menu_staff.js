$(document).ready(function () {
    let currentPage = 1;
    const limit = 10;

    function loadStaff(page = 1, search = '') {
        currentPage = page;
        $('#staff-list').html('<tr><td colspan="4" class="text-center">Memuat data...</td></tr>');

        $.ajax({
            url: '../admin/menu_staff/crud_staff.php',
            method: 'GET',
            data: { action: 'get', page: page, search: search, limit: limit },
            success: function (response) {
                const { data: staff, pagination } = JSON.parse(response);
                let rows = '';

                if (staff.length === 0) {
                    rows = '<tr><td colspan="4" class="text-center">Data staff tidak ditemukan.</td></tr>';
                } else {
                    staff.forEach(person => {
                        rows += `<tr>
                            <td>${person.id}</td>
                            <td>${person.name}</td>
                            <td>${person.position}</td>
                            <td>
                                <button class="btn btn-warning btn-sm edit-btn" data-id="${person.id}">Edit</button>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${person.id}">Hapus</button>
                            </td>
                        </tr>`;
                    });
                }

                $('#staff-list').html(rows);
                renderPagination(pagination.totalPages, pagination.currentPage);
            }
        });
    }

    function renderPagination(totalPages, currentPage) {
        let paginationHTML = '';
        for (let i = 1; i <= totalPages; i++) {
            paginationHTML += `<button class="btn ${i === currentPage ? 'btn-primary' : 'btn-outline-primary'} pagination-btn" data-page="${i}">${i}</button>`;
        }
        $('#pagination').html(paginationHTML);
    }

    $('#create-staff-form').submit(function (e) {
        e.preventDefault();

        const id = $('#staff-id').val();
        const formData = $(this).serialize();
        const action = id ? 'update' : 'add';

        $.ajax({
            url: '../admin/menu_staff/crud_staff.php',
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
                    $('#createStaffModal').modal('hide');
                    loadStaff(currentPage);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: result.message,
                    });
                }
            }
        });
    });

    $(document).on('click', '.edit-btn', function () {
        const id = $(this).data('id');

        $.ajax({
            url: '../admin/menu_staff/crud_staff.php',
            method: 'GET',
            data: { action: 'get_staff', id: id },
            success: function (response) {
                const { data: person } = JSON.parse(response);

                $('#staff-id').val(person.id);
                $('#name').val(person.name);
                $('#position').val(person.position);

                $('#createStaffModalLabel').text('Edit Staff');
                $('#createStaffModal').modal('show');
            }
        });
    });

    $(document).on('click', '.delete-btn', function () {
        const id = $(this).data('id');

        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus staff ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../admin/menu_staff/crud_staff.php',
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
                            loadStaff(currentPage);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: result.message,
                            });
                        }
                    }
                });
            }
        });
    });

    $(document).on('click', '.pagination-btn', function () {
        const page = $(this).data('page');
        loadStaff(page, $('#search-staff').val());
    });

    $('#search-staff').on('input', function () {
        const search = $(this).val();
        loadStaff(1, search);
    });

    $('#createStaffModal').on('hidden.bs.modal', function () {
        $('#create-staff-form')[0].reset();
        $('#staff-id').val('');
        $('#createStaffModalLabel').text('Tambah Staff');
    });

    loadStaff();
});
