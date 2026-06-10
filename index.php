<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM animals WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: index.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM animals ORDER BY id DESC");
$animals = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hayvan Takip Sistemi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f4f7f5; }
        .navbar { background-color: #1e4620 !important; }
        .card-table { border: none; border-radius: 12px; overflow: hidden; }
        .table modern-thead { background-color: #2c3e50; color: white; }
        .badge-health { background-color: #e8f5e9; color: #2e6f40; border: 1px solid #c8e6c9; font-weight: 600; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="index.php">
            <i class="bi bi-tree-fill me-2 fs-4"></i> Hale'nin Hayvanat Bahçesi Takip Sistemi
        </a>
        <div class="d-flex align-items-center">
            <span class="navbar-text text-white me-3 d-none d-sm-inline">
                <i class="bi bi-person-circle me-1"></i> Hoş geldin, <strong class="text-warning"><?= htmlspecialchars($_SESSION['username']) ?></strong>
            </span>
            <a href="logout.php" class="btn btn-outline-light btn-sm rounded-pill px-3">
                <i class="bi bi-box-arrow-right me-1"></i> Çıkış Yap
            </a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row align-items-center mb-4">
        <div class="col">
            <h2 class="fw-bold text-dark m-0"><i class="bi bi-images me-2 text-success"></i>Hayvan Envanteri</h2>
            <small class="text-muted">Barınaktaki tüm kayıtlı hayvanların listesi</small>
        </div>
        <div class="col-auto">
            <a href="add.php" class="btn btn-success shadow-sm rounded-pill px-4 py-2 fw-semibold">
                <i class="bi bi-plus-lg me-2"></i>Yeni Hayvan Ekle
            </a>
        </div>
    </div>
    
    <div class="card card-table shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Hayvan İsmi</th>
                        <th>Türü</th>
                        <th>Sağlık Durumu</th>
                        <th>Yaşam Alanı</th>
                        <th class="text-end pe-4">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($animals as $animal): ?>
                    <tr>
                        <td class="ps-4 fw-bold text-muted">#<?= $animal['id'] ?></td>
                        <td class="fw-semibold text-dark"><?= htmlspecialchars($animal['name']) ?></td>
                        <td><span class="badge bg-secondary text-light rounded-pill px-2 py-1.5"><?= htmlspecialchars($animal['species']) ?></span></td>
                        <td><span class="badge badge-health px-3 py-2 rounded-pill"><?= htmlspecialchars($animal['health_status']) ?></span></td>
                        <td><i class="bi bi-geo-alt-fill text-danger me-1"></i><?= htmlspecialchars($animal['habitat']) ?></td>
                        <td class="text-end pe-4">
                            <div class="btn-group shadow-sm rounded">
                                <a href="edit.php?id=<?= $animal['id'] ?>" class="btn btn-warning btn-sm fw-medium" title="Düzenle">
                                    <i class="bi bi-pencil-square"></i> Düzenle
                                </a>
                                <a href="index.php?delete=<?= $animal['id'] ?>" class="btn btn-danger btn-sm fw-medium" onclick="return confirm('Bu kaydı silmek istediğinize emin misiniz?');" title="Sil">
                                    <i class="bi bi-trash3-fill"></i> Sil
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(!$animals): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox display-4 d-block mb-3 text-secondary"></i>
                            Henüz kayıtlı hayvan bulunmamaktadır.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>