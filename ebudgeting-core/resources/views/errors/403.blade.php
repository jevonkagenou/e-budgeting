<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Akses Ditolak</title>
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
            color: #ff5b5c;
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
        <h1>403</h1>
        <h2>Akses Ditolak</h2>
        <p>Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.</p>
        <a href="{{ url('/') }}">Kembali ke Beranda</a>
    </div>
</body>

</html>
