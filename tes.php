<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SweetAlert2 Button Example</title>

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.8/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body>

    <!-- Button that triggers SweetAlert2 -->
    <button id="alertButton" class="btn btn-primary">Klik Saya!</button>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.8/dist/sweetalert2.all.min.js"></script>

    <script>
        // Adding event listener to the button
        document.getElementById('alertButton').addEventListener('click', function() {
            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Kamu akan melakukan aksi ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, lanjutkan!',
                cancelButtonText: 'Tidak, batalkan!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        'Tindakan Berhasil!',
                        'Aksi telah dilanjutkan.',
                        'success'
                    );
                } else if (result.isDismissed) {
                    Swal.fire(
                        'Aksi Dibatalkan',
                        'Tidak ada tindakan yang dilakukan.',
                        'error'
                    );
                }
            });
        });
    </script>

</body>
</html>
