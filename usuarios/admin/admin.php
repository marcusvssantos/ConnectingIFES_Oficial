<?php
include("../../conexao.php");
include("header.php");


$logado = $_SESSION['login'];

// Busque os dados da tabela 'usuarios'
$usuarios_query = mysqli_query($conn, "SELECT * FROM usuarios");

// Inicialize um array para armazenar os resultados
$usuarios = [];

while ($row = mysqli_fetch_assoc($usuarios_query)) {
    $usuarios[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaPainel de Controle</title>
    <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="../../bootstrap/js/bootstrap.min.js"></script>
    

</head>

<body>
    <div class="container mt-5">
        <h1>Painel de controle do Administrador</h1>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Tipo de Usuário</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario) { ?>
                        <tr>
                            <td><?php echo $usuario['idUsuario']; ?></td>
                            <td><?php echo $usuario['nome'] . " " . $usuario['sobrenome']; ?></td>
                            <td><?php echo $usuario['email']; ?></td>
                            <td><?php echo $usuario['tipo']; ?></td>
                            <td>

                            <a href="editar.php?id=<?php echo $usuario['idUsuario']; ?>" style="text-decoration: none;">
                                    <img src="../../icons/pencil-fill.svg" class="pencil" ; width="16" height="16" alt="Ícone">
                                </a>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="#" style="text-decoration: none;">
                                    <img src="../../icons/trash3-fill.svg" width="16" height="16" alt="Ícone">
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <button id="cadastrarUsuario" class="btn btn-success">Cadastrar Novo Usuário</button>
        <script>
            document.getElementById("cadastrarUsuario").addEventListener("click", function() {
                // Redirecionar para a página de cadastro
                window.location.href = "cadastro_usuario.php";
            });
        </script>
    </div>

    
</body>

</html>