<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Asset</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            background: #f8f9fa;
        }
        .sidebar {
            width: 250px;
            background: #fff;
            border-right: 1px solid #ddd;
            padding: 15px;
        }
        .sidebar .nav-link {
            color: #333;
            margin: 5px 0;
        }
        .sidebar .nav-link.active {
            background: #f0f0f0;
            font-weight: bold;
        }
        .content {
            flex-grow: 1;
            padding: 20px;
        }
        .foto-profil {
          width: 150px;        /* ukuran bebas */
          height: 150px;
          object-fit: cover;   /* biar gambar nggak gepeng */
          border-radius: 50%;  /* bikin bulat */
          border: 2px solid #ccc; /* opsional, buat pinggiran */
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
                <a class="nav-link d-flex align-items-center justify-content-between" data-bs-toggle="collapse" href="#datamaster" role="button" style="border-radius:8px; font-weight:500;">
                    <span><i class="fa fa-database me-2"></i> Data Master</span>
                    <i class="fa fa-chevron-down"></i>
                </a>
                <div class="collapse {{ Request::is('kategori*')||Request::is('barang*')||Request::is('lokasi*')||Request::is('user*') ? 'show' : '' }}" id="datamaster">
                    <ul class="nav flex-column ms-3">
                        <li><a href="{{ route('kategori.index') }}" class="nav-link {{ Request::is('kategori*') ? 'active bg-secondary text-white' : '' }}" style="border-radius:8px;">Kategori</a></li>
                        <li><a href="{{ url('barang') }}" class="nav-link {{ Request::is('barang*') ? 'active bg-secondary text-white' : '' }}" style="border-radius:8px;">Barang</a></li>
                        <li><a href="{{ url('lokasi') }}" class="nav-link {{ Request::is('lokasi*') ? 'active bg-secondary text-white' : '' }}" style="border-radius:8px;">Lokasi</a></li>
                    </ul>
                </div>
            </li>
            <li><a href="{{ url('asset') }}" class="nav-link d-flex align-items-center" style="border-radius:8px; font-weight:500;"><i class="fa fa-barcode me-2"></i> Asset</a></li>
            <li><a href="{{ url('pengajuan') }}" class="nav-link d-flex align-items-center" style="border-radius:8px; font-weight:500;"><i class="fa fa-thumbs-up me-2"></i> Pengajuan</a></li>
            <li><a href="{{ url('monitoring') }}" class="nav-link d-flex align-items-center" style="border-radius:8px; font-weight:500;"><i class="fa fa-eye me-2"></i> Monitoring</a></li>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
