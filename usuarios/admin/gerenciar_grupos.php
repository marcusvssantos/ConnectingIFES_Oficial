<?php
include("header.php");

if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

$query = "SELECT * FROM grupos";
$result = mysqli_query($conn, $query);
$grupos = mysqli_fetch_all($result, MYSQLI_ASSOC);

$queryProfessores = "SELECT professores.idProfessor, usuarios.nome FROM professores JOIN usuarios ON professores.idUsuario = usuarios.idUsuario";
$resultProfessores = mysqli_query($conn, $queryProfessores);
$professores = mysqli_fetch_all($resultProfessores, MYSQLI_ASSOC);

// Consulta para buscar todos os estudantes
$queryEstudantes = "SELECT estudantes.idEstudante, usuarios.nome FROM estudantes JOIN usuarios ON estudantes.idUsuario = usuarios.idUsuario";
$resultEstudantes = mysqli_query($conn, $queryEstudantes);
$estudantes = mysqli_fetch_all($resultEstudantes, MYSQLI_ASSOC);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idEstudante = isset($_POST['estudante']) ? $_POST['estudante'] : null;
    $idProfessor = isset($_POST['professor']) ? $_POST['professor'] : null;

    foreach ($grupos as $grupo) {
        if (isset($_POST['cadastrarProfessor_' . $grupo["idGrupo"]])) {
            $idGrupo = $grupo["idGrupo"];
            $checkQueryProfessor = "SELECT * FROM professoresgrupos WHERE idProfessor = '$idProfessor' AND idGrupo = '$idGrupo'";
            $checkResultProfessor = mysqli_query($conn, $checkQueryProfessor);
            if (mysqli_num_rows($checkResultProfessor) > 0) {
                echo "<script>alert('O professor já está cadastrado neste grupo!');</script>";
            } else {
                $insertQueryProfessor = "INSERT INTO professoresgrupos (idProfessor, idGrupo) VALUES ('$idProfessor', '$idGrupo')";
                if (mysqli_query($conn, $insertQueryProfessor)) {
                    echo "<script>alert('Professor cadastrado no grupo com sucesso!');</script>";
                } else {
                    echo "Erro: " . mysqli_error($conn);
                }
            }
        }

        if (isset($_POST['removerProfessor_' . $grupo["idGrupo"]])) {
            $idGrupo = $grupo["idGrupo"];
            $deleteQueryProfessor = "DELETE FROM professoresgrupos WHERE idProfessor = '$idProfessor' AND idGrupo = '$idGrupo'";
            if (mysqli_query($conn, $deleteQueryProfessor)) {
                echo "<script>alert('Professor removido do grupo com sucesso!');</script>";
            } else {
                echo "Erro: " . mysqli_error($conn);
            }
        }
    }


    // Loop para verificar cada botão de cadastro
    foreach ($grupos as $grupo) {
        if (isset($_POST['cadastrarEstudante_' . $grupo["idGrupo"]])) {
            $idGrupo = $grupo["idGrupo"];
            // Verificar se o estudante já está cadastrado no grupo
            $checkQueryEstudante = "SELECT * FROM estudantesgrupos WHERE idEstudante = '$idEstudante' AND idGrupo = '$idGrupo'";
            $checkResultEstudante = mysqli_query($conn, $checkQueryEstudante);
            if (mysqli_num_rows($checkResultEstudante) > 0) {
                echo "<script>alert('O estudante já está cadastrado neste grupo!');</script>";
            } else {
                $insertQueryEstudante = "INSERT INTO estudantesgrupos (idEstudante, idGrupo) VALUES ('$idEstudante', '$idGrupo')";
                if (mysqli_query($conn, $insertQueryEstudante)) {
                    echo "<script>alert('Estudante cadastrado no grupo com sucesso!');</script>";
                } else {
                    echo "Erro: " . mysqli_error($conn);
                }
            }
        }

        // Loop para verificar cada botão de remoção
        if (isset($_POST['removerEstudante_' . $grupo["idGrupo"]])) {
            $idGrupo = $grupo["idGrupo"];
            $deleteQueryEstudante = "DELETE FROM estudantesgrupos WHERE idEstudante = '$idEstudante' AND idGrupo = '$idGrupo'";
            if (mysqli_query($conn, $deleteQueryEstudante)) {
                echo "<script>alert('Estudante removido do grupo com sucesso!');</script>";
            } else {
                echo "Erro: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Grupos</title>
    <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="../../bootstrap/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container mt-6">
        <h2>Gerenciar Grupos</h2>

        <!-- Botões para alternar entre visualização, cadastro de professores e cadastro de estudantes -->
        <button id="viewGroups" class="btn btn-primary">Visualizar Grupos</button>
        <button id="registerProfessor" class="btn btn-secondary">Cadastrar Professor em Grupo</button>
        <button id="registerEstudante" class="btn btn-info">Cadastrar Estudante em Grupo</button>

        <!-- Seção de visualização de grupos -->
        <div id="groupsSection" class="mt-4">
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
        </div>

        <!-- Seção de cadastro de professores em grupos -->
        <div id="registerProfessorSection" class="mt-4" style="display: none;">
            <form method="POST">
                <div class="mb-3">
                    <label for="professor" class="form-label">Professor</label>
                    <select class="form-control" id="professor" name="professor">
                        <?php
                        foreach ($professores as $professor) {
                            echo "<option value='" . $professor["idProfessor"] . "'>" . $professor["nome"] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nome do Grupo</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $selectedProfessor = $_POST['professor'] ?? $professores[0]['idProfessor'];
                        foreach ($grupos as $grupo) {
                            echo "<tr>";
                            echo "<td>" . $grupo["nome"] . "</td>";

                            $checkQueryProfessor = "SELECT * FROM professoresgrupos WHERE idProfessor = '$selectedProfessor' AND idGrupo = '" . $grupo["idGrupo"] . "'";
                            $checkResultProfessor = mysqli_query($conn, $checkQueryProfessor);
                            if (mysqli_num_rows($checkResultProfessor) > 0) {
                                echo "<td><button type='submit' name='removerProfessor_" . $grupo["idGrupo"] . "' class='btn btn-danger'>Remover</button></td>";
                            } else {
                                echo "<td><button type='submit' name='cadastrarProfessor_" . $grupo["idGrupo"] . "' class='btn btn-success'>Cadastrar</button></td>";
                            }
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </form>
        </div>

        <!-- Seção de cadastro de estudantes em grupos -->
        <div id="registerEstudanteSection" class="mt-4" style="display: none;">
            <form method="POST">
                <div class="mb-3">
                    <label for="estudante" class="form-label">Estudante</label>
                    <select class="form-control" id="estudante" name="estudante" onchange="this.form.submit()">
                        <?php
                        foreach ($estudantes as $estudante) {
                            echo "<option value='" . $estudante["idEstudante"] . "'>" . $estudante["nome"] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nome do Grupo</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $selectedStudent = $_POST['estudante'] ?? $estudantes[0]['idEstudante'];
                        foreach ($grupos as $grupo) {
                            echo "<tr>";
                            echo "<td>" . $grupo["nome"] . "</td>";

                            $checkQueryEstudante = "SELECT * FROM estudantesgrupos WHERE idEstudante = '$selectedStudent' AND idGrupo = '" . $grupo["idGrupo"] . "'";
                            $checkResultEstudante = mysqli_query($conn, $checkQueryEstudante);
                            if (mysqli_num_rows($checkResultEstudante) > 0) {
                                echo "<td><button type='submit' name='removerEstudante_" . $grupo["idGrupo"] . "' class='btn btn-danger'>Remover</button></td>";
                            } else {
                                echo "<td><button type='submit' name='cadastrarEstudante_" . $grupo["idGrupo"] . "' class='btn btn-success'>Cadastrar</button></td>";
                            }
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </form>
        </div>

        <?php
        function isStudentRegisteredInGroup($idEstudante, $idGrupo)
        {
            global $conn;
            $checkQuery = "SELECT * FROM estudantesgrupos WHERE idEstudante = '$idEstudante' AND idGrupo = '$idGrupo'";
            $checkResult = mysqli_query($conn, $checkQuery);
            return mysqli_num_rows($checkResult) > 0;
        }
        ?>

        <script>
            document.getElementById("viewGroups").addEventListener("click", function() {
                document.getElementById("groupsSection").style.display = "block";
                document.getElementById("registerProfessorSection").style.display = "none";
                document.getElementById("registerEstudanteSection").style.display = "none";
            });

            document.getElementById("registerProfessor").addEventListener("click", function() {
                document.getElementById("groupsSection").style.display = "none";
                document.getElementById("registerProfessorSection").style.display = "block";
                document.getElementById("registerEstudanteSection").style.display = "none";
            });

            document.getElementById("registerEstudante").addEventListener("click", function() {
                document.getElementById("groupsSection").style.display = "none";
                document.getElementById("registerProfessorSection").style.display = "none";
                document.getElementById("registerEstudanteSection").style.display = "block";
            });
        </script>
    </div>
</body>

</html>