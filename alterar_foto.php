<?php
session_start(); // começa a sessao pra acessar dados do usuario
include 'config.php'; // puxa as configs do banco de dados ou outras coisas

// verifica se o usuario ta logado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); // manda o usuario pro login se nao tiver logado
    exit(); // para tudo aqui pra evitar problemas
}

// checa se o arquivo foi enviado
if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
    $usuario_id = $_SESSION['id']; // pega o id do usuario logado
    $pasta_uploads = 'uploads/'; // define a pasta onde vai guardar as fotos

    // valida se o tipo do arquivo é permitido
    $tipos_permitidos = array('image/jpeg', 'image/png'); // define os tipos que pode aceitar
    if (!in_array($_FILES['foto_perfil']['type'], $tipos_permitidos)) {
        echo "tipo de arquivo nao permitido. aceita so jpg e png."; // avisa que nao funcionou por causa do formato
        exit();
    }

    $nome_arquivo = $_FILES['foto_perfil']['name']; // pega o nome do arquivo
    $caminho_temporario = $_FILES['foto_perfil']['tmp_name']; // pega onde o arquivo ta temporariamente
    $novo_nome_arquivo = uniqid() . '_' . $nome_arquivo; // cria um nome unico pro arquivo
    $caminho_final = $pasta_uploads . $novo_nome_arquivo; // monta o caminho final do arquivo

    // conecta com o banco de dados
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("falha na conexao com o banco: " . mysqli_connect_error()); // para tudo se nao conectar
    }

    // busca a foto antiga do usuario
    $sql_select = "SELECT foto_perfil FROM usuarios WHERE id = ?";
    $stmt_select = mysqli_prepare($conn, $sql_select); // prepara a query
    mysqli_stmt_bind_param($stmt_select, "i", $usuario_id); // substitui o ? pelo id do usuario
    mysqli_stmt_execute($stmt_select); // executa a query
    $result_select = mysqli_stmt_get_result($stmt_select); // pega o resultado

    if (mysqli_num_rows($result_select) == 1) { // se achou o usuario
        $row = mysqli_fetch_assoc($result_select); // pega os dados da query
        $foto_antiga = $row['foto_perfil']; // pega o nome da foto antiga

        // apaga a foto antiga se ela existir
        if ($foto_antiga) {
            $caminho_foto_antiga = $pasta_uploads . $foto_antiga; // monta o caminho dela
            if (file_exists($caminho_foto_antiga)) { // checa se a foto existe
                unlink($caminho_foto_antiga); // apaga a foto
            }
        }
    }

    mysqli_stmt_close($stmt_select); // fecha o statement da foto antiga

    // move o novo arquivo pra pasta final
    if (move_uploaded_file($caminho_temporario, $caminho_final)) {
        // atualiza o banco com o nome do novo arquivo
        $sql_update = "UPDATE usuarios SET foto_perfil = ? WHERE id = ?";
        $stmt_update = mysqli_prepare($conn, $sql_update); // prepara a query
        mysqli_stmt_bind_param($stmt_update, "si", $novo_nome_arquivo, $usuario_id); // coloca os valores na query

        if (mysqli_stmt_execute($stmt_update)) { // tenta executar a query
            header("Location: perfil.php"); // redireciona pro perfil
        } else {
            echo "erro ao atualizar a foto: " . mysqli_error($conn); // erro na query
        }

        mysqli_stmt_close($stmt_update); // fecha o statement de update
    } else {
        // trata erros de upload
        $errorMessage = "erro ao fazer upload da foto. ";
        if (!is_dir($pasta_uploads)) {
            $errorMessage .= "a pasta de uploads nao existe. ";
        } else if (!is_writable($pasta_uploads)) {
            $errorMessage .= "a pasta de uploads nao tem permissao de escrita. ";
        }
        echo $errorMessage; // mostra mensagem de erro
    }

    mysqli_close($conn); // fecha a conexao com o banco
} else {
    echo "nenhum arquivo enviado ou erro no upload."; // mensagem caso nao tenha arquivo
}
?>
