<?php
include("header.php");

if (!isset($_GET['id'])) {
    die("ID não fornecido.");
}

$id = $_GET['id'];

if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// Deleta o grupo da tabela Grupos
$sql = "DELETE FROM grupos WHERE idGrupo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$stmt->close();
$conn->close();

header("Location: gerenciar_grupos.php");
?>
