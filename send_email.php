<?php
// ============================================
// RockSolid Construction & Consulting LLC
// Contact Form - Email Handler
// ============================================

// TROQUE AQUI pelo seu email
$destinatario = "alexpsantini@gmail.com";
$assunto_prefix = "[RockSolid Website] Nova mensagem de contato";

// Segurança: aceita apenas POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Method not allowed"]);
    exit;
}

// Coleta e sanitiza os dados
$nome    = htmlspecialchars(strip_tags(trim($_POST["name"] ?? "")));
$email   = htmlspecialchars(strip_tags(trim($_POST["email"] ?? "")));
$telefone = htmlspecialchars(strip_tags(trim($_POST["phone"] ?? "")));
$mensagem = htmlspecialchars(strip_tags(trim($_POST["message"] ?? "")));

// Validação básica
if (empty($nome) || empty($email) || empty($mensagem)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Please fill in all required fields."]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Invalid email address."]);
    exit;
}

// Monta o email
$assunto = $assunto_prefix . " - " . $nome;

$corpo = "
==============================================
  NOVA MENSAGEM - ROCKSOLID WEBSITE
==============================================

Nome / Empresa: $nome
Email:          $email
Telefone:       $telefone

Mensagem:
$mensagem

==============================================
Enviado em: " . date("d/m/Y H:i:s") . "
";

// Headers
$headers  = "From: noreply@rocksolidcc.com\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Envia
$enviado = mail($destinatario, $assunto, $corpo, $headers);

if ($enviado) {
    echo json_encode(["success" => true, "message" => "Message sent successfully!"]);
} else {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Failed to send. Please try again."]);
}
?>
