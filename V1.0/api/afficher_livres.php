<?php
include "db/conn.php";

$stmt = $conn->prepare("SELECT l.id_livre, l.titre, l.auteur, l.isbn, l.annee_publication, l.quantite, l.id_categorie, c.nom AS categorie FROM livres l LEFT JOIN categories c ON l.id_categorie = c.id_categorie ORDER BY l.id_livre DESC");
$stmt->execute();

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$categoriesStmt = $conn->query("SELECT id_categorie, nom FROM categories ORDER BY id_categorie ASC");
$categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);
