<!DOCTYPE html>
<html>
<head>
    <title>Financial Data Export</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
        p {
            line-height: 1.6;
        }
        a {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            color: #fff;
            background: #007bff;
            text-decoration: none;
            border-radius: 5px;
        }
        a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Financial Data Export</h1>
        <p>Your {{ $title }} data export is ready. Download it within 30 minutes or the link will expire.</p>
        <p>You can download it using the link below:</p>
        <a href="{{ $downloadLink }}">Download CSV</a>
    </div>
</body>
</html>
