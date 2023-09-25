<?php
include("header.php");
$id = $_GET['id'];

$usuario_query = mysqli_query($conn, "SELECT * FROM usuarios WHERE idUsuario = $id");
$usuario = mysqli_fetch_assoc($usuario_query);

if (isset($_POST['editar'])) {
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $email = $_POST['email'];
    $tipo = $_POST['tipo'];

    $update_query = mysqli_query($conn, "UPDATE usuarios SET nome = '$nome', sobrenome = '$sobrenome', email = '$email', tipo = '$tipo' WHERE idUsuario = $id");

    if ($update_query) {
        header("Location: admin.php");
    } else {
        echo "Erro ao editar usuário";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="../../bootstrap/js/bootstrap.min.js"></script>
    <title>Editar Usuario</title>
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Cadastro de Usuário</h2>
        <form method="POST">
            <div class="mb-3">
                <input type="text" class="form-control" placeholder="Nome" value="<?php echo $usuario['nome'] ?>" name="nome" required>
            </div>
            <div class="mb-3">
                <input type="text" class="form-control" placeholder="Sobrenome" value="<?php echo $usuario['sobrenome'] ?>" name="sobrenome" required>
            </div>
            <div class="mb-3">
                <input type="email" class="form-control" placeholder="Endereço Email" value="<?php echo $usuario['email'] ?>" name="email" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" placeholder="Senha" value="<?php echo $usuario['senha'] ?>" name="senha" required>
            </div>
            <div class="mb-3">
                <input type="date" class="form-control" name="data" value="<?php echo $usuario['data'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo de Usuário:</label>
                <select class="form-select" name="tipo" id="tipo" required>
                    <option value="<?php echo $usuario['tipo'] ?>" disabled selected><?php echo $usuario['tipo'] ?></option>
                    <option value="Administrador">Administrador</option>
                    <option value="Aluno">Aluno</option>
                    <option value="Professor">Professor</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success" name="editar">Confirmar</button>
        </form>
    </div>
</body>

</html>