$(document).ready(function () {
    let currentPage = 1;
    const limit = 10;
    let selectedId = null;
    let selectedAction = null;

    // Fungsi untuk memuat data absensi
    function loadAttendance(page = 1, search = '') {
        currentPage = page;
        $('#attendance-list').html('<tr><td colspan="5" class="text-center">Memuat data...</td></tr>');

        $.ajax({
            url: '../admin/absensi_staff/crud_attendance.php',
            method: 'GET',
            data: { action: 'get', page: page, search: search, limit: limit },
            success: function (response) {
                const { data: attendance, pagination } = JSON.parse(response);
                let rows = '';

                if (attendance.length === 0) {
                    rows = '<tr><td colspan="5" class="text-center">Data absensi tidak ditemukan.</td></tr>';
                } else {
                    attendance.forEach(record => {
                        rows += `
                            <tr>
                                <td>${record.id}</td>
                                <td>${record.staff_name}</td>
                                <td>${record.date}</td>
                                <td>${record.status}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm edit-btn" data-id="${record.id}">Edit</button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="${record.id}">Hapus</button>
                                </td>
                            </tr>
                        `;
                    });
                }

                $('#attendance-list').html(rows);
                renderPagination(pagination.totalPages, pagination.currentPage);
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat memuat data absensi.',
                });
            }
        });
    }

    // Fungsi untuk memuat dropdown staff
    function loadStaffDropdown() {
        $.ajax({
            url: '../admin/absensi_staff/crud_attendance.php',
            method: 'GET',
            data: { action: 'get_staff' },
            success: function (response) {
                const staff = JSON.parse(response);
                let options = '<option value="" disabled selected>Pilih Nama Staff</option>';

                if (staff.length === 0) {
                    options += '<option value="" disabled>Tidak ada data staff</option>';
                } else {
                    staff.forEach(person => {
                        options += `<option value="${person.id}">${person.name}</option>`;
                    });
                }

                $('#staff_id').html(options);
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Tidak dapat memuat data staff.',
                });
            }
        });
    }

    // Fungsi untuk merender pagination
    function renderPagination(totalPages, currentPage) {
        let paginationHTML = '';
        for (let i = 1; i <= totalPages; i++) {
            paginationHTML += `<button class="btn ${i === currentPage ? 'btn-primary' : 'btn-outline-primary'} pagination-btn" data-page="${i}">${i}</button>`;
        }
        $('#pagination').html(paginationHTML);
    }

    // Event Listener: Buka modal tambah/edit absensi
    $('#createAttendanceModal').on('show.bs.modal', function () {
        loadStaffDropdown();
    });

    // Event Listener: Reset form modal setelah ditutup
    $('#createAttendanceModal').on('hidden.bs.modal', function () {
        $('#create-attendance-form')[0].reset();
        $('#attendance-id').val('');
        $('#createAttendanceModalLabel').text('Tambah Absensi');
    });

    // Event Listener: Reset modal PIN setelah ditutup
    $('#pinConfirmationModal').on('hidden.bs.modal', function () {
        $('#pin').val('');
    });

    // Event Listener: Simpan absensi (Tambah/Edit)
    $('#create-attendance-form').submit(function (e) {
        e.preventDefault();

        const id = $('#attendance-id').val();
        const action = id ? 'update' : 'add';
        const formData = $(this).serialize();

        $.ajax({
            url: '../admin/absensi_staff/crud_attendance.php',
            method: 'POST',
            data: formData + `&action=${action}`,
            success: function (response) {
                const result = JSON.parse(response);
                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: result.message,
                    });
                    $('#createAttendanceModal').modal('hide');
                    loadAttendance(currentPage);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: result.message,
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat menyimpan data.',
                });
            }
        });
    });

    // Event Listener: Klik tombol Edit
    $(document).on('click', '.edit-btn', function () {
        selectedId = $(this).data('id');
        selectedAction = 'edit';
        $('#pinConfirmationModal').modal('show');
    });

    // Event Listener: Klik tombol Hapus
    $(document).on('click', '.delete-btn', function () {
        selectedId = $(this).data('id');
        selectedAction = 'delete';
        $('#pinConfirmationModal').modal('show');
    });

    // Event Listener: Konfirmasi PIN
    $('#confirm-pin-btn').click(function () {
        const pin = $('#pin').val().trim();

        if (!pin) {
            Swal.fire({
                icon: 'warning',
                title: 'PIN Wajib',
                text: 'Masukkan PIN untuk melanjutkan.',
            });
            return;
        }

        $.ajax({
            url: '../admin/absensi_staff/crud_attendance.php',
            method: 'POST',
            data: { action: 'validate_pin', pin: pin },
            success: function (response) {
                const result = JSON.parse(response);
                if (result.success) {
                    if (selectedAction === 'edit') {
                        $.ajax({
                            url: '../admin/absensi_staff/crud_attendance.php',
                            method: 'GET',
                            data: { action: 'get_attendance', id: selectedId },
                            success: function (response) {
                                const record = JSON.parse(response).data;

                                $('#attendance-id').val(record.id);
                                $('#staff_id').val(record.staff_id);
                                $('#date').val(record.date);
                                $('#status').val(record.status);

                                $('#createAttendanceModalLabel').text('Edit Absensi');
                                $('#createAttendanceModal').modal('show');
                                $('#pinConfirmationModal').modal('hide');
                            },
                            error: function () {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: 'Terjadi kesalahan saat mengambil data absensi.',
                                });
                            }
                        });
                    } else if (selectedAction === 'delete') {
                        $.ajax({
                            url: '../admin/absensi_staff/crud_attendance.php',
                            method: 'POST',
                            data: { action: 'delete', id: selectedId },
                            success: function (response) {
                                const deleteResult = JSON.parse(response);
                                if (deleteResult.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: deleteResult.message,
                                    });
                                    loadAttendance(currentPage);
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        text: deleteResult.message,
                                    });
                                }
                                $('#pinConfirmationModal').modal('hide');
                            },
                            error: function () {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: 'Terjadi kesalahan saat menghapus data.',
                                });
                            }
                        });
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'PIN Salah',
                        text: 'PIN yang Anda masukkan salah.',
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat memvalidasi PIN.',
                });
            }
        });
    });

    // Event Listener: Pagination
    $(document).on('click', '.pagination-btn', function () {
        const page = $(this).data('page');
        loadAttendance(page);
    });

    // Event Listener: Pencarian absensi
    $('#search-attendance').on('input', function () {
        const search = $(this).val();
        loadAttendance(1, search);
    });

    // Load data absensi pertama kali
    loadAttendance();
});
