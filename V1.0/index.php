<?php
include "api/afficher_livres.php";
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion de Bibliothèque - Catalogue & Inventaire (V1)</title>
  <!-- Google Fonts: Plus Jakarta Sans & Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
  <!-- FontAwesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="assets/style.css">
</head>

<body>

  <!-- Main Container -->
  <main class="main-container">

    <?php if (isset($_GET['erreur']) && $_GET['erreur'] === 'donnees_invalides'): ?>
      <div class="alert alert-danger">
        <div class="alert-icon">
          <i class="fa-solid fa-circle-exclamation"></i>
        </div>
        <div class="alert-content">
          <h4>Données invalides</h4>
          <p>Veuillez vérifier que tous les champs obligatoires sont correctement remplis.</p>
        </div>
      </div>
    <?php endif; ?>

    <div class="app-grid">
      <!-- Form Section (Ajouter / Modifier) -->
      <section class="card form-card" id="form-section">
        <div class="card-header">
          <div class="card-header-icon primary-tint">
            <i class="fa-solid fa-square-plus"></i>
          </div>
          <div>
            <h2 class="card-title">Gestion d'un Livre</h2>
            <p class="card-subtitle">Ajouter ou modifier un ouvrage</p>
          </div>
        </div>

        <div class="card-body">
          <form action="api/ajouter_livre.php" method="POST" id="formLivre" class="crud-form">
            <input type="hidden" id="id_livre" name="id" value="">

            <div class="form-group">
              <label for="titre">Titre du livre <span class="required">*</span></label>
              <div class="input-wrapper">
                <i class="fa-solid fa-book input-icon"></i>
                <input type="text" id="titre" name="titre" placeholder="ex: L'Étranger" required autocomplete="off">
              </div>
            </div>

            <div class="form-group">
              <label for="auteur">Auteur <span class="required">*</span></label>
              <div class="input-wrapper">
                <i class="fa-solid fa-user-pen input-icon"></i>
                <input type="text" id="auteur" name="auteur" placeholder="ex: Albert Camus" required autocomplete="off">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="id_categorie">Catégorie <span class="required">*</span></label>
                <div class="input-wrapper">
                  <i class="fa-solid fa-layer-group input-icon"></i>
                  <select id="id_categorie" name="id_categorie" required>
                    <option value="" disabled selected>Sélectionner...</option>
                    <?php if (!empty($categories) && is_array($categories)): ?>
                      <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['id_categorie']) ?>">
                          <?= htmlspecialchars($cat['nom']) ?>
                        </option>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <option value="1">Roman</option>
                      <option value="2">Science</option>
                      <option value="3">Histoire</option>
                      <option value="4">Informatique</option>
                      <option value="5">Developpement personnel</option>
                      <option value="6">Philosophie</option>
                      <option value="7">Economie</option>
                      <option value="8">Biographie</option>
                      <option value="9">Jeunesse</option>
                      <option value="10">Policier</option>
                    <?php endif; ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label for="isbn">Code ISBN</label>
                <div class="input-wrapper">
                  <i class="fa-solid fa-barcode input-icon"></i>
                  <input type="text" id="isbn" name="isbn" maxlength="20" placeholder="ex: 978-2070360024" autocomplete="off">
                </div>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="annee_publication">Année de publication</label>
                <div class="input-wrapper">
                  <i class="fa-solid fa-calendar-days input-icon"></i>
                  <input type="number" id="annee_publication" name="annee_publication" min="0" max="9999" placeholder="ex: 1942">
                </div>
              </div>

              <div class="form-group">
                <label for="quantite">Quantité en stock <span class="required">*</span></label>
                <div class="input-wrapper">
                  <i class="fa-solid fa-boxes-stacked input-icon"></i>
                  <input type="number" id="quantite" name="quantite" min="0" placeholder="ex: 12" required>
                </div>
              </div>
            </div>

            <div class="form-actions">
              <button type="submit" class="btn btn-primary" id="formBtn">
                <i class="fa-solid fa-plus"></i>
                <span>Ajouter au catalogue</span>
              </button>
              <button type="reset" class="btn btn-secondary">
                <i class="fa-solid fa-rotate-left"></i>
                <span>Réinitialiser</span>
              </button>
            </div>
          </form>
        </div>
      </section>

      <!-- Catalogue Table Section -->
      <section class="card table-card" id="liste-livres">
        <div class="card-header space-between">
          <div class="header-title-group">
            <div class="card-header-icon indigo-tint">
              <i class="fa-solid fa-list-check"></i>
            </div>
            <div>
              <h2 class="card-title">Catalogue des Livres</h2>
              <p class="card-subtitle">Consulter, modifier et supprimer les ouvrages</p>
            </div>
          </div>
          
          <div class="search-box">
            <i class="fa-solid fa-magnifying-glass search-icon"></i>
            <input type="text" id="search-input" placeholder="Rechercher par titre, auteur, ISBN..." autocomplete="off">
          </div>
        </div>

        <div class="card-body padding-none">
          <div class="table-responsive">
            <table class="data-table">
              <thead>
                <tr>
                  <th style="width: 55px;">ID</th>
                  <th>Titre du livre</th>
                  <th>Auteur</th>
                  <th>Catégorie</th>
                  <th>ISBN</th>
                  <th>Année</th>
                  <th>Stock</th>
                  <th class="text-center" style="width: 170px;">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($data) && is_array($data)): ?>
                  <?php foreach ($data as $livre): ?>
                    <tr data-book-id="<?= htmlspecialchars($livre['id_livre']) ?>" data-category-id="<?= htmlspecialchars($livre['id_categorie']) ?>">
                      <td><strong><?= htmlspecialchars($livre['id_livre']) ?></strong></td>
                      <td><div class="book-title"><?= htmlspecialchars($livre['titre']) ?></div></td>
                      <td><?= htmlspecialchars($livre['auteur']) ?></td>
                      <td>
                        <span class="category-tag tag-<?= htmlspecialchars($livre['id_categorie'] ?? '') ?>">
                          <?= htmlspecialchars($livre['categorie'] ?? ($livre['id_categorie'] ? 'Catégorie ' . $livre['id_categorie'] : 'Sans catégorie')) ?>
                        </span>
                      </td>
                      <td><?= htmlspecialchars($livre['isbn'] ?: '-') ?></td>
                      <td><?= htmlspecialchars($livre['annee_publication'] ?: '-') ?></td>
                      <td>
                        <span class="stock-badge <?= (int)$livre['quantite'] > 0 ? 'stock-available' : 'stock-low' ?>">
                          <?= htmlspecialchars($livre['quantite']) ?>
                        </span>
                      </td>
                      <td class="text-center actions-cell">
                        <button type="button" class="btn-icon btn-edit" title="Modifier">
                          <i class="fa-solid fa-pen-to-square"></i> Modifier
                        </button>
                        <a href="api/supprimer_livre.php?id_livre=<?= htmlspecialchars($livre['id_livre']) ?>" 
                           class="btn-icon btn-delete" 
                           title="Supprimer" 
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce livre ?');">
                          <i class="fa-solid fa-trash-can"></i> Supprimer
                        </a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="8" class="text-center">Aucun livre trouvé dans le catalogue.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </section>
    </div>
  </main>

  <!-- Scripts -->
  <script src="js/script.js"></script>
  <script>
    // Search input live filtering
    document.addEventListener("DOMContentLoaded", () => {
      const searchInput = document.getElementById("search-input");
      if (searchInput) {
        searchInput.addEventListener("input", (e) => {
          const term = e.target.value.toLowerCase().trim();
          const rows = document.querySelectorAll(".data-table tbody tr");
          rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(term) ? "" : "none";
          });
        });
      }
    });
  </script>
</body>

</html>
