<?php
require_once 'config/config.php';

// Récupérer les produits validés depuis la base
$stmt = $pdo->prepare("SELECT * FROM produits WHERE validation = 1 ORDER BY id DESC");
$stmt->execute();
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>i Nha Trang - Accueil</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <script defer src="assets/js/main.js"></script>
</head>
<body>

  <?php include 'includes/header.php'; ?> <!-- ✅ Le header est appelé ici -->

  <section class="hero">
    <div class="hero-content">
      <img src="assets/images/logo_v3_green.png" class="hero-logo" alt="i Nha Trang">
      <h1>Découvrez<br><span>i Nha Trang</span></h1>
      <h2>Sous le signe de la dégustation</h2>
      <p>Tous les samedis au marché de Weyersheim</p>
      <a href="#produits" class="btn">En savoir plus</a>
    </div>
  </section>

  <section class="products" id="produits">
    <h2>Nos produits</h2>
    <div class="product-grid">
      <?php foreach ($produits as $produit): ?>
        <div class="product-card">
          <img src="uploads/<?= htmlspecialchars($produit['image']) ?>" alt="<?= htmlspecialchars($produit['nom']) ?>">
          <div class="product-info">
            <h3><?= htmlspecialchars($produit['nom']) ?></h3>
            <p><?= nl2br(htmlspecialchars($produit['description'])) ?></p>
            <strong><?= htmlspecialchars($produit['prix']) ?> €</strong> <span>/unité</span>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <?php include 'includes/footer.php'; ?> <!-- ✅ Le footer sera aussi externe -->

</body>
</html>
