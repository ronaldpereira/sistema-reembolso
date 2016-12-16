<?php
/**
 * Email.php
 *
 * Classe para manipulação de Emails e disparo pelo servidor da Ijúnior
 *
 * Após a criação do objeto, chamar o método send para enviar o email.
 *
 * Exemplo de uso:
 * 	<?php
 *	 	require("Email.php");
 *
 *		$email = new Email("cliente@gmail.com","assunto","mensagem","remetente");
 *
 *	    if($email->send()) {
 *		    echo "E-mail enviado com sucesso";
 *  	}else {
 *	        echo "Erro no envio, mensagem:";
 *	        echo $email->error;
 *      }
 *  ?>
 *
 * @author     Gabriel Carvalho - gabrielcarvalho306@gmail.com
 * @copyright  2015 Chorei Largado Entertaiment (everything in its own time)
 * @see        script mail.php, localizado em public_html/mail no servidor da Ijúnior
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    1.0
 * @todo       Token para auth, tratar headers adicionais, tratar múltiplos emails, muito mais..
 */

class Email {

	public $to;
	public $subject;
	public $message;
	public $headers;
	public $from;
	public $error;


	/**
	 * Construtor do objeto Email
	 * @param [string] $to      [email do destinatário]
	 * @param [string] $subject [assunto do email]
	 * @param [string] $message [conteúdo da mensagem]
	 * @param [string] $from    [rementente]
	 * @param [array]  $headers [headers personalizados, caso necessário. Cada propriedade deve ser uma posição no array]
	 */
	public function __construct($to, $subject, $message, $from, $headers) {
		$this->to = $to;
		$this->subject = $subject;
		$this->message = $message;
		$this->from = $from;

		if(is_array($headers))
			$this->headers = $headers;
	}

	/**
	 * Envia o email, realizando uma requisição POST para um script no servidor
	 * da Ijúnior, em public_html/mail/mail.php
	 * @return [bool] [description]
	 */
	public function send() {
		$ch = curl_init();

		$fields = "to={$this->to}&subject={$this->subject}&message={$this->message}&from={$this->from}";

		if($this->headers)
			$fields .= "&headers{$this->headers}";

		curl_setopt($ch, CURLOPT_URL, "http://www.ijunior.com.br/sig/reembolso/src/sistema/mail/mail.php");
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		$response = curl_exec ($ch);
		curl_close($ch);


		if($response) {
			return true;
		}else {
			return false;
		}

	}

}


?>
