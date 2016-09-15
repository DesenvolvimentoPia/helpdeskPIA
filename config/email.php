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
//echo $header->fromaddress;

// Nome do remetente
$nome1 = $header->from->personal;

//echo "Nome: ".$nome[0]["personal"];

// Enderecos de copia
//echo $header->cc;

// Endereco de resposta
echo $header->reply_toaddress;

$nome = $header->reply_toaddress;

// Tamanho da mensagem
//echo $header->Size;

// Assunto da mensagem
if(strstr($header->Subject, "=?utf")) $assunto = "Sem Assunto";
else $assunto = $header->Subject;

//echo $assunto;

$conteudo =  imap_fetchbody($mailbox,1,1.2); 

echo "<p>Nome: ".$nome."</p>";
echo "<p>Assunto: ".$assunto."</p>";
echo "<p>Conteudo: ".$conteudo."</p>";



if(!strstr($assunto, 'EVERYONE') && !empty($assunto) && !empty($nome)) {

$sql = "INSERT INTO glpi_tickets(id, entities_id, name, date, closedate, solvedate, date_mod, users_id_lastupdater, status, users_id_recipient, requesttypes_id, content, urgency, impact, priority, itilcategories_id, type, solutiontypes_id, solution, global_validation, slas_id, slalevels_id, due_date, begin_waiting_date, sla_waiting_duration, waiting_duration, close_delay_stat, solve_delay_stat, takeintoaccount_delay_stat, actiontime, is_deleted, locations_id, validation_percent) VALUES ('','0','".$assunto."','20".date('y-m-d H:i:s')."','','','','','1','','2','".$conteudo."','3','3','3','0','1','0','','1','0','0','0','0','0','0','0','0','0','0','','1','0')";

$res = mysql_query($sql, $con);

echo $sql;

}

imap_delete($mailbox, 1);
imap_expunge($mailbox);
