<?php
include("../../conexao.php");

session_start();

if ((!isset($_SESSION['login']) == true) and (!isset($_SESSION['senha']) == true)) {
    header('location: ../../index.php');
}

$remetenteChat = $_SESSION['login'];

$destinatarioChat = $_GET['userId'];


$pegaRemetenteChat = mysqli_query($conn, "SELECT * FROM usuarios WHERE email = '$remetenteChat'");
$remetente = mysqli_fetch_assoc($pegaRemetenteChat);

$pegaDestinatarioChat = mysqli_query($conn, "SELECT * FROM usuarios WHERE idUsuario = '$destinatarioChat'");
$destinatario = mysqli_fetch_assoc($pegaDestinatarioChat);

function realizarUploadImagemChat($imagem)
{
    $nome = $imagem["name"];
    $tempNome = $imagem["tmp_name"];
    $erro = $imagem["error"];
    if ($erro === 0) {
        $diretorioDestino = "../../uploads/chat/";
        $nomeUnico = time() . '_' . $nome;
        $caminhoCompleto = $diretorioDestino . $nomeUnico;
        move_uploaded_file($tempNome, $caminhoCompleto);
        return $caminhoCompleto;
    }
    return false;
}

// Verificar se a mensagem foi enviada
if (isset($_POST['send'])) {
    $msg = $_POST['text'];
    $data = date("Y-m-d H:i:s");
    $imagemPublicacao = realizarUploadImagemChat($_FILES["imagemChat"]);


    if ($msg == "") {
        echo "<h3>Não pode enviar uma mensagem em branco</h3>";
    } else {
        // Preparar a consulta SQL
        $stmt = $conn->prepare("INSERT INTO mensagens (`remetente`, `destinatario`, `texto`, `imagem`, `dataEnvio`) VALUES (?, ?, ?, ?, ?)");

        // Verificar se a preparação foi bem-sucedida
        if ($stmt === false) {
            trigger_error("Erro na preparação da consulta: " . $conn->error, E_USER_ERROR);
            exit;
        }

        // Vincular os parâmetros à sua consulta preparada
        $stmt->bind_param("iisss", $remetente['idUsuario'], $destinatario['idUsuario'], $msg, $imagemPublicacao, $data);

        // Executar a consulta preparada
        $success = $stmt->execute();

        // Verificar se a execução foi bem-sucedida
        if ($success) {
            header("Location: " . $_SERVER['PHP_SELF'] . "?userId=" . $destinatarioChat); // Recarrega a página para atualizar as mensagens
        } else {
            echo "<h3>Erro ao enviar a mensagem</h3>" . $stmt->error;
        }

        // Fechar a declaração
        $stmt->close();
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title>Título da página</title>
    <meta charset="utf-8">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            /* altura mínima da viewport */
            margin: 0;
        }

        #messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        #messages-container::-webkit-scrollbar {
            display: none;
            /* Para o Chrome, Safari e Opera */
        }

        .message {
            margin-bottom: 15px;
        }

        .message .bubble {
            border-radius: 5px;
            padding: 10px;
            color: #fff;
        }

        .remetente {
            text-align: right;
        }

        .remetente .bubble {
            background-color: #0B6B1D;
        }

        .destinatario .bubble {
            background-color: #007bff;
        }

        form {
            background-color: #f9f9f9;
            border-top: 1px solid #e1e1e1;
        }

        .data-header {
            padding: 5px;
            text-align: center;
            font-weight: bold;
            margin: 10px 0;
        }
    </style>

</head>

<body>
    <div id="messages-container">

        <?php
        // Buscar mensagens do banco de dados
        $mensagens = mysqli_query($conn, "SELECT * FROM mensagens WHERE (remetente='" . $remetente['idUsuario'] . "' AND destinatario='" . $destinatario['idUsuario'] . "') OR (remetente='" . $destinatario['idUsuario'] . "' AND destinatario='" . $remetente['idUsuario'] . "') ORDER BY dataEnvio ASC");

        $dataAtual = "";

        while ($mensagem = mysqli_fetch_assoc($mensagens)) {
            // ... [Código para processar a data, etc.]
            $dataHora = $mensagem['dataEnvio'];
            $dataMensagem = date("d/m/Y", strtotime($dataHora)); // Formata a data para "dia/mês/ano"

            // Se a data da mensagem for diferente da data atual (ou seja, mudamos o dia)
            if ($dataMensagem != $dataAtual) {
                // Atualize a data atual
                $dataAtual = $dataMensagem;

                // Imprima um cabeçalho com a nova data
                echo "<div class='data-header'>" . $dataAtual . "</div> ";
            }
            // Agora, processamos a mensagem como antes
            $posicao = ($mensagem['remetente'] == $remetente['idUsuario']) ? 'remetente' : 'destinatario';
            echo "<div class='message $posicao'>";
            echo "<div class='bubble'>";
            echo htmlspecialchars($mensagem['texto']); // Use htmlspecialchars para evitar XSS
        
            // Verifique se há uma imagem associada à mensagem
            if (!empty($mensagem['imagem'])) {
                // Ajuste o caminho da imagem se necessário. Aqui estou assumindo que 'imagem' contém o caminho relativo da imagem.
                echo "<div><img src='" . htmlspecialchars($mensagem['imagem']) . "' alt='imagem-chat' style='max-width:100px; max-height:100px;'></div>"; // Ajuste o estilo conforme necessário
            }
        
            $hora = date("H:i", strtotime($dataHora)); // Formata a data para pegar apenas a hora e os minutos
            echo "<div class='timestamp'>" . $hora . "</div>"; // Exibe a hora
            echo "</div>";
            echo "</div>";
        
        }
        ?>
    </div>


    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="text" placeholder="Digite sua mensagem aqui" autocomplete="off">
        <input type="file" name="imagemChat" id="imagemChat" accept="image/*" onchange="checkFile(event)">
        <input type="submit" name="send" value="Enviar">
    </form>

    <script type="text/javascript">
        function checkFile(event) {
            // Seleciona o arquivo
            var file = event.target.files[0];

            // Tamanho máximo em bytes (5MB neste exemplo)
            var maxSize = 2 * 1024 * 1024;

            // Verifica o tamanho do arquivo
            if (file.size > maxSize) {
                alert("O arquivo selecionado é muito grande! Por favor, selecione um arquivo de até 2MB.");
                // Limpa o campo de seleção de arquivo
                event.target.value = "";
            }
        }
    </script>
    <script>
        // Pega o elemento que queremos aplicar o "arrastar para rolar"
        const messagesContainer = document.getElementById('messages-container');

        let isDown = false;
        let startY; // Alterado de startX para startY
        let scrollTop; // Alterado de scrollLeft para scrollTop

        messagesContainer.addEventListener('mousedown', (e) => {
            isDown = true;
            startY = e.pageY - messagesContainer.offsetTop; // Usando pageY e offsetTop
            scrollTop = messagesContainer.scrollTop;
        });

        messagesContainer.addEventListener('mouseleave', () => {
            isDown = false;
        });

        messagesContainer.addEventListener('mouseup', () => {
            isDown = false;
        });

        messagesContainer.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const y = e.pageY - messagesContainer.offsetTop; // Usando pageY
            const walk = (y - startY) * 3; // velocidade de rolagem
            messagesContainer.scrollTop = scrollTop - walk; // Usando scrollTop
        });

        function scrollToBottom() {
            var messagesContainer = document.getElementById('messages-container');
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        // Executa a função quando a página termina de carregar
        window.onload = scrollToBottom;
    </script>


</body>

</html>