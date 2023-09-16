<?php

if($f_tipo_de_usuario['tipo'] !== "Aluno"){
    echo '<script>window.history.back();</script>';
}

echo "<h3>Olá Aluno</h3>";