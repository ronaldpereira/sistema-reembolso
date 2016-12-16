<?php
	require("../classes/Email.php");

	$nome = $_POST["nome"];
	//$email = $_POST["email"];
	$cpf = $_POST["cpf"];

	$assunto = "[INTERNO] Solicitação de recursos financeiros";
	$remetente = "Sistema de Reembolso";
	$mensagem = "Olá,<br/><br/>Uma nova solicitação de recursos financeiros foi feita:<br/>Nome do Solicitante: $nome,<br/>CPF do Solicitante: $cpf.<br/><br/>Verifique o arquivo gerado e o comprovante(em caso de reembolso) no diretório do sistema de solicitações, no FPT da empresa. Este encontra-se no diretório \"relatoriosDeSolicitacoes/CPFSolicitante/datahora/\"";
	$to = "financeiro@ijunior.com.br";
	$email = new Email($to, $assunto, $mensagem, $remetente, null);


	$output = array();


	if($email->send()) {
		$output["sucesso"] = "Financeiro contactado com sucesso.";
	}else {
		$output["error"] = "Erro no envio do email para o financeiro.";
	}

	echo json_encode($output);
?>
