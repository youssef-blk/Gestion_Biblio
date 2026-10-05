<?php

include "../db/conn.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: ../index.php");
  exit;
}

$idLivre = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$titre = trim($_POST['titre'] ?? '');
$auteur = trim($_POST['auteur'] ?? '');
$isbn = trim($_POST['isbn'] ?? '') ?: null;
$anneePublication = trim($_POST['annee_publication'] ?? '') ?: null;
$quantite = filter_input(INPUT_POST, 'quantite', FILTER_VALIDATE_INT);
$idCategorie = filter_input(INPUT_POST, 'id_categorie', FILTER_VALIDATE_INT);

if (
  $idLivre === false ||
  $idLivre === null ||
  $idLivre < 1 ||
  $titre === '' ||
  $auteur === '' ||
  $quantite === false ||
  $quantite === null ||
  $quantite < 0 ||
  $idCategorie === false ||
  $idCategorie === null
) {
  header("Location: ../index.php?erreur=donnees_invalides");
  exit;
}

$stmt = $conn->prepare(
  "UPDATE livres
   SET titre = :titre,
       auteur = :auteur,
       isbn = :isbn,
       annee_publication = :annee_publication,
       quantite = :quantite,
       id_categorie = :id_categorie
   WHERE id_livre = :id_livre"
);

$stmt->execute([
  ':titre' => $titre,
  ':auteur' => $auteur,
  ':isbn' => $isbn,
  ':annee_publication' => $anneePublication,
  ':quantite' => $quantite,
  ':id_categorie' => $idCategorie,
  ':id_livre' => $idLivre,
]);

header("Location: ../index.php");
exit;
