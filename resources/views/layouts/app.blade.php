<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Asset</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    @include('components.modal-styles')
    <style>
        /* Main Layout Styles */
        body {
            min-height: 100vh;
            display: flex;
            background: #f0f2f5;
            font-family: 'Inter', sans-serif;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background: #fff;
            border-right: 1px solid rgba(0,0,0,0.05);
            padding: 15px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
        }

        .sidebar .nav-link {
            color: #333;
            margin: 5px 0;
            transition: all 0.3s ease;
            border-radius: 10px;
            padding: 10px 15px;
        }

        .sidebar .nav-link:hover {
            background-color: #ffc107;
            color: #000;
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            background: #ffc107;
            color: #000;
            font-weight: 500;
            box-shadow: 0 2px 5px rgba(255, 193, 7, 0.3);
        }

        /* Content Area Styles */
        .content {
            flex-grow: 1;
            padding: 25px;
        }

        /* Card Styles */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            margin-bottom: 25px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        .card-header {
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 20px;
            background: white;
            border-radius: 15px 15px 0 0 !important;
        }

        /* Modern Table Styles */
        .table-responsive {
            border-radius: 15px;
            background: white;
            padding: 0.5rem;
        }

        .table {
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        .table thead th {
            border: none;
            background: transparent;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            padding: 12px 20px;
            color: #6c757d;
            letter-spacing: 0.5px;
        }

        .table tbody td {
            padding: 15px 20px;
            vertical-align: middle;
            border: none;
            background: #fff;
            color: #333;
            font-size: 0.95rem;
        }

        .table tbody tr {
            box-shadow: 0 2px 6px rgba(0,0,0,0.02);
            border-radius: 10px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .table tbody tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            background-color: #fff;
        }

        .table tbody tr td:first-child {
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        .table tbody tr td:last-child {
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        .table tbody tr td {
            background: #f8f9fa;
        }

        /* Badge Styles */
        .status-badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        /* Item Card Style */
        .item-card {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .item-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: rgba(67, 94, 190, 0.1);
            color: #435ebe;
        }

        .item-info {
            display: flex;
            flex-direction: column;
        }

        .item-title {
            font-weight: 500;
            color: #333;
            margin-bottom: 2px;
        }

        .item-subtitle {
            font-size: 0.85rem;
            color: #6c757d;
        }

        /* Button Styles */
        .btn {
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-sm {
            padding: 6px 12px;
            border-radius: 6px;
        }

        .btn-primary {
            background: #435ebe;
            border-color: #435ebe;
            box-shadow: 0 2px 5px rgba(67, 94, 190, 0.3);
        }

        .btn-primary:hover {
            background: #3a51a3;
            border-color: #3a51a3;
            transform: translateY(-1px);
        }

        .btn-warning {
            background: #ffc107;
            border-color: #ffc107;
            color: #000;
        }

        .btn-warning:hover {
            background: #ffb300;
            border-color: #ffb300;
            color: #000;
        }

        .btn-danger {
            background: #ff5b5c;
            border-color: #ff5b5c;
        }

        .btn-danger:hover {
            background: #ff4243;
            border-color: #ff4243;
        }

        /* Modal Base Styles */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1055;
            width: 100%;
            height: 100%;
            overflow-x: hidden;
            overflow-y: auto;
            outline: 0;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-dialog {
            position: relative;
            width: 100%;
            margin: 0.5rem;
            pointer-events: auto;
            max-width: 500px;
            margin: 1.75rem auto;
        }

        .modal-content {
            position: relative;
            display: flex;
            flex-direction: column;
            width: 100%;
            background-color: #fff;
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            outline: 0;
        }

        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1050;
            width: 100vw;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.5) !important;
        }

        /* Kill all animations */
        .modal,
        .modal-dialog,
        .modal-content,
        .modal-backdrop,
        .modal.fade,
        .modal.show,
        .modal-backdrop.fade,
        .modal-backdrop.show {
            transition: none !important;
            animation: none !important;
            transform: none !important;
        }

        /* Optimize performance */
        .modal-dialog {
            transform: translateZ(0);
            backface-visibility: hidden;
            perspective: 1000px;
            will-change: transform;
        }

        /* Clean spacing */
        .modal-header {
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
            background: #fff;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }

        .modal-body {
            padding: 1rem;
            background: #fff;
        }

        .modal-footer {
            padding: 1rem;
            border-top: 1px solid #dee2e6;
            background: #fff;
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
        }

        /* Form fields */
        .modal input,
        .modal select,
        .modal textarea {
            transform: translateZ(0);
            backface-visibility: hidden;
        }

        /* Prevent body shift */
        body.modal-open {
            padding-right: 0 !important;
            overflow: hidden !important;
        }

        /* Form Controls */
        .form-control {
            padding: 10px 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #435ebe;
            box-shadow: 0 0 0 0.2rem rgba(67, 94, 190, 0.25);
        }

        /* Profile Image */
        .foto-profil {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .foto-profil:hover {
            transform: scale(1.05);
        }

        /* Dropdown Arrow Animation */
        .nav-link .fa-chevron-down {
            transition: transform 0.3s ease;
        }

        .nav-link[aria-expanded="true"] .fa-chevron-down {
            transform: rotate(180deg);
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .action-buttons .btn {
            width: 35px;
            height: 35px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #666;
        }

        .empty-state i {
            font-size: 3rem;
            color: #ddd;
            margin-bottom: 15px;
        }

    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column align-items-stretch" style="width: 240px; background: #fff; border-right: 1px solid #eee; min-height: 100vh;">
        <div class="mb-3 text-center">
            <a href="{{ url('profil') }}">
                <img src="..." alt="Foto Profil" class="w-20 h-20 rounded-50% object-cover" />
            </a>
            <h5 class="mt-2 fw-bold">Data Asset</h5>
        </div>
        <p class="mb-2"><span class="fw-bold">Selamat Datang</span><br><b>Admin</p></b>
        <hr>
        <ul class="nav flex-column gap-1">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link d-flex align-items-center {{ Request::is('dashboard') ? 'active bg-warning text-dark' : '' }}" style="border-radius:8px; font-weight:500;">
                    <i class="fa fa-gauge me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center justify-content-between {{ Request::is('kategori*')||Request::is('barang*')||Request::is('lokasi*') ? 'active bg-warning text-dark' : '' }}" 
                   data-bs-toggle="collapse" 
                   href="#datamaster" 
                   role="button" 
                   aria-expanded="{{ Request::is('kategori*')||Request::is('barang*')||Request::is('lokasi*') ? 'true' : 'false' }}"
                   style="border-radius:8px; font-weight:500;">
                    <span><i class="fa fa-database me-2"></i> Data Master</span>
                    <i class="fa fa-chevron-down"></i>
                </a>
                <div class="collapse {{ Request::is('kategori*')||Request::is('barang*')||Request::is('lokasi*')||Request::is('user*') ? 'show' : '' }}" id="datamaster">
                    <ul class="nav flex-column ms-3">
                        <li><a href="{{ route('kategori.index') }}" class="nav-link d-flex align-items-center {{ Request::is('kategori*') ? 'active bg-warning text-dark' : '' }}" style="border-radius:8px;"><i class="fa fa-circle me-2" style="font-size: 8px;"></i> Kategori</a></li>
                        <li><a href="{{ url('barang') }}" class="nav-link d-flex align-items-center {{ Request::is('barang*') ? 'active bg-warning text-dark' : '' }}" style="border-radius:8px;"><i class="fa fa-circle me-2" style="font-size: 8px;"></i> Barang</a></li>
                        <li><a href="{{ url('lokasi') }}" class="nav-link d-flex align-items-center {{ Request::is('lokasi*') ? 'active bg-warning text-dark' : '' }}" style="border-radius:8px;"><i class="fa fa-circle me-2" style="font-size: 8px;"></i> Lokasi</a></li>
                        <li><a href="{{ url('user') }}" class="nav-link d-flex align-items-center {{ Request::is('user*') ? 'active bg-warning text-dark' : '' }}" style="border-radius:8px;"><i class="fa fa-circle me-2" style="font-size: 8px;"></i> User</a></li>
                    </ul>
                </div>
            </li>
            <li><a href="{{ url('asset') }}" class="nav-link d-flex align-items-center {{ Request::is('asset*') ? 'active bg-warning text-dark' : '' }}" style="border-radius:8px; font-weight:500;"><i class="fa fa-barcode me-2"></i> Asset</a></li>
            <li><a href="{{ url('pengajuan') }}" class="nav-link d-flex align-items-center {{ Request::is('pengajuan*') ? 'active bg-warning text-dark' : '' }}" style="border-radius:8px; font-weight:500;"><i class="fa fa-thumbs-up me-2"></i> Pengajuan</a></li>
            <li><a href="{{ url('monitoring') }}" class="nav-link d-flex align-items-center {{ Request::is('monitoring*') ? 'active bg-warning text-dark' : '' }}" style="border-radius:8px; font-weight:500;"><i class="fa fa-eye me-2"></i> Monitoring</a></li>
            <li>
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="nav-link text-danger bg-transparent border-0 d-flex align-items-center" style="border-radius:8px; font-weight:500;">
                        <i class="fa fa-sign-out-alt me-2"></i> SignOut
                    </button>
                </form>
            </li>
        </ul>
    </div>

    <!-- Konten -->
    <div class="content">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Toast Container -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
            <div class="toast-header">
                <i class="fas fa-check-circle text-success me-2"></i>
                <strong class="me-auto">Notifikasi</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body"></div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @include('components.notification-modal')
    @stack('scripts')
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check for session messages
            @if(session('success'))
                showNotification('success', 'Berhasil!', '{{ session('success') }}');
            @endif
            @if(session('warning'))
                showNotification('warning', 'Perhatian!', '{{ session('warning') }}');
            @endif
            @if(session('error'))
                showNotification('error', 'Gagal!', '{{ session('error') }}');
            @endif
        });
    </script>
</body>
</html>
