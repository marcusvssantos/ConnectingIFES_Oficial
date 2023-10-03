<?php
include("header.php");

if (!isset($_GET['id'])) {
    die("ID não fornecido.");
}

$id = $_GET['id'];

if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// Deleta referências ao grupo na tabela estudantesgrupos
$sql1 = "DELETE FROM estudantesgrupos WHERE idGrupo = ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("i", $id);
$stmt1->execute();
$stmt1->close();

// Deleta referências ao grupo na tabela professoresgrupos
$sql2 = "DELETE FROM professoresgrupos WHERE idGrupo = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $id);
$stmt2->execute();
$stmt2->close();

// Deleta referências ao grupo na tabela publicacoesgrupos
$sql3 = "DELETE FROM publicacoesgrupos WHERE idGrupo = ?";
$stmt3 = $conn->prepare($sql3);
$stmt3->bind_param("i", $id);
$stmt3->execute();
$stmt3->close();

// Deleta o grupo da tabela Grupos
$sql4 = "DELETE FROM grupos WHERE idGrupo = ?";
$stmt4 = $conn->prepare($sql4);
$stmt4->bind_param("i", $id);
$stmt4->execute();
$stmt4->close();

$conn->close();

header("Location: gerenciar_grupos.php");
?>
