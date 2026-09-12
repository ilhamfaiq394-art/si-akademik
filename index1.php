<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SI Akademik</title>
</head>
<body>

    <h1>Selamat datang di SI Akademik</h1>

    <!-- Form Pencarian (GET) -->
    <h3>Form Pencarian Mahasiswa</h3>
    <form action="proses.php" method="GET">
        <label>Cari Mahasiswa:</label>
        <input type="text" name="keyword">
        <button type="submit">Cari</button>
    </form>

    <hr>

    <!-- Form Login (POST) -->
    <h3>Form Login</h3>
    <form action="login.php" method="POST">
        <label>Username:</label>
        <input type="text" name="username"><br><br>
        
        <label>Password:</label>
        <input type="password" name="password"><br><br>
        
        <button type="submit">Login</button>
    </form>

</body>
</html>

