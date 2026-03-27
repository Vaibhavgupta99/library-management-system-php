<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'library_db');
$msg = isset($_SESSION['success']) ? $_SESSION['success'] : 'Transaction Completed Successfully!';
if(isset($_SESSION['success'])) unset($_SESSION['success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>LibraryMS — Confirmation</title>
    <style>
        * { font-family: 'Inter', sans-serif; }
        h1,h2,h3,h4,h5,h6 { font-family: 'Poppins', sans-serif; }
        body {
            background: #f5f7fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .confirm-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.1);
            padding: 48px 40px;
            text-align: center;
            max-width: 480px;
            width: 100%;
            animation: popIn 0.5s ease;
        }
        @keyframes popIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        .confirm-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(0,184,148,0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        .confirm-icon i { font-size: 2.2rem; color: #00b894; }
    </style>
</head>
<body>
    <div class="confirm-card">
        <div class="confirm-icon"><i class="fas fa-check-circle"></i></div>
        <h3 style="color:#1a1a2e; font-weight:700;">Success!</h3>
        <p style="color:#636e72; margin-bottom:24px;"><?php echo $msg; ?></p>
        <a href="index.php" class="btn" style="background:#e94560; color:#fff; border-radius:50px; padding:10px 32px; font-weight:600; transition:all 0.2s;">
            <i class="fas fa-home me-2"></i>Back to Home
        </a>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
