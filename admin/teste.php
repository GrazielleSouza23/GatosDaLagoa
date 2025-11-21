<?php
$hash = '$2y$10$quYnuFsL9wYydj39cyj2R..jSmR/4ehCVjxo39WRpHLtDwFCA5/Uu';
$senha_input = 'admin123';

if (password_verify($senha_input, $hash)) {
    echo "Senha válida!";
} else {
    echo "Senha inválida!";
}

?>
