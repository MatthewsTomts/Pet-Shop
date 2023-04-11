<?php
session_start();
include_once("../../rotas.php"); // Inclui o arquivo de rotas
include_once($connRoute); // Inclui o arquivo de conexão

// Pega os valores digitados no login, pelo usuário
$email = htmlspecialchars($_POST['email']);
$senha = htmlspecialchars($_POST['senha']);
// Codifica a senha, para que possa ser comparada com a senha do banco
$hash = hash("sha512", $senha);

try {
    // Faz a query no banco, utilizando a senha e o cpf, fornecidos pelo usuário
    $stmt = $conn->prepare("SELECT pk_Cliente FROM Clientes WHERE email = ? and senha = ?");
    $stmt->bind_param("ss", $email, $hash);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Verifica se a query deu algum retorno
    if ($row = $resultado->fetch_row()) {
        $_SESSION['loggedin'] = true;
        $_SESSION['id'] = $row[0];   // id do cliente
        header("Location: ". $homeRoute);
    } else {
        $_SESSION['msglogin'] = "<p>USUÁRIO OU SENHA INCORRETO(S).</p>";
        echo $email."<br>". $hash;
        // header("Location: " . $loginCliRoute);
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
