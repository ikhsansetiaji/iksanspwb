<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <style>
        body {
            display: flex;
            height: 100vh;
            margin: 0;
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            color: #212529;
        }

        /* Sidebar */
        #sidebar {
            height: 100vh;
            width: 250px;
            background: linear-gradient(180deg, #343a40, #495057);
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px 10px;
            overflow-y: auto;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        #sidebar h3 {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 30px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #f8f9fa;
        }

        #sidebar .nav-link {
            color: #ced4da;
            padding: 15px 20px;
            margin: 5px 0;
            border-radius: 5px;
            transition: all 0.3s ease;
            font-size: 1rem;
            font-weight: 500;
        }

        #sidebar .nav-link:hover {
            background-color: #6c757d;
            color: white;
            transform: translateX(5px);
        }

        #sidebar .nav-link.active {
            background-color: #007bff;
            color: white;
        }

        /* Content Area */
        #content {
            margin-left: 250px;
            flex-grow: 1;
            background-color: #f8f9fa;
            overflow-y: auto;
            padding: 20px;
        }

        /* Navbar */
        .navbar {
            background-color: #007bff;
            color: white;
            box-shadow: 0 4px 2px -2px gray;
        }

        .navbar .navbar-brand {
            font-size: 1.25rem;
            font-weight: bold;
            letter-spacing: 1px;
        }

        /* Dynamic Content */
        #main-content {
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            min-height: 300px;
        }

        .ajax-loading {
            text-align: center;
            font-size: 1.2rem;
            color: #6c757d;
            padding: 40px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            #sidebar {
                width: 100%;
                height: auto;
                position: static;
            }
            #content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside id="sidebar">
        <h3 class="text-center">Admin Panel</h3>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="#" class="nav-link active" data-url="../admin/dashboard/dashboard.php" onclick="loadPage(this)">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link" data-url="../admin/menu_member/menu_member.php" onclick="loadPage(this)">
                    <i class="fas fa-users"></i> Menu Member
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link" data-url="../admin/menu_paket/menu_paket.php" onclick="loadPage(this)">
                    <i class="fas fa-box"></i> Menu Paket
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link" data-url="reports.php" onclick="loadPage(this)">
                    <i class="fas fa-chart-line"></i> Reports
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link" data-url="../admin/menu_staff/menu_staff.php" onclick="loadPage(this)">
                    <i class="fas fa-cogs"></i> Menu Staff
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link" data-url="../admin/absensi_staff/menu_attendance.php" onclick="loadPage(this)">
                    <i class="fas fa-cogs"></i> Absensi Staff
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link" data-url="../admin/masukan/admin_contact_message.php" onclick="loadPage(this)">
                    <i class="fas fa-chart-line"></i> Contact
                </a>
            </li>
            <li class="nav-item">
                <a href="../logout.php" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <div id="content">
        <!-- Navbar -->
        <nav class="navbar navbar-dark">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h1">Admin Dashboard</span>
            </div>
        </nav>

        <!-- Dynamic Content -->
        <div id="main-content" class="container">
            <div class="ajax-loading">Please select a menu option</div>
        </div>
    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Load page content dynamically
        function loadPage(link) {
            const url = link.getAttribute('data-url');

            // Highlight active link
            document.querySelectorAll('.nav-link').forEach(el => el.classList.remove('active'));
            link.classList.add('active');

            // Show loading message
            $('#main-content').html('<div class="ajax-loading">Loading...</div>');

            // Fetch content via AJAX
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'html',
                success: function (data) {
                    $('#main-content').html(data);
                },
                error: function (xhr, status, error) {
                    console.error('Failed to load content:', status, error);
                    $('#main-content').html(`
                        <div class="alert alert-danger">
                            <strong>Error!</strong> Failed to load content. Please try again later.
                        </div>
                    `);
                }
            });
        }

        // Automatically load Dashboard on page load
        $(document).ready(function () {
            const defaultLink = document.querySelector('a[data-url="../admin/dashboard/dashboard.php"]');
            if (defaultLink) {
                loadPage(defaultLink);
            }
        });
    </script>
</body>
</html>
