<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Deteksi Method Request</title>
</head>
<body>

    <h2>Deteksi Method Request PHP</h2>

    <?php
    // Memeriksa method request yang sedang aktif //
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'POST') {
        echo "<p style='color: green; font-weight: bold;'>Status: Anda mengirim data menggunakan method <u>POST</u>!</p>";
    } else {
        echo "<p style='color: navy; font-weight: bold;'>Status: Anda mengakses halaman ini menggunakan method <u>GET</u>!</p>";
    }
    ?>

    <hr>
    <!--Pemisah baris -->

    <!--Pengiriman GET -->
    <h3>Tes Method GET</h3>
    <form action="" method="GET">
        <button type="submit">Kirim request GET</button>
    </form>

    <br>

    <!--Pengiriman POST -->
    <h3>Tes Method POST</h3>
    <form action="" method="POST">
        <button type="submit">Kirim request POST</button>
    </form>

</body>
</html>
