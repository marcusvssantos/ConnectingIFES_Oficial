<?php
include("conexao.php");

$tipo_de_usuario = mysqli_query($conn, "SELECT * FROM usuarios WHERE idUsuario = '$login_cookie'");
$f_tipo_de_usuario = mysqli_fetch_assoc($tipo_de_usuario);

if($f_tipo_de_usuario['tipo']=="Aluno"){
    header("Location: usuarios/aluno/aluno.php");
    exit(); 
}elseif($f_tipo_de_usuario['tipo']=="Professor"){
    header("Location: usuarios/professor/professor.php");
    exit(); 
}elseif($f_tipo_de_usuario['tipo']=="Administrador"){
    header("Location: usuarios/admin/admin.php");
    exit(); 
}
?>

