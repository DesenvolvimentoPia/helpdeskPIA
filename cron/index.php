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


$sql = "SELECT 
glpi_tickets.id,
glpi_tickets.name,
glpi_tickets.content,
glpi_tickets.urgency,
glpi_tickets.due_date,
glpi_users.firstname AS usuario,
glpi_users.realname AS sobrenome,
glpi_users.name AS mail,
glpi_tickets.is_deleted

FROM glpi_tickets

INNER JOIN glpi_tickets_users ON
glpi_tickets_users.tickets_id = glpi_tickets.id

INNER JOIN glpi_users ON
glpi_users.id = glpi_tickets_users.users_id

WHERE
glpi_tickets.solvedate IS NULL
AND glpi_tickets.is_deleted = 0
AND glpi_tickets_users.type = 2

ORDER BY glpi_tickets.id DESC";
$res = mysql_query($sql, $con);

$num = mysql_num_rows($res);

for($x = 0; $x < $num; $x++) {

	$row = mysql_fetch_array($res);

	echo "<div>{$row['id']} - {$row['name']} - {$row['usuario']} {$row['sobrenome']} - {$row['mail']}@pia.com.br</div>";

	if($row['due_date'] == null) $row['due_date'] = "Sem Prazo";
	else {
		$data = explode(" ", $row['due_date']);
		$data2 = explode("-", $data[0]);
		$row['due_date'] = $data2[2]."/".$data2[1]."/".$data2[0];
	}
	
	$m['subject'] = 'Tarefas GLPI - Chamados Abertos';	
	$m['to'] = $row["mail"].'@pia.com.br';
	
	$m['message'] = '
	O seguinte CHAMADO está sob sua responsabilidade e encontra-se aberto:<br /><br />

	ID do Chamado: '.$row["id"].'<br />
	Nome do Chamado: '.$row["name"].'<br />
	Descrição do Chamado: '.$row["content"].'<br />
	Urgência: '.$row["urgency"].'<br />
	Prazo: '.$row["due_date"].'<br /><br />

    <a href="http://suporte.pia.com.br/ti/front/ticket.form.php?id='.$row["id"].'">Responder Chamado</a>
	
	';
	
	$m['headers'] = implode("\r\n",array(
							'From: Chamados HELPDESK<glpi@pia.com.br>',
							'Reply-To: Chamados HELPDESK<glpi@pia.com.br>',
							'Mime-Version: 1.0',
							'Content-Type: text/html; charset=utf-8',
							'Priority: normal',
							'X-Mailer: PHP/'.phpversion(),
							'X-Priority: 3'));;
	
	//if(mail($m['to'],$m['subject'],$m['message'],$m['headers'])) echo "<p>Seu email foi enviado com sucesso para {$row['mail']}@pia.com.br.</p>";
	//else echo "<p>Falha ao enviar email para {$row['mail']}@pia.com.br.</p>";



}

echo "<div style='height: 70px'></div>";


$sql = "SELECT 
glpi_tickets.id,
glpi_tickets.name,
glpi_tickets.content,
glpi_tickets.urgency,
glpi_tickets.due_date,
glpi_users.firstname AS usuario,
glpi_users.realname AS sobrenome,
glpi_users.name AS mail,
SUM(glpi_tickets_users.type) AS soma

FROM glpi_tickets

INNER JOIN glpi_tickets_users ON
glpi_tickets_users.tickets_id = glpi_tickets.id
AND glpi_tickets_users.type != 2

INNER JOIN glpi_users ON
glpi_users.id = glpi_tickets_users.users_id

WHERE
glpi_tickets.solvedate IS NULL
AND glpi_tickets.is_deleted = 0

GROUP BY glpi_tickets.id, glpi_tickets.name, glpi_tickets.content, glpi_tickets.urgency, glpi_tickets.due_date, glpi_users.firstname, glpi_users.realname, glpi_users.name 

ORDER BY glpi_tickets.id DESC";
$res = mysql_query($sql, $con);

$num = mysql_num_rows($res);

for($x = 0; $x < $num; $x++) {

	$row = mysql_fetch_array($res);

	echo "<div>{$row['id']} - {$row['name']} - {$row['usuario']} {$row['sobrenome']} - {$row['mail']}@pia.com.br</div>";

	if($row['due_date'] == null) $row['due_date'] = "Sem Prazo";
	else {
		$data = explode(" ", $row['due_date']);
		$data2 = explode("-", $data[0]);
		$row['due_date'] = $data2[2]."/".$data2[1]."/".$data2[0];
	}
	
	$m['subject'] = 'Tarefas GLPI - Chamados Abertos';	
	$m['to'] = $row["mail"].'@pia.com.br';
	
	$m['message'] = '
	O seguinte CHAMADO está sob sua responsabilidade e encontra-se aberto:<br /><br />

	ID do Chamado: '.$row["id"].'<br />
	Nome do Chamado: '.$row["name"].'<br />
	Descrição do Chamado: '.$row["content"].'<br />
	Urgência: '.$row["urgency"].'<br />
	Prazo: '.$row["due_date"].'<br /><br />

    <a href="http://suporte.pia.com.br/ti/front/ticket.form.php?id='.$row["id"].'">Responder Chamado</a>
	
	';
	
	$m['headers'] = implode("\r\n",array(
							'From: Chamados HELPDESK<glpi@pia.com.br>',
							'Reply-To: Chamados HELPDESK<glpi@pia.com.br>',
							'Mime-Version: 1.0',
							'Content-Type: text/html; charset=utf-8',
							'Priority: normal',
							'X-Mailer: PHP/'.phpversion(),
							'X-Priority: 3'));;
	
	/*if(mail($m['to'],$m['subject'],$m['message'],$m['headers'])) echo "<p>Seu email foi enviado com sucesso para {$row['mail']}@pia.com.br.</p>";
	else echo "<p>Falha ao enviar email para {$row['mail']}@pia.com.br.</p>";*/



}



$quinzeDias1 = strtotime("-15 days");
$quinzeDias = date("y-m-d H:i:s", $quinzeDias1);


echo "<div>Quinze dias: ".$quinzeDias;


$sql = "SELECT 
glpi_tickets.id,
glpi_tickets.name,
glpi_tickets.content,
glpi_tickets.urgency,
glpi_tickets.due_date,
glpi_tickets.is_deleted

FROM glpi_tickets

WHERE
glpi_tickets.status = 5
AND glpi_tickets.solvedate IS NOT NULL
AND glpi_tickets.solvedate < '".$quinzeDias."'
AND glpi_tickets.is_deleted = 0

ORDER BY glpi_tickets.id DESC";

echo "<div>SQL: ".$sql;

$res = mysql_query($sql, $con);

$num = mysql_num_rows($res);

for($x = 0; $x < $num; $x++) {
$row = mysql_fetch_array($res);
	echo $row['id'].": ".$row['name']."<br>";
	$sql2 = "UPDATE glpi_tickets SET closedate = '".date("y-m-d H:i:s")."', status = 6 WHERE id = '".$row['id']."'";
	$res2 = mysql_query($sql2, $con);
	echo "<p>SQL: ".$sql2;
}