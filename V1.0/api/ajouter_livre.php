<?php
include "../db/conn.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: ../index.php");
  exit;
}

$titre = trim($_POST['titre'] ?? '');
$auteur = trim($_POST['auteur'] ?? '');
$isbn = trim($_POST['isbn'] ?? '') ?: null;
$anneePublication = trim($_POST['annee_publication'] ?? '') ?: null;
$quantite = filter_input(INPUT_POST, 'quantite', FILTER_VALIDATE_INT);
$idCategorie = filter_input(INPUT_POST, 'id_categorie', FILTER_VALIDATE_INT);

if ($titre === '' || $auteur === '' || $quantite === false || $quantite === null || $quantite < 0 || $idCategorie === false || $idCategorie === null) {
  header("Location: ../index.php?erreur=donnees_invalides");
  exit;
}

$stmt = $conn->prepare(
  "INSERT INTO livres (titre, auteur, isbn, annee_publication, quantite, id_categorie)
     VALUES (:titre, :auteur, :isbn, :annee_publication, :quantite, :id_categorie)"
);
$stmt->execute([
  ':titre' => $titre,
  ':auteur' => $auteur,
  ':isbn' => $isbn,
  ':annee_publication' => $anneePublication,
  ':quantite' => $quantite,
  ':id_categorie' => $idCategorie,
]);

header("Location: ../index.php");
exit;
