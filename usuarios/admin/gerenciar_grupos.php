<?php
include("header.php");

if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

$query = "SELECT * FROM grupos";
$result = mysqli_query($conn, $query);
$grupos = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Controle de Estudantes</title>
    <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="../../bootstrap/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container mt-6">
        <h2>Gerenciar Grupos</h2>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nome do Grupo</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($grupos as $grupo) :
                        echo "<tr>";
                        echo "<td>" . $grupo["nome"] . "</td>";
                    ?>
                        <td>

                            <a href="editar_grupo.php?id=<?php echo $grupo['idGrupo']; ?>" style="text-decoration: none;">
                                <img src="../../icons/pencil-fill.svg" class="pencil" ; width="16" height="16" alt="Ícone">
                            </a>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="deletar_grupo.php?id=<?php echo $grupo['idGrupo']; ?>" onclick="return confirm('Tem certeza que deseja deletar este grupo?');" style="text-decoration: none;">
                                <img src="../../icons/trash3-fill.svg" width="16" height="16" alt="Ícone">
                            </a>

                        </td>
                    <?php echo "</tr>";
                    endforeach;
                    ?>

                </tbody>
            </table>
        </div>

        <button id="cadastrarGrupo" class="btn btn-success">Cadastrar Novo Grupo</button>
        <script>
            document.getElementById("cadastrarGrupo").addEventListener("click", function() {
                // Redirecionar para a página de cadastro de estudantes
                window.location.href = "cadastrar_grupo.php";
            });
        </script>
    </div>
</body>

</html>