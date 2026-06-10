<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM animals WHERE id = ?");
$stmt->execute([$id]);
$animal = $stmt->fetch();

if (!$animal) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $species = $_POST['species'];
    $health = $_POST['health_status'];
    $habitat = $_POST['habitat'];

    $update = $pdo->prepare("UPDATE animals SET name=?, species=?, health_status=?, habitat=? WHERE id=?");
    $update->execute([$name, $species, $health, $habitat, $id]);
    
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hayvan Bilgilerini Düzenle</title>
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
    <div class="card shadow mx-auto" style="max-width: 600px;">
        <div class="card-body p-4">
            <div class="d-flex align-items-center mb-4">
                <div class="p-2 bg-warning text-dark rounded-3 me-3"><i class="bi bi-pencil-square fs-4"></i></div>
                <div>
                    <h4 class="fw-bold m-0 text-dark">Hayvan Kaydını Güncelle</h4>
                    <small class="text-muted">Mevcut bilgileri güncelleyip kaydedin</small>
                </div>
            </div>
            
            <form method="POST">
                <div class="form-floating mb-3">
                    <input type="text" name="name" class="form-control" id="nameEdit" value="<?= htmlspecialchars($animal['name']) ?>" placeholder="Hayvanın İsmi" required>
                    <label for="nameEdit">Hayvanın İsmi</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="species" class="form-control" id="specEdit" value="<?= htmlspecialchars($animal['species']) ?>" placeholder="Türü" required>
                    <label for="specEdit">Türü (Örn: Aslan, Penguen)</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="health_status" class="form-control" id="healthEdit" value="<?= htmlspecialchars($animal['health_status']) ?>" placeholder="Sağlık Durumu" required>
                    <label for="healthEdit">Sağlık Durumu</label>
                </div>
                <div class="form-floating mb-4">
                    <input type="text" name="habitat" class="form-control" id="habEdit" value="<?= htmlspecialchars($animal['habitat']) ?>" placeholder="Yaşam Alanı" required>
                    <label for="habEdit">Yaşam Alanı (Kafes/Bölge)</label>
                </div>
                
                <div class="d-flex gap-2 justify-content-end">
                    <a href="index.php" class="btn btn-light border rounded-pill px-4">İptal</a>
                    <button type="submit" class="btn btn-warning rounded-pill px-4 shadow-sm fw-semibold">
                        <i class="bi bi-floppy-fill me-1"></i> Değişiklikleri Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>