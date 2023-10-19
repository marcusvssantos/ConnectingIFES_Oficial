<?php
include("../../conexao.php");

session_start();

if ((!isset($_SESSION['login']) == true) and (!isset($_SESSION['senha']) == true)) {
  header('location: ../../index.php');
}


if (isset($_POST['sair'])) {
  unset($_SESSION['login']);
  unset($_SESSION['senha']);
}


$logado = $_SESSION['login'];


$tipo_de_usuario = mysqli_query($conn, "SELECT * FROM usuarios WHERE email = '$logado'");
$f_tipo_de_usuario = mysqli_fetch_assoc($tipo_de_usuario);


$sql = "SELECT * FROM usuarios"; // ajuste a consulta conforme necessário
$resultado = $conn->query($sql);

if ($f_tipo_de_usuario['tipo'] !== "professor") {
  echo '<script>window.history.back();</script>';
}

function conectarDB()
{
  $conexao = new mysqli("localhost", "root", "", "connecting_ifes_oficial");
  if ($conexao->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conexao->connect_error);
  }
  return $conexao;
}

function realizarUploadImagem($imagem)
{
  $nome = $imagem["name"];
  $tempNome = $imagem["tmp_name"];
  $erro = $imagem["error"];
  if ($erro === 0) {
    $diretorioDestino = "uploads/publicações/";
    $nomeUnico = time() . '_' . $nome;
    $caminhoCompleto = $diretorioDestino . $nomeUnico;
    move_uploaded_file($tempNome, $caminhoCompleto);
    return $caminhoCompleto;
  }
  return false;
}

if (isset($_POST['post_publicacao'])) {
  $titulo = $_POST["tituloPost"];
  $conteudo = $_POST["conteudoPost"];
  $dataPublicacao = date('Y-m-d H:i:s');
  $idProfessor = $f_tipo_de_usuario['idUsuario'];
  $idGrupo = $_POST['grupo'];
  $imagemPublicacao = realizarUploadImagem($_FILES["imagemPost"]);

  $conexao = conectarDB();

  // Insere a publicação na tabela "publicacoes"
  $sqlPublicacao = "INSERT INTO connecting_ifes_oficial.publicacoes (titulo, conteudo, imagemPublicacao, dataPublicacao, idProfessor) VALUES (?, ?, ?, ?, ?)";
  $stmtPublicacao = $conexao->prepare($sqlPublicacao);
  $stmtPublicacao->bind_param("ssssi", $titulo, $conteudo, $imagemPublicacao, $dataPublicacao, $idProfessor);
  if ($stmtPublicacao->execute()) {
    $idPublicacao = $stmtPublicacao->insert_id;
    $sqlPublicacaoGrupo = "INSERT INTO connecting_ifes_oficial.publicacoesgrupos (idPublicacao, idGrupo) VALUES (?, ?)";
    $stmtPublicacaoGrupo = $conexao->prepare($sqlPublicacaoGrupo);
    $stmtPublicacaoGrupo->bind_param("ii", $idPublicacao, $idGrupo);
    if ($stmtPublicacaoGrupo->execute()) {
      echo "Publicação realizada com sucesso!";
      header("Refresh: 1; url=" . $_SERVER['PHP_SELF']);
    } else {
      echo "Erro ao inserir na tabela publicacoesgrupos: " . $conexao->error;
    }
  } else {
    echo "Erro ao inserir na tabela publicacoes: " . $conexao->error;
  }

  $conexao->close();
}



$currentPage = basename($_SERVER['PHP_SELF']);


?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" type="image/png" sizes="32x32" href="../../img/Logo ConnectingIFES.png">
  <link rel="stylesheet" type="text/css" href="estilo_professor.css" media="screen" />
</head>

<body>


  <div class="topbar">
    <div class="left-items">
      <a><img src="<?php echo $f_tipo_de_usuario["fotoPerfil"]; ?>"></a>
      <a style="color: #FFFFFF;"> <?php echo $f_tipo_de_usuario["nome"] . " " . $f_tipo_de_usuario["sobrenome"]; ?> </a>
    </div>
    <div class="center-items">
      <a style="color: #FFFFFF;"> Área do Professor </a>
    </div>
    <div class="right-items">
      <a style="color: #FFFFFF;"> ConnectingIFES </a>
      <form class="nav navbar-nav navbar-right" method="POST">
        <button type="submit" class="btn btn-danger" name="sair">Sair</button>
      </form>
      &nbsp&nbsp;
    </div>
  </div>


  <div class="sidebar">
    <a href="index_professor.php"><i class="bi bi-house-door"></i></a>
    <a href="editar_professor.php"><i class="bi bi-gear"></i></a>
    <a href="meu_perfil.php"><i class="bi bi-people"></i></a>
    <a href="#contact"><i class="bi bi-envelope"></i></a>
    <?php if ($currentPage == "meu_perfil.php" || $currentPage == "index_professor.php") : ?>
      <a href="#contact" data-toggle="modal" data-target="#postModal" title="Adicionar Nova Publicação"><i class="bi bi-file-earmark-plus"></i></a>
    <?php endif; ?>
  </div>


  <div class="sidebar-right">
    <div class="friend-item">
      <a><i style="color: #FFFFFF;" class="bi bi-chat-dots"></i></a>
      <a style="color: #FFFFFF;"> Conversas </a>
    </div>

    <?php
    if ($resultado->num_rows > 0) {
      while ($usuario = $resultado->fetch_assoc()) {
        echo '<div class="friend-item" onclick="openChat(\'' . $usuario['nome'] . '\', \'' . $usuario['idUsuario'] . '\')">';
        echo '  <a href="javascript:void(0);"><img src="../../img/ifes-vertical-cor.png" alt="Amigo"></a>'; // ajuste o caminho da imagem conforme necessário
        echo '  <a href="javascript:void(0);" style="color: #FFFFFF;">' . $usuario['nome'] . '</a>'; // assumindo que 'nome' é a coluna com o nome do usuário
        echo '</div>';
      }
    } else {
      echo "0 resultados"; // Nenhum usuário encontrado
    }
    ?>

  </div>
  </div>



  <!-- Modal de Publicação -->
  <div class="modal fade" id="postModal" tabindex="-1" role="dialog" aria-labelledby="postModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="postModalLabel">Adicionar Postagem</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="index_professor.php" method="post" enctype="multipart/form-data">
            <!-- Título da postagem -->
            <div class="form-group">
              <label for="tituloPost">Título</label>
              <input type="text" class="form-control" id="tituloPost" name="tituloPost" placeholder="Digite o título da postagem">
            </div>

            <!-- Conteúdo da postagem -->
            <div class="form-group">
              <label for="conteudoPost">Conteúdo</label>
              <textarea class="form-control" id="conteudoPost" name="conteudoPost" rows="4" placeholder="Digite o conteúdo da postagem"></textarea>
            </div>

            <!-- Upload de imagem -->
            <div class="form-group">
              <label for="imagemPost">Imagem</label>
              <input type="file" class="form-control" id="imagemPost" name="imagemPost" onchange="checkFiles(event)">
            </div>

            <!-- Seleção do grupo -->
            <div class="form-group">
              <label for="grupo">Grupo</label>
              <select class="form-control" id="grupo" name="grupo">
                <?php
                $query_professor_id = "SELECT idProfessor FROM professores WHERE idUsuario = '{$f_tipo_de_usuario['idUsuario']}'";
                $resultado_professor_id = mysqli_query($conn, $query_professor_id);
                if (!$resultado_professor_id) {
                  die("Erro na consulta: " . mysqli_error($conn));
                }
                $professor_data = mysqli_fetch_assoc($resultado_professor_id);
                $id_professor = $professor_data['idProfessor'];

                $query_grupos = mysqli_query($conn, "SELECT g.idGrupo, g.nome FROM professoresgrupos pg JOIN grupos g ON pg.idGrupo = g.idGrupo WHERE pg.idProfessor = '$id_professor'");

                // Preencher o <select> com os grupos
                while ($grupo = mysqli_fetch_assoc($query_grupos)) {
                  echo "<option value='{$grupo['idGrupo']}'>{$grupo['nome']}</option>";
                }
                ?>
              </select>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
              <button type="submit" name='post_publicacao' class="btn btn-primary">Publicar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Estrutura básica do chat -->
  <div id="chatPopup" class="chat-popup">
    <div id="chatHeader" class="chat-header">
      <span id="chatUsername">Nome do Usuário</span>
      <span class="chat-header-buttons">
        <button class="close" onclick="closeChat()">x</button>
        <button class="close" id="chatMinimize" class="chat-minimize">-&nbsp;&nbsp;</button>
        <button class="close" id="chatMaximize" class="chat-maximize" style="visibility: hidden;">+</button>
      </span>
    </div>
    <div id="chatContent" class="chat-content">
      <iframe id="chatFrame" src="chat.php" style="width:100%; height:100%; border:none;"></iframe>
    </div>

  </div>




  <script>
    // Função para abrir o chat
    function openChat(nome, userId) {
      document.getElementById('chatPopup').style.display = 'block';
      document.getElementById('chatUsername').textContent = nome;

      // Definir o 'src' do iframe com um parâmetro de consulta para o ID do usuário
      var chatFrame = document.getElementById('chatFrame'); // Supondo que 'chatFrame' é o ID do seu iframe
      chatFrame.src = 'chat.php?userId=' + userId;
    }

    // Função para minimizar o chat
    function minimizeChat() {
      document.getElementById('chatContent').style.display = 'none';
      document.getElementById('chatMinimize').style.visibility = 'hidden';
      document.getElementById('chatMaximize').style.visibility = 'visible';
    }

    // Função para maximizar o chat
    function maximizeChat() {
      document.getElementById('chatContent').style.display = 'block';
      document.getElementById('chatMinimize').style.visibility = 'visible';
      document.getElementById('chatMaximize').style.visibility = 'hidden';
    }

    // Enviar mensagem
    function sendMessage() {
      var message = document.getElementById("chatInput").value;
      if (message) { // Se houver uma mensagem
        document.getElementById("chatInput").value = ""; // Limpar o input
      }
    }

    // Event listeners para os botões de minimizar e maximizar
    document.getElementById('chatMinimize').addEventListener('click', minimizeChat);
    document.getElementById('chatMaximize').addEventListener('click', maximizeChat);

    function closeChat() {
      document.getElementById("chatPopup").style.display = "none";
    }
  </script>
  <script type="text/javascript">
        function checkFiles(event) {
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

  <!-- Incluindo Bootstrap JS (opcional) -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
<script src="../../bootstrap/js/bootstrap.min.js"></script>
</body>

</html>