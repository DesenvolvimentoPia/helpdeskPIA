<meta charset="utf-8">
<?php

date_default_timezone_set('America/Sao_Paulo');

$con = @mysql_connect("localhost","root","tpiasl2k10*");
if (!$con) {
	echo "Erro: Não foi possível conectar com o banco de dados!";
	exit;
}
$db = @mysql_select_db("glpi",$con);
if (!$db) {
	echo "Erro: Conexão feita, mas a base de dados não foi encontrada...";
	exit;
}

// if($con && $db) echo "Tudo certo!";


mysql_query("SET NAMES 'utf8'");
mysql_query('SET character_set_connection=utf8');
mysql_query('SET character_set_client=utf8');
mysql_query('SET character_set_results=utf8');

// Configure com seu login/senha
$login = 'suporte';
$senha = 'Sup0rt3!';

$str_conexao = '{mail.pia.com.br}';
if (!extension_loaded('imap')) {
    die('Modulo PHP/IMAP nao foi carregado');
}

// Abrindo conexao
$mailbox = imap_open($str_conexao, $login, $senha);
if (!$mailbox) {
    die('Erro ao conectar: '.imap_last_error());
}

$check = imap_check($mailbox);

// Ultima mensagem
echo $check->Date;

// Tipo de conexao
echo $check->Driver;

// Mailbox
echo $check->Mailbox;

// Numero de mensagens total
echo $check->Nmsgs;

// Numero de mensagens novas
echo $check->Recent;


echo "<p>";

$header = imap_header($mailbox, 1);

// Endereco do remetente
//$requerente = $header->fromaddress;

// Nome do remetente
$requerente = $header->from;


//echo "Nome: ".$nome[0]["personal"];

// Enderecos de copia
//echo $header->cc;

// Endereco de resposta
// echo $header->reply_toaddress;

$nome = $header->reply_toaddress;
$para = $header->toaddress;

// Tamanho da mensagem
//echo $header->Size;

// Assunto da mensagem
if(strstr($header->Subject, "=?utf")) $assunto = "Sem Assunto";
else $assunto = $header->Subject;

//echo $assunto;

$text = imap_fetchbody($mailbox, 1,2);   

$conteudo = $text;

$subject = quoted_printable_decode($header->Subject);

//echo $alt;
//echo $subject."\n".$bodyText;

echo "<p>Nome: ".$nome."</p>";

$limpar = str_replace("=
", "", strip_tags($conteudo));
$conteudo = $limpar;


$header2  = imap_headerinfo($mailbox, 1, 80, 150);
$subject    = $header2->fetchsubject;
$assunto = iconv_mime_decode($subject, 1, "UTF-8");



$sqlReq = "SELECT * FROM glpi_users WHERE name = '".$requerente[0]->mailbox."'";
$resReq = mysql_query($sqlReq, $con);
$numReq = mysql_num_rows($resReq);

if($numReq > 0) {
	$rowReq = mysql_fetch_array($resReq);
	$usu = $rowReq['id'];
}

else $usu = "";

if(!strstr($assunto, "Suporte - TI #")) {

	//echo "<div>AQUI!</div>";

	echo "<p>Conteudo: ".$conteudo."</p>";
	echo "<p>Requerente: ";
	//print_r($requerente);
	echo $requerente[0]->mailbox;
	echo "</p>";
	echo "<p>Para: ".$para."</p>";
	echo "<p>Subject: ".$assunto."</p>";
	echo "<p>Nome: ".$nome."</p>";


if(!strstr($assunto, 'EVERYONE') && !empty($assunto) && !empty($nome) && strstr($para, "suporte@pia.com.br")) {

$sql = "INSERT INTO glpi_tickets(id, entities_id, name, date, closedate, solvedate, date_mod, users_id_lastupdater, status, users_id_recipient, requesttypes_id, content, urgency, impact, priority, itilcategories_id, type, solutiontypes_id, solution, global_validation, slas_id, slalevels_id, due_date, begin_waiting_date, sla_waiting_duration, waiting_duration, close_delay_stat, solve_delay_stat, takeintoaccount_delay_stat, actiontime, is_deleted, locations_id, validation_percent) VALUES ('','0','".$assunto."','20".date('y-m-d H:i:s')."',NULL,NULL,'20".date('y-m-d H:i:s')."','".$usu."','1','".$usu."','2','".$conteudo."','3','3','3','0','1','0','','1','0','0',NULL,'0','0','0','0','0','0','0','','1','0')";

$res = mysql_query($sql, $con);
echo $sql;

$sqlId = "SELECT * FROM glpi_tickets ORDER BY id DESC";
$resId = mysql_query($sqlId, $con);
$rowId = mysql_fetch_array($resId);



	$sqlIns = "INSERT INTO glpi_tickets_users VALUES ('', '".$rowId['id']."', '".$usu."', '1', '1', '".$requerente[0]->mailbox."@pia.com.br')";
echo $sqlIns;
    $resIns = mysql_query($sqlIns, $con);

	
	$email = $requerente[0]->mailbox."@pia.com.br";
	
	$m['subject'] = 'Seu E-mail NÃO foi Recebido!';	
	$m['to'] = $email;
	
	$m['message'] = '
	E-mail de ALERTA enviado Automaticamente:<br /><br />

	Para chamados de SUPORTE, favor acessar <a href="http://suporte.PIA.com.br">suporte.PIA.com.br</a> e abrir seu chamado na ferramenta.<br /><br />

	Resumo do E-mail enviado:<br /><br />

	'.$conteudo.'
	
	';
	
	$m['headers'] = implode("\r\n",array(
							'From: Suporte - Helpdesk<suporte@pia.com.br>',
							'Reply-To: Suporte - Helpdesk<suporte@pia.com.br>',
							'Mime-Version: 1.0',
							'Content-Type: text/html; charset=utf-8',
							'Priority: normal',
							'X-Mailer: PHP/'.phpversion(),
							'X-Priority: 3'));
	
	//if(mail($m['to'],$m['subject'],$m['message'],$m['headers'])) echo 'Seu contato foi enviado com sucesso.';	

	echo "<p>".$m['to']."</p>";
	echo "<p>".$m['subject']."</p>";
	echo "<p>".$m['message']."</p>";
	echo "<p>".$m['headers']."</p>";


}

}

else {
	$chamado = explode("GLPI #", $assunto);
	$idChamado = explode("]", $chamado[1]);
	$idFinal = $idChamado[0];

	$filtro = str_replace("Re: ", "", $assunto);

	$explodir = explode("-----Mensagem Original-----", $conteudo);
	$conteudo = str_replace($filtro, "", $explodir[0]);


	$sqlFol = "INSERT INTO glpi_ticketfollowups VALUES ('','$idFinal','".date('y-m-d H:i:s')."','".$usu."','".$conteudo."','0','1')";
	$resFol = mysql_query($sqlFol, $con);

	echo $sqlFol;


}

imap_delete($mailbox, 1);
imap_expunge($mailbox);
