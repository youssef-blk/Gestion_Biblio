<?php
include "../db/conn.php";

if ($_SERVER['REQUEST_METHOD'] == "GET") {
  $id_livre = $_GET['id_livre'];

  $stmt = $conn->prepare("DELETE FROM livres Where id_livre = ?");
  $stmt->execute([$id_livre]);
  header("Location: ../index.php");

}
?>
