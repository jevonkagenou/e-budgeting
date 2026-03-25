<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Tidak Ditemukan</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #f8f9fa;
            color: #3a3b45;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }

        h1 {
            font-size: 80px;
            color: #696cff;
            margin: 0;
        }

        p {
            font-size: 18px;
            margin-bottom: 24px;
            color: #6c757d;
        }

        a {
            padding: 10px 24px;
            background-color: #696cff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div>
        <h1>404</h1>
        <h2>Halaman Tidak Ditemukan</h2>
        <p>Maaf, halaman yang Anda cari tidak ada atau sudah dipindahkan.</p>
        <a href="{{ url('/') }}">Kembali ke Beranda</a>
    </div>
</body>

</html>
