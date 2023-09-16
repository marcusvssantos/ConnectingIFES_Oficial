<?php

if($f_tipo_de_usuario['tipo'] !== "Professor"){
    echo '<script>window.history.back();</script>';
}

echo "<h3>Olá Professor</h3>";