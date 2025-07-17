<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>403 - Không có quyền truy cập</title>
    <style>
        body {
            background-color: #f8fafc;
            color: #333;
            font-family: Arial, Helvetica, sans-serif;
            height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        h1 {
            font-size: 72px;
            margin-bottom: 0;
            color: #dc2626;
        }

        p {
            font-size: 20px;
            margin-top: 0;
            margin-bottom: 20px;
        }

        a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #2563eb;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #1d4ed8;
        }
    </style>
</head>

<body>
    <h1>403</h1>
    <p>Bạn không có quyền truy cập trang này.</p>
    <a href="{{ route('trangchu') }}">Quay về trang chủ</a>
</body>

</html>