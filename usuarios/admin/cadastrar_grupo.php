<?php
include('header.php');

if(isset($_POST['submit'])) {
    // Recupere os dados do formulário
    $nomeGrupo = $_POST['nomeGrupo'];

    // Verifique se o nome do grupo já existe
    $checkQuery = "SELECT * FROM grupos WHERE nome = '$nomeGrupo'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if(mysqli_num_rows($checkResult) > 0) {
        echo "O nome do grupo já existe. Por favor, escolha outro nome.";
    } else {
        // Insira os dados no banco de dados
        $query = "INSERT INTO grupos (nome) VALUES ('$nomeGrupo')";
        $result = mysqli_query($conn, $query);

        if($result) {
            echo "Grupo cadastrado com sucesso!";
            header('location: gerenciar_grupos.php');
        } else {
            echo "Erro ao cadastrar o grupo.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Grupo</title>
    <!-- Inclua aqui os links para o Bootstrap e quaisquer outros estilos ou scripts necessários -->
</head>
<body>

<div class="container">
    <h2>Cadastrar Novo Grupo</h2>
    <form action="" method="post">
        <div class="form-group">
            <label for="nomeGrupo">Nome do Grupo:</label>
            <input type="text" class="form-control" id="nomeGrupo" name="nomeGrupo" required>
        </div>
        <button type="submit" name="submit" class="btn btn-success">Cadastrar</button>
    </form>
</div>

</body>
</html>
