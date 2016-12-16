<?php
/**
 * mail.php
 *
 * Script para envio de emails para projetos
 *
 * Utilizando a classe Email.php, no chamado de sua função send() é realizada uma
 * requisição POST para este script, que realiza o envio do email de fato. O POST
 * pode incluir os seguintes parâmetros:
 *   to:      [string] email do destinatário
 *   subject: [string] assunto do email
 *   message: [string] mensagem do email
 *   headers: [array]  propriedades de cabeçalho (opcional)
 *   from:    [string] nome do remetente
 *   
 *
 * @author     Gabriel Carvalho - gabrielcarvalho306@gmail.com
 * @copyright  2015 Chorei Largado Entertaiment (everything in its own time)
 * @see        Email.php class, http://wiki.locaweb.com.br/pt-br/PHP_Mail_-_Como_enviar_e-mails_utilizando_a_fun%C3%A7%C3%A3o_Mail_nativa_do_PHP
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    1.0
 * @todo       Token para auth, tratar headers adicionais, tratar múltiplos emails, muito mais..
 */

$to         = (!empty($_POST["to"]) ? $_POST["to"] : false);
$subject    = (!empty($_POST["subject"]) ? $_POST["subject"] : false);
$message    = (!empty($_POST["message"]) ? $_POST["message"] : false);
$from       = (!empty($_POST["from"]) ? $_POST["from"] : false);
$headers    = (!empty($_POST["headers"]) ? $_POST["headers"] : false);
$localEmail = "contato@ijunior.com.br";
$output     = array();


if(!$headers) {
  $headers   = array();
  $headers[] = "MIME-Version: 1.1";
  $headers[] = "Content-type: text/html; charset=utf-8";
  $headers[] = "From: $from <contato@ijunior.com.br>";
}
   
if(mail($to, $subject, $message, implode("\r\n", $headers),"-r".$localEmail)) {
  $output["success"] = true;
} else {
  $output["success"] = false;
  $output["error"]   = "Something bad happened";
}

?>