<?php
	require('../classes/fpdf/fpdf.php');
	define('FPDF_FONTPATH', "../classes/fpdf/font");
	require_once ("../dao/ReembolsoDAO.php");

function tipoSolicitacao($tipo){
	if($tipo == "reembolso"){
		return "Reembolso";
	}else{
		return "Requisição de verba";
	}
}

function formaPagamento($tipo){
	if($tipo == "deposito"){
		return "Depósito";
	}else{
		return "Cheque";
	}
}

class PDF extends FPDF{
	// Page header
	function Header(){
		// Logo
		$this->Image('../../img/logo.png',11,13,100);
		// Times bold 15
		$this->SetFont('Times','B',16);
		// Move down
		//$this->Cell(190,10);
		// Title
		$this->Cell(190,30,utf8_decode('Comprovante de Solicitação'),1,0,'R');
		$this->Ln(7);
		$this->Cell(190,30, 'de Recursos Financeiros', 0, 0, "R");
		// Line break
		$this->Ln(25);
		
	}

	// Page footer
	function Footer(){
		// Position at 1.5 cm from bottom
		$this->SetY(-30);

		$this->SetFont('Times','I',10);
		$this->Cell(90,5,utf8_decode('__________________________________'),0,0,'C');
		$this->Cell(90,5,utf8_decode('__________________________________'),0,1,'C');
		$this->Cell(90,5,utf8_decode('Diretor Administrativo-Financeiro'),0,0,'C');
		$this->Cell(90,5,utf8_decode($_POST["cargo"]),0,1,'C');
		// Times italic 8
		$this->SetFont('Times','I',8);
		// Page number
		$today = getdate();
		$date = $today["year"];
		$date = $today["mday"] . "/" . $today["mon"] . "/" . $today["year"];
		$this->Cell(0,10,utf8_decode('Página '.$this->PageNo(). ' de {nb}'),0,1,'C');
		$this->Cell(0,-1, $date,0,0,'C');
	}
}
	
function gera(){
	
	$pdf = new PDF();
	$pdf->AddPage();
	$pdf->SetFont('Times','B',16);
	
	$pdf->SetFont('Times', 'B', 14);
	$pdf->Cell(190,10,utf8_decode('Dados do Solicitante:'), 0, 1);
	
	$pdf->SetFont('Times', '', 12);
	$pdf->Cell(25,7,utf8_decode('Data'), 1, 0);
	$pdf->Cell(65,7,utf8_decode('Nome'), 1, 0);
	$pdf->Cell(65,7,utf8_decode('E-mail'), 1, 0);
	$pdf->Cell(35,7,utf8_decode('CPF'), 1, 1);
	
	$pdf->SetFont('Times', '', 9);
	$pdf->Cell(25,7,utf8_decode($_POST["dataSolicitacao"]), 1, 0);
	$pdf->Cell(65,7,utf8_decode($_POST["nome"]), 1, 0);
	$pdf->Cell(65,7,utf8_decode($_POST['email']), 1, 0);
	$cpf = $_POST['cpf'];
	$pdf->Cell(35,7,utf8_decode($_POST['cpf']), 1, 1);
	
	$pdf->Ln(2);

	$pdf->SetFont('Times', '', 12);
	$pdf->Cell(40,7,utf8_decode('RG'), 1, 0);
	$pdf->Cell(70,7,utf8_decode('Cargo'), 1, 0);
	$pdf->Cell(40,7,utf8_decode('Telefone'), 1, 0);
	$pdf->Cell(40,7,utf8_decode('Celular'), 1, 1);
	
	
	$pdf->SetFont('Times', '', 9);
	$pdf->Cell(40,7,utf8_decode($_POST["rg"]), 1, 0);
	$pdf->Cell(70,7,utf8_decode($_POST["cargo"]), 1, 0);
	$pdf->Cell(40,7,utf8_decode($_POST["telefone"]), 1, 0);		
	$pdf->Cell(40,7,utf8_decode($_POST['celular']), 1, 1);
	
	$pdf->Ln(3);
	$pdf->SetFont('Times', 'B', 14);
	$pdf->Cell(190,7,utf8_decode('Dados da Solicitação:'), 0, 1);
	
	$pdf->SetFont('Times', 'B', 12);
	$tipo = tipoSolicitacao($_POST["tipoSolicitacao"]);
	$pdf->Cell(190,7,utf8_decode('Tipo: '. $tipo), 0, 1);
	
	$pdf->SetFont('Times', '', 12);
	$pdf->Cell(190,7,utf8_decode('Motivo'), 'LTR', 1);
	$pdf->SetFont('Times', '', 9);
	$pdf->MultiCell(190,7,utf8_decode($_POST['motivoSolicitacao']), 'LBR', 1);

	$pdf->Ln(3);
	$pdf->SetFont('Times', 'B', 14);
	$pdf->Cell(190,7,utf8_decode('Valores solicitados/reembolsados:'), 0, 1);

	$pdf->SetFont('Times', '', 12);
	$pdf->Cell(35,7,utf8_decode('Data do Gasto'), 1, 0);
	$pdf->Cell(35,7,utf8_decode('Valor Total'), 1, 0);
	$pdf->Cell(60,7,utf8_decode('Porcentagem Reembolsada'), 1, 0);
	$pdf->Cell(60,7,utf8_decode('Valor Reembolsado'), 1, 1);

	$pdf->SetFont('Times', '', 9);
	$pdf->Cell(35,7,utf8_decode($_POST["dataGasto"]), 1, 0);
	$pdf->Cell(35,7,utf8_decode($_POST["valorGasto"]), 1, 0);
	$pdf->Cell(60,7,utf8_decode(""), 1, 0);		
	$pdf->Cell(60,7,utf8_decode(""), 1, 1);

	$pdf->SetFont('Times', '', 12);
	$pdf->Cell(190,7,utf8_decode('Descrição'), 'LTR', 1);
	$pdf->SetFont('Times', '', 9);
	$pdf->MultiCell(190,7,utf8_decode($_POST['descricaoGasto']), 'LBR', 1);

	$pdf->Ln(3);

	$pdf->SetFont('Times', 'B', 14);
	$forma = formaPagamento($_POST["formaPagamento"]);
	$pdf->Cell(190,7,utf8_decode('Forma de Pagamento: '. $forma), 0, 1);
	
	if($forma == "Depósito"){
		
		$pdf->SetFont('Times', 'B', 12);
		$pdf->Cell(190,7,utf8_decode('Dados Bancários:'), 0, 1);

		$pdf->SetFont('Times', '', 12);
		$pdf->Cell(120,7,utf8_decode('Nome do Titular'), 1, 0);
		$pdf->Cell(70,7,utf8_decode('CPF'), 1, 1);

		$pdf->SetFont('Times', '', 9);
		$pdf->Cell(120,7,utf8_decode($_POST["nomeTitular"]), 1, 0);
		$pdf->Cell(70,7,utf8_decode($_POST["cpfTitular"]), 1, 1);
		
		$pdf->SetFont('Times', '', 12);
		$pdf->Cell(70,7,utf8_decode('Banco'), 1, 0);
		$pdf->Cell(50,7,utf8_decode('Agência'), 1, 0);
		$pdf->Cell(70,7,utf8_decode('Conta'), 1, 1);
		
		$pdf->SetFont('Times', '', 9);
		$pdf->Cell(70,7,utf8_decode($_POST["bancoTitular"]), 1, 0);
		$pdf->Cell(50,7,utf8_decode($_POST["agenciaTitular"]), 1, 0);
		$pdf->Cell(70,7,utf8_decode($_POST['contaTitular']), 1, 1);
		
	}

	$pdf->AliasNbPages();
	

	$path = "../../../relatoriosDeSolicitacoes/".$cpf;
	if (!is_dir($path)) {
    	mkdir($path, 0777, true);
	}

	$hour = date('Hi');
	$date = str_replace("/","",$_POST["dataSolicitacao"]);

	$path = "../../../relatoriosDeSolicitacoes/".$cpf."/".$date.$hour;
	if (!is_dir($path)) {
    	mkdir($path, 0777, true);
	}
	//salva o relatório para o diretor
	$pdf->Output("tmp.pdf");
	$pdf->Output($path."/diretor_".$date.$hour.".pdf");
}

	
gera();

$output = array();
$output["sucesso"] = "favoravel";
echo json_encode($output);
?>