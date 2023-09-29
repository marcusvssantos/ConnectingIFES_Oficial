<?php

include("header.php");

if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// Consulta SQL para recuperar os professores
$sql = "SELECT Usuarios.idUsuario, Usuarios.nome, Usuarios.sobrenome, Usuarios.email, Usuarios.fotoPerfil, Professores.siape, Professores.departamento FROM Usuarios INNER JOIN Professores ON Usuarios.idUsuario = Professores.idUsuario";
$resultado = $conn->query($sql);

if (!$resultado) {
    die("Erro na consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Controle de Professores</title>
    <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="../../bootstrap/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container mt-6">
        <h1>Painel de controle de Professores</h1>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Sobrenome</th>
                        <th>Email</th>
                        <th>Foto de Perfil</th>
                        <th>SIAPE</th>
                        <th>Departamento</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($linha = $resultado->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $linha["nome"] . "</td>";
                        echo "<td>" . $linha["sobrenome"] . "</td>";
                        echo "<td>" . $linha["email"] . "</td>";
                        echo '<td><img src="' . $linha["fotoPerfil"] . '" style="border-radius: 50%; width: 50px; height: 50px;" alt="Foto de Perfil"></td>';
                        echo "<td>" . $linha["siape"] . "</td>";
                        echo "<td>" . $linha["departamento"] . "</td>";
                        ?>
                        <td>
                            <a href="editar_professor.php?id=<?php echo $linha['idUsuario']; ?>" style="text-decoration: none;">
                                <img src="../../icons/pencil-fill.svg" class="pencil" ; width="16" height="16" alt="Ícone">
                            </a>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="#" style="text-decoration: none;">
                                <img src="../../icons/trash3-fill.svg" width="16" height="16" alt="Ícone">
                            </a>
                        </td>
                    <?php
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <button id="cadastrarProfessor" class="btn btn-success">Cadastrar Novo Professor</button>
        <script>
            document.getElementById("cadastrarProfessor").addEventListener("click", function() {
                // Redirecionar para a página de cadastro de professores
                window.location.href = "cadastrar_professor.php";
            });
        </script>
    </div>
</body>

</html>
