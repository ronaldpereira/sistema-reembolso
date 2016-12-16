function submitForm(formId){

  var formData = $(formId).serialize();
  sendEmail(formData);

}

function anexaComprovante(form){
  formData = new FormData($('#formSolicitacao')[0]);
  $.ajax({
    url: './controllers/salvaComprovante.php',
    type: 'POST',
    // Form data
    data: formData,
    //Options to tell jQuery not to process data or worry about content-type.
    cache: false,
    contentType: false,
    processData: false,
    success: function(response){
      var registros = jQuery.parseJSON(response);
      if(registros["sucesso"]){
        alertify.alert(registros["sucesso"], function(){
          openReceipt(form);
        });
      }else{
        alertify.error(registros["error"], function(){
          openReceipt(form);
        });
      }
    }
 });
}

function saveReceipt(formData){
  $.ajax({
    url: './relatorios/salvaComprovante.php',
    type: 'POST',
    data: formData,
    success: function(response){
      alertify.alert("Comprovante salvo com sucesso. Clique em ok e aguarde a próxima confirmação.",
        function(){
          if($("input[name='tipoSolicitacao']:checked").val() == "reembolso"){
            anexaComprovante(formData);
          }else{
            openReceipt(formData);
          }
        });
    }
 });
}

function sendEmail(formData){
  $.ajax({
    url: './controllers/sendEmail.php',
    type: 'POST',
    data: formData,
    success: function(response){
	  JSON.stringify(response);
      var registros = jQuery.parseJSON(response);
      if(registros["sucesso"]){
        alertify.alert("Email para o financeiro enviado. Clique em ok e aguarde a próxima confirmação.", function(){
          saveReceipt(formData);
        });
      }else{
        alertify.error(registros["error"], function(){
          saveReceipt(formData);
        });
      }

    }
 });
}

function openReceipt(formData){
  $.ajax({
    url: './relatorios/printComprovante.php',
    type: 'POST',
    data: formData,
    cache: false,
    success: function(response){
      window.open('relatorios/tmp.pdf');
      location.reload();
    }
 });
}

function getDataAtual(){
  var today = new Date();
  var dd = today.getDate();
  var mm = today.getMonth()+1; //January is 0!
  var yyyy = today.getFullYear();

  if(dd<10) {
      dd='0'+dd
  }

  if(mm<10) {
      mm='0'+mm
  }

  return(today = dd+'/'+mm+'/'+yyyy);
}
