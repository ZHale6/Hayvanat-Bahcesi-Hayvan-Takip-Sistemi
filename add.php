<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $species = $_POST['species'];
    $health = $_POST['health_status'];
    $habitat = $_POST['habitat'];
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("INSERT INTO animals (name, species, health_status, habitat, added_by) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $species, $health, $habitat, $user_id]);
    
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Hayvan Ekle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f4f7f5; }
        .navbar { background-color: #1e4620 !important; }
        .card { border: none; border-radius: 16px; }
    </style>
</head>
<body>
<nav class="navbar navbar-dark shadow-sm mb-5">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="index.php">
            <i class="bi bi-tree-fill me-2 fs-4"></i> Zoo Takip Sistemi
        </a>
    </div>
</nav>

<div class="container">
    <div class="card shadow max-w-md mx-auto" style="max-width: 600px;">
        <div class="card-body p-4">
            <div class="d-flex align-items-center mb-4">
                <div class="p-2 bg-success text-white rounded-3 me-3"><i class="bi bi-plus-circle fs-4"></i></div>
                <div>
                    <h4 class="fw-bold m-0 text-dark">Yeni Hayvan Kaydı</h4>
                    <small class="text-muted">Lütfen tüm alanları eksiksiz doldurun</small>
                </div>
            </div>
            
            <form method="POST">
                <div class="form-floating mb-3">
                    <input type="text" name="name" class="form-control" id="nameInp" placeholder="Hayvanın İsmi" required>
                    <label for="nameInp">Hayvanın İsmi</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="species" class="form-control" id="specInp" placeholder="Türü (Örn: Aslan)" required>
                    <label for="specInp">Türü (Örn: Aslan, Penguen)</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="health_status" class="form-control" id="healthInp" placeholder="Sağlık Durumu" required>
                    <label for="healthInp">Sağlık Durumu</label>
                </div>
                <div class="form-floating mb-4">
                    <input type="text" name="habitat" class="form-control" id="habInp" placeholder="Yaşam Alanı" required>
                    <label for="habInp">Yaşam Alanı (Kafes/Bölge)</label>
                </div>
                
                <div class="d-flex gap-2 justify-content-end">
                    <a href="index.php" class="btn btn-light border rounded-pill px-4">İptal</a>
                    <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm fw-semibold">
                        <i class="bi bi-check-lg me-1"></i> Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>