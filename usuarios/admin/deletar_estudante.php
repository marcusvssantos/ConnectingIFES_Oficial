<?php
include("header.php");

if (!isset($_GET['id'])) {
    die("ID não fornecido.");
}

$id = $_GET['id'];

if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// Recupera o caminho da foto de perfil do estudante
$sql = "SELECT fotoPerfil FROM Usuarios WHERE idUsuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($fotoPerfil);
$stmt->fetch();
$stmt->close();

// Se a foto de perfil existir, deleta o arquivo do servidor
if ($fotoPerfil && file_exists($fotoPerfil)) {
    unlink($fotoPerfil);
}

// Deleta o estudante da tabela Estudantes
$sql = "DELETE FROM Estudantes WHERE idUsuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

// Deleta o usuário da tabela Usuarios
$sql = "DELETE FROM Usuarios WHERE idUsuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

$conn->close();

header("Location: gerenciar_estudante.php");
?>
