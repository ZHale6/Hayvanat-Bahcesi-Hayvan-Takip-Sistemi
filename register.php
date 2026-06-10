<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = trim($_POST['username']);
    $pass = $_POST['password'];
    
    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    if ($stmt->execute([$user, $hashed_password])) {
        header("Location: login.php?success=1");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol - Zoo Takip</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #1e4620 0%, #0f2310 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .card {
            border: none;
            border-radius: 16px;
        }
        .btn-success {
            background-color: #2e6f40;
            border-color: #2e6f40;
        }
        .btn-success:hover {
            background-color: #1e4620;
            border-color: #1e4620;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow-lg p-3">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="display-5 text-success mb-2"><i class="bi bi-person-plus-fill"></i></div>
                        <h3 class="fw-bold text-dark m-0">Sisteme Kayıt Ol</h3>
                        <small class="text-muted">Yeni bir kullanıcı hesabı oluşturun</small>
                    </div>
                    
                    <form method="POST">
                        <div class="form-floating mb-3">
                            <input type="text" name="username" class="form-control" id="regUser" placeholder="Kullanıcı Adı" required>
                            <label for="regUser"><i class="bi bi-person me-2"></i>Kullanıcı Adı</label>
                        </div>
                        <div class="form-floating mb-4">
                            <input type="password" name="password" class="form-control" id="regPass" placeholder="Şifre" required>
                            <label for="regPass"><i class="bi bi-lock me-2"></i>Şifre</label>
                        </div>
                        <button type="submit" class="btn btn-success w-100 py-2 fw-semibold rounded-3 shadow-sm">
                            Hesap Oluştur <i class="bi bi-check2-circle ms-1"></i>
                        </button>
                    </form>
                    
                    <div class="mt-4 text-center">
                        <a href="login.php" class="text-decoration-none text-success small fw-medium">
                            <i class="bi bi-arrow-left"></i> Zaten üye misin? Giriş yap
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>