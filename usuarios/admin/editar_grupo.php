<?php
include ('header.php');

$idGrupo = $_GET['id'];

// Buscar informações do grupo no banco de dados
$query = "SELECT * FROM grupos WHERE idGrupo = '$idGrupo'";
$result = mysqli_query($conn, $query);
$grupo = mysqli_fetch_assoc($result);

if(isset($_POST['submit'])) {
    // Recupere os dados do formulário
    $nomeGrupo = $_POST['nomeGrupo'];

    // Verifique se o nome do grupo já existe em outro grupo
    $checkQuery = "SELECT * FROM grupos WHERE nome = '$nomeGrupo' AND idGrupo != '$idGrupo'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if(mysqli_num_rows($checkResult) > 0) {
        echo "O nome do grupo já existe em outro grupo. Por favor, escolha outro nome.";
    } else {
        // Atualize os dados no banco de dados
        $query = "UPDATE grupos SET nome = '$nomeGrupo' WHERE idGrupo = '$idGrupo'";
        $result = mysqli_query($conn, $query);

        if($result) {
            echo "Grupo atualizado com sucesso!";
            header('location: gerenciar_grupos.php');
        } else {
            echo "Erro ao atualizar o grupo.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Grupo</title>
    <!-- Inclua aqui os links para o Bootstrap e quaisquer outros estilos ou scripts necessários -->
</head>
<body>

<div class="container">
    <h2>Editar Grupo</h2>
    <form action="" method="post">
        <div class="form-group">
            <label for="nomeGrupo">Nome do Grupo:</label>
            <input type="text" class="form-control" id="nomeGrupo" name="nomeGrupo" value="<?php echo $grupo['nome']; ?>" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Atualizar</button>
    </form>
</div>

</body>
</html>
