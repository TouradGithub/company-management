<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>لا توجد صلاحيات</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            direction: rtl;
            font-family: 'Cairo', sans-serif;
        }

        .no-permission {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
            color: #495057;
        }

        .no-permission i {
            font-size: 5rem;
            color: #dc3545;
            margin-bottom: 20px;
        }

        .no-permission h1 {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .no-permission p {
            font-size: 1.2rem;
            color: #6c757d;
        }
    </style>

    <!-- Font Awesome for icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

<div class="no-permission">
    <i class="fas fa-exclamation-triangle"></i>
    <h1>ليس لديك أي صلاحيات</h1>
    <p>يرجى التواصل مع مدير النظام لمنحك الصلاحيات اللازمة.</p>
</div>

</body>
</html>
