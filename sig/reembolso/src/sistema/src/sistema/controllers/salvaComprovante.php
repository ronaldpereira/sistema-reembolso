<?php

$output = array();

$cpf = $_POST["cpf"];

$hour = date('Hi');
$date = str_replace("/","",$_POST["dataSolicitacao"]);
$target_dir = "../../../relatoriosDeSolicitacoes/".$cpf."/".$date.$hour."/";

$target_file = $target_dir . basename($_FILES["comprovante"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

// Check if file already exists
if (file_exists($target_file)) {
    //echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["comprovante"]["size"] > 5000000) {
    //echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "pdf") {
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    $output["error"] = "Problema na submissão do comprovante, verifique se está em formato PDF e possui menos do que 5MB. Tente novamente.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["comprovante"]["tmp_name"], $target_file)) {
        //echo "The file ". basename( $_FILES["comprovante"]["name"]). " has been uploaded.";
        rename($target_file, $target_dir."comprovante.pdf");
        $output["sucesso"] = "Comprovante anexado com sucesso. Obrigado por utilizar o Sistema de Reembolso.";
    } else {
        $output["error"] = "Problema na submissão do comprovante, verifique se está em formato PDF e possui menos do que 5MB. Tente novamente.";
    }
}

echo json_encode($output);
?>
