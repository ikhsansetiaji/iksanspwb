$(document).ready(function () {
    let currentPage = 1;
    const limit = 10;

    // Function to load members
    function loadMembers(page = 1, search = '') {
        currentPage = page;
        $('#member-list').html('<tr><td colspan="7" class="text-center">Loading...</td></tr>');

        $.ajax({
            url: '../admin/menu_member/crud_member.php',
            method: 'GET',
            data: { action: 'get', page: page, search: search, limit: limit },
            success: function (response) {
                const { members, pagination } = JSON.parse(response);
                let rows = '';

                if (members.length === 0) {
                    rows = `<tr><td colspan="7" class="text-center">Member tidak ditemukan</td></tr>`;
                } else {
                    members.forEach(member => {
                        rows += `<tr>
                                  <td>${member.id}</td>
                                  <td>${member.nama}</td>
                                  <td>${member.email}</td>
                                  <td>${member.nomor_telepon}</td>
                                  <td>${member.menu_paket_id}</td>
                                  <td>${member.status}</td>
                                  <td>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="${member.id}">Hapus</button>
                                    <button class="btn btn-warning btn-sm edit-btn" data-id="${member.id}">Edit</button>

                                  </td>
                                </tr>`;
                    });                    
                }

                $('#member-list').html(rows);
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

    // Function to load packages into dropdown
    function loadPackages(dropdownId) {
        $.ajax({
            url: '../admin/menu_member/crud_member.php',
            method: 'GET',
            data: { action: 'get_packages' },
            success: function (response) {
                const packages = JSON.parse(response);
                let options = '';
                packages.forEach(pkg => {
                    options += `<option value="${pkg.id}">${pkg.nama}</option>`;
                });
                $(`#${dropdownId}`).html(options);
            }
        });
    }

    // Handle form submission for creating a new member
    $('#create-member-form').submit(function (e) {
        e.preventDefault();
    
        const id = $('#member-id').val();
        const formData = $(this).serialize();
    
        const action = id ? 'update' : 'add'; // Jika ID ada, lakukan update, jika tidak, lakukan tambah baru.
    
        $.ajax({
            url: '../admin/menu_member/crud_member.php',
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
                    $('#createMemberModal').on('hidden.bs.modal', function () {
                        $(this).find('form')[0].reset(); // Reset form
                        $(this).removeClass('fade'); // Hapus efek fade (opsional)
                        $('.modal-backdrop').remove(); // Hapus backdrop overlay
                        $('body').removeClass('modal-open'); // Pastikan body tidak terkunci
                    });                    
                    loadMembers(currentPage);
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

    // Handle editing of member
    $(document).on('click', '.edit-btn', function () {
        const id = $(this).data('id');

        $.ajax({
            url: '../admin/menu_member/crud_member.php',
            method: 'GET',
            data: { action: 'get_member', id: id },
            success: function (response) {
                const member = JSON.parse(response);

                $('#member-id').val(member.id);
                $('#nama').val(member.nama);
                $('#email').val(member.email);
                $('#nomor_telepon').val(member.nomor_telepon);
                $('#menu_paket_id').val(member.menu_paket_id);
                $('#status').val(member.status);

                $('#createMemberModalLabel').text('Edit Member');
                $('#createMemberModal').modal('show');
            }
        });
    });

    



    // Search for members
    $('#search-member').on('input', function () {
        const search = $(this).val();
        loadMembers(1, search);
    });

    // Handle deletion of member
    $(document).on('click', '.delete-btn', function () {
        const id = $(this).data('id');

        Swal.fire({
            title: 'Masukkan PIN Konfirmasi',
            input: 'password',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal',
            showLoaderOnConfirm: true,
            preConfirm: (pin) => {
                return new Promise((resolve, reject) => {
                    const correctPin = '1234';
                    if (pin !== correctPin) {
                        reject('PIN salah');
                    } else {
                        $.ajax({
                            url: '../admin/menu_member/crud_member.php',
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
                                        timer: 2500,
                                        timerProgressBar: true,
                                        showConfirmButton: false
                                    });
                                    loadMembers(currentPage);
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
            },
            allowOutsideClick: () => !Swal.isLoading()
        });
    });

    $(document).on('click', '.pagination-btn', function () {
        const page = $(this).data('page');
        loadMembers(page, $('#search-member').val());
    });

    // Initialize page and load data
    loadMembers();
    loadPackages('menu_paket_id');
});

document.querySelector('button[type="reset"]').addEventListener('click', function() {
    document.getElementById('create-member-form').reset();
  });
  
