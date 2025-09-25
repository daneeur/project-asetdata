<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Profil User</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f4f4f4;
    }

    .menu-btn {
      font-size: 20px;
      padding: 10px 20px;
      background: #333;
      color: #fff;
      border: none;
      cursor: pointer;
    }

    .sidebar {
      position: fixed;
      top: 0;
      left: -250px;
      width: 250px;
      height: 100%;
      background: #444;
      color: #fff;
      padding: 20px;
      transition: left 0.3s ease;
    }

    .sidebar.active {
      left: 0;
    }

    .sidebar ul {
      list-style: none;
      padding: 0;
    }

    .sidebar ul li {
      margin: 15px 0;
    }

    .sidebar ul li a {
      color: white;
      text-decoration: none;
    }

    .content {
      margin-left: 150px;
      padding: 20px;
    }

    .profile-card {
      background: #fff;
      padding: 120px;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
      max-width: 700px;
      text-align: center;
    }

    .profile-card img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      display: block;
      margin: 0 auto 15px auto;
      object-fit: cover;
    }

    input[type="file"] {
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <!-- Konten Profil -->
  <div class="content">
    <div class="profile-card">
      <img id="profileImage" src="https://via.placeholder.com/120" alt="Foto Profil">
      <h2>Nama User</h2>
      <p>Email: user@email.com</p>
      <p>Alamat: Jakarta, Indonesia</p>

      <!-- Upload Foto -->
      <input type="file" id="fileInput" accept="image/*">
    </div>
  </div>

  <div class="flex flex-col items-center mt-12">
    <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data" class="flex flex-col items-center gap-4">
        @csrf
        @method('PUT')
  <img src="{{ optional($user)->foto ? url('storage/' . $user->foto) : asset('default.png') }}" alt="Foto Profil"
            class="rounded-full w-48 h-48 object-cover mb-4 border shadow">
        <input type="file" name="foto" class="border rounded px-3 py-2">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan Foto</button>
    </form>
    <h1 class="text-3xl font-bold mt-6">{{ optional($user)->name ?? '-' }}</h1>
    <p class="mt-2 text-lg">Email: {{ optional($user)->email ?? '-' }}</p>
    <p class="mt-2 text-lg">Alamat: {{ optional($user)->alamat ?? '-' }}</p>
</div>

  <script>
    function toggleMenu() {
      document.getElementById("sidebar").classList.toggle("active");
    }

    // Preview Foto
    document.getElementById("fileInput").addEventListener("change", function(event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          document.getElementById("profileImage").src = e.target.result;
        }
        reader.readAsDataURL(file);
      }
    });
  </script>

</body>
</html>
