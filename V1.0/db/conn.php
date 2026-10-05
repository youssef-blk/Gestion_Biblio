<?php
$host = "localhost";
$dbname = "gestion_bibliotheque";
$port = "3306";
$user = "root";
$password = "";

try {
  $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  http_response_code(500);

  echo json_encode([
    "error" => "Erreur de connexion à la base de données"
  ]);

  exit;
}
