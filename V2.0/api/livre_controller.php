<?php

header("Content-Type: application/json; charset=UTF-8");

require_once "../db/conn.php";

$method = $_SERVER["REQUEST_METHOD"];

switch ($method) {

  // =========================
  // GET
  // =========================
  case "GET":

    if (isset($_GET["query"])) {
      $query = $_GET['query'];

      $stmt = $conn->prepare(
        "SELECT
          l.id_livre,
          l.titre,
          l.auteur,
          l.isbn,
          l.annee_publication,
          l.quantite,
          l.id_categorie,
          c.nom AS categorie 
          FROM livres l 
          LEFT JOIN categories c 
          ON l.id_categorie = c.id_categorie 
          WHERE LOWER(l.titre) LIKE CONCAT(LOWER(?), '%')
          OR LOWER(l.auteur) LIKE CONCAT(LOWER(?), '%')
          OR LOWER(l.isbn) LIKE CONCAT(LOWER(?), '%')
          ORDER BY l.id_livre DESC"
      );

      $stmt->execute([$query, $query, $query]);

      $livres = $stmt->fetchAll(PDO::FETCH_ASSOC);

      echo json_encode($livres);

    } else {
      $stmt = $conn->query(
        "SELECT l.id_livre, l.titre, l.auteur, l.isbn, l.annee_publication, l.quantite, l.id_categorie, c.nom AS categorie FROM livres l LEFT JOIN categories c ON l.id_categorie = c.id_categorie ORDER BY l.id_livre DESC"
      );

      $livres = $stmt->fetchAll(PDO::FETCH_ASSOC);

      echo json_encode($livres);
    }

    break;


  // =========================
  // POST
  // =========================
  case "POST":

    $data = json_decode(
      file_get_contents("php://input"),
      true
    );

    if (
      !is_array($data) ||
      empty(trim((string) ($data["titre"] ?? ""))) ||
      empty(trim((string) ($data["auteur"] ?? ""))) ||
      empty(trim((string) ($data["isbn"] ?? ""))) ||
      !isset($data["annee_publication"]) ||
      !isset($data["quantite"]) ||
      !isset($data["id_categorie"])
    ) {

      http_response_code(400);

      echo json_encode([
        "error" => "Tous les champs sont obligatoires"
      ]);

      exit;
    }

    $sql = "
        INSERT INTO livres
        (titre, auteur, isbn, annee_publication, quantite, id_categorie)
        VALUES (?, ?, ?, ?, ?, ?)
      ";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
      trim($data["titre"]),
      trim($data["auteur"]),
      trim($data["isbn"]),
      $data["annee_publication"],
      $data["quantite"],
      $data["id_categorie"]
    ]);

    echo json_encode([
      "message" => "Livre ajouté avec succès",
      "id" => $conn->lastInsertId()
    ]);

    break;


  // =========================
  // PUT
  // =========================
  case "PUT":

    $data = json_decode(
      file_get_contents("php://input"),
      true
    );

    if (
      !is_array($data) ||
      empty($data["id_livre"]) ||
      empty(trim((string) ($data["titre"] ?? ""))) ||
      empty(trim((string) ($data["auteur"] ?? ""))) ||
      empty(trim((string) ($data["isbn"] ?? ""))) ||
      !isset($data["annee_publication"]) ||
      !isset($data["quantite"]) ||
      !isset($data["id_categorie"])
    ) {

      http_response_code(400);

      echo json_encode([
        "error" => "ID obligatoire"
      ]);

      exit;
    }

    $sql = "
        UPDATE livres
        SET titre = ?,
          auteur = ?,
          isbn = ?,
          annee_publication = ?,
          quantite = ?,
          id_categorie = ?
        WHERE id_livre = ?
      ";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
      trim($data["titre"]),
      trim($data["auteur"]),
      trim($data["isbn"]),
      $data["annee_publication"],
      $data["quantite"],
      $data["id_categorie"],
      $data["id_livre"]
    ]);

    echo json_encode([
      "message" => "Livre modifié avec succès"
    ]);

    break;


  // =========================
  // DELETE
  // =========================
  case "DELETE":

    $data = json_decode(
      file_get_contents("php://input"),
      true
    );

    if (!is_array($data) || empty($data["id_livre"])) {

      http_response_code(400);

      echo json_encode([
        "error" => "ID obligatoire"
      ]);

      exit;
    }

    $stmt = $conn->prepare(
      "DELETE FROM livres WHERE id_livre = ?"
    );

    $stmt->execute([
      $data["id_livre"]
    ]);

    echo json_encode([
      "message" => "Livre supprimé avec succès"
    ]);

    break;


  // =========================
  // METHOD NOT ALLOWED
  // =========================
  default:

    http_response_code(405);

    echo json_encode([
      "error" => "Méthode HTTP non autorisée"
    ]);

    break;
}
