<?php
include("header.php");


if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// Consulta SQL para recuperar os administradores
$sql = "SELECT Usuarios.idUsuario, Usuarios.nome, Usuarios.sobrenome, Usuarios.email, Usuarios.fotoPerfil FROM Usuarios INNER JOIN Administradores ON Usuarios.idUsuario = Administradores.idUsuario";
$resultado = $conn->query($sql);

if (!$resultado) {
    die("Erro na consulta: " . $conn->error);
}

$usuarios_query = mysqli_query($conn, "SELECT * FROM usuarios");
$usuarios = [];

while ($row = mysqli_fetch_assoc($usuarios_query)) {
    $usuarios[] = $row;
}

?>

