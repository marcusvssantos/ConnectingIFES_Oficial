<?php
include("../../conexao.php");
include("header.php");

if (isset($_POST['entrar'])) {
    try {
        $verifica = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $verifica->execute(array($_POST['email']));
        $verificando = $verifica->rowCount();

        if ($verificando >= 1) { // Verificação de existência do Email.
            echo "<h3>Este Email já está registrado!</h3>";
        } elseif ($_POST['nome'] == '' || strlen($_POST['nome']) < 3) { // Verificação de Validade do Nome
            echo "<h3>Nome Inválido!</h3>";
        } elseif ($_POST['sobrenome'] == '') { // Verificação de Validade do Sobrenome
            echo "<h3>Sobrenome Inválido!</h3>";
        } elseif ($_POST['email'] == '' || strlen($_POST['email']) < 3) { // Verificação de Validade do Email
            echo "<h3>Email Inválido!</h3>";
        } elseif ($_POST['senha'] == '' || strlen($_POST['senha']) < 3) { // Verificação de Validade da Senha
            echo "<h3>Senha Inválida!</h3>";
        } else {
            // Conexão com o banco de dados usando MySQLi

            if ($conn->connect_error) {
                die("Conexão falhou: " . $conn->connect_error);
            }

            $nome = $_POST['nome'];
            $sobrenome = $_POST['sobrenome'];
            $email = $_POST['email'];
            $senha = $_POST['senha'];
            $data = $_POST['data'];
            $tipo = $_POST['tipo']; // Adicionando o campo 'tipo'

            // Preparando a declaração SQL com parâmetros
            $stmt = $conn->prepare("INSERT INTO usuarios (nome, sobrenome, email, senha, data, tipo) VALUES (?, ?, ?, ?, ?, ?)");

            // Vinculando os parâmetros e seus tipos
            $stmt->bind_param("ssssss", $nome, $sobrenome, $email, $senha, $data, $tipo);

            // Executando a inserção
            if ($stmt->execute()) {
                header("location: admin.php");
            } else {
                echo "<h3>Ocorreu um erro ao cadastrar o usuário.</h3>";
            }

            // Fechando a conexão
            $stmt->close();
            $conn->close();
        }
    } catch (PDOException $erro) {
        echo $erro->getMessage();
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
    <title>Cadastro de Usuário</title>
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Cadastro de Usuário</h2>
        <form method="POST">
            <div class="mb-3">
                <input type="text" class="form-control" placeholder="Nome" name="nome" required>
            </div>
            <div class="mb-3">
                <input type="text" class="form-control" placeholder="Sobrenome" name="sobrenome" required>
            </div>
            <div class="mb-3">
                <input type="email" class="form-control" placeholder="Endereço Email" name="email" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" placeholder="Senha" name="senha" required>
            </div>
            <div class="mb-3">
                <input type="date" class="form-control" name="data" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo de Usuário:</label>
                <select class="form-select" name="tipo" id="tipo" required>
                    <option value="" disabled selected>Selecione o tipo de usuário</option>
                    <option value="Administrador">Administrador</option>
                    <option value="Aluno">Aluno</option>
                    <option value="Professor">Professor</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success" name="entrar">Criar Conta</button>
        </form>
    </div>
    <button type="submit" class="btn btn-success" name="entrar">Criar Conta</button>
    </form>
    </div>

    <script src="../../bootstrap/js/bootstrap.min.js"></script>
</body>

</html>


