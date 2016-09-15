<?php

//define('GLPI_ROOT', '../../../..');
include ("../../../../inc/includes.php");
include ("../../../../config/config.php");
include "../inc/functions.php";

global $DB;

Session::checkLoginUser();
Session::checkRight("profile", READ);

if(!empty($_POST['submit']))
{	
	$data_ini =  $_POST['date1'];	
	$data_fin = $_POST['date2'];
}

else {	
	$data_ini = date("Y-m-01");
	$data_fin = date("Y-m-d");	
	}  

if(!isset($_POST["sel_ent"])) {
	$id_ent = $_GET["ent"];	
}

else {
	$id_ent = $_POST["sel_ent"];
}

?>

<html> 
<head>
<title> GLPI - <?php echo __('Satisfaction survey');?> </title>
<!-- <base href= "<?php $_SERVER['SERVER_NAME'] ?>" > -->
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
  <meta http-equiv="content-language" content="en-us" />
  <meta charset="utf-8">
  
  <link rel="icon" href="../img/dash.ico" type="image/x-icon" />
  <link rel="shortcut icon" href="../img/dash.ico" type="image/x-icon" />
  <link href="../css/styles.css" rel="stylesheet" type="text/css" />
  <link href="../css/bootstrap.css" rel="stylesheet" type="text/css" />
  <link href="../css/bootstrap-responsive.css" rel="stylesheet" type="text/css" />  
  <link href="../css/font-awesome.css" type="text/css" rel="stylesheet" />  
  <script language="javascript" src="../js/jquery.min.js"></script>  
  <link href="../inc/select2/select2.css" rel="stylesheet" type="text/css">
  <script src="../inc/select2/select2.js" type="text/javascript" language="javascript"></script>

  <script src="../js/bootstrap-datepicker.js"></script>
   <link href="../css/datepicker.css" rel="stylesheet" type="text/css">
   <link href="../less/datepicker.less" rel="stylesheet" type="text/css">
   
   <script src="../js/media/js/jquery.dataTables.min.js"></script>
	<link href="../js/media/css/dataTables.bootstrap.css" type="text/css" rel="stylesheet" />  
	<script src="../js/media/js/dataTables.bootstrap.js"></script> 
	<link href="../js/extensions/TableTools/css/dataTables.tableTools.css" type="text/css" rel="stylesheet" />
	<script src="../js/extensions/TableTools/js/dataTables.tableTools.js"></script>
	
	<script src="../js/extensions/ColVis/css/dataTables.colVis.min.css"></script>
	<script src="../js/extensions/ColVis/js/dataTables.colVis.min.js"></script>	
	
<style type="text/css">	
	select { width: 60px; }
	table.dataTable { empty-cells: show; }
   a:link, a:visited, a:active { text-decoration: none;}
	a:hover {color: #000099;}
</style>

<?php echo '<link rel="stylesheet" type="text/css" href="../css/style-'.$_SESSION['style'].'">';  ?> 
   
</head>

<body style="background-color: #e5e5e5; margin-left:0%;">

<div id='content' >
<div id='container-fluid' style="margin: 0px 2% 0px 2%;"> 
<div id="charts" class="row-fluid chart"> 
<div id="pad-wrapper" >
<div id="head-lg" class="row-fluid">

<style type="text/css">

</style>

<a href="../index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:25px;"></i><span></span></a>

	<div id="titulo_graf"><?php echo __('Satisfaction survey'); ?> </div>
	
		<div id="datas-tec" class="span12 row-fluid" >
 
		<form id="form1" name="form1" class="form_rel" method="post" action="rel_satisfacao.php?con=1" onsubmit="datai();dataf();" style="margin-left: 37%;">  
		<table border="0" cellspacing="0" cellpadding="3" bgcolor="#efefef" >
		<tr>							
			<td style="width: 310px;">
			<?php
			$url = $_SERVER['REQUEST_URI']; 
			$arr_url = explode("?", $url);
			$url2 = $arr_url[0];
			    
			echo'
			<table>
				<tr>
					<td>
					   <div class="input-group date" id="dp1" data-date="'.$data_ini.'" data-date-format="yyyy-mm-dd">
					    	<input class="col-md-9 form-control" size="13" type="text" name="date1" value="'.$data_ini.'" >
					    	<span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
				    	</div>
					</td>
					<td>&nbsp;</td>
					<td>
				   	<div class="input-group date" id="dp2" data-date="'.$data_fin.'" data-date-format="yyyy-mm-dd">
					    	<input class="col-md-9 form-control" size="13" type="text" name="date2" value="'.$data_fin.'" >
					    	<span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
				    	</div>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table> ';
				?>	
			
			<script language="Javascript">		
				$('#dp1').datepicker('update');
				$('#dp2').datepicker('update');		
			</script>
			</td>
		</tr>	
		<tr><td height="20px"></td></tr>
		<tr>
			<td colspan="2" align="center">		 
				<button class="btn btn-primary btn-sm" type="submit" name="submit" value="Atualizar" ><i class="fa fa-search"></i>&nbsp; <?php echo __('Consult', 'dashboard'); ?></button>
				<button class="btn btn-primary btn-sm" type="button" name="Limpar" value="Limpar" onclick="location.href='<?php echo $url2 ?>'" > <i class="fa fa-trash-o"></i>&nbsp; <?php echo __('Clean', 'dashboard'); ?> </button></td>
			</td>
		</tr>
			
			</table>
		<?php Html::closeForm(); ?>

		</div>
	</div>	

<?php 

//entidades
$con = $_REQUEST['con'];

if($con == "1") {

if(!isset($_POST['date1']))
{	
	$data_ini2 = $_GET['date1'];	
	$data_fin2 = $_GET['date2'];
}

else {	
	$data_ini2 = $_POST['date1'];	
	$data_fin2 = $_POST['date2'];	
}  

//entity
if(!isset($_REQUEST["sel_ent"]) || $_REQUEST["sel_ent"] == 0 || $_REQUEST["sel_ent"] == "" ) 
{ 
	$id_ent = 0; 
   $entidade = "";
}

else { 
	$id_ent = $_REQUEST["sel_ent"]; 
	$entidade = "AND glpi_tickets.entities_id = ".$id_ent." ";
}

//$arr_param = array($id_ent, $id_sta, $id_req, $id_pri, $id_cat, $id_tip);

//dates
if($data_ini2 == $data_fin2) {
	$datas2 = "LIKE '%".$data_ini2."%'";	
}	

else {
	$datas2 = "AND glpi_tickets.date BETWEEN '".$data_ini2." 00:00:00' AND '".$data_fin2." 23:59:59'";	
}	

// Chamados

$sql_cham = 
"SELECT glpi_tickets.id, glpi_tickets.name as titulo, glpi_tickets.closedate as fechamento, 
glpi_ticketsatisfactions.satisfaction as nota, glpi_ticketsatisfactions.comment as comentarios, 
glpi_tickets.date as abertura
FROM glpi_tickets, glpi_ticketsatisfactions
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.status = 6
".$datas2."
AND glpi_ticketsatisfactions.tickets_id = glpi_tickets.id
AND glpi_ticketsatisfactions.satisfaction <> 'NULL'
ORDER BY glpi_tickets.id ASC ";

$result_cham = $DB->query($sql_cham);

//var_dump($sql_cham);
//localizando comentários nas aprovações de soluções sem pesquisas de satisfação respondidas
/*
$sqlbuscacomentarios = "

SELECT glpi_tickets.id, glpi_tickets.name as titulo, glpi_tickets.closedate as fechamento, glpi_tickets.date as abertura
FROM glpi_tickets
left join glpi_ticketfollowups on glpi_ticketfollowups.tickets_id=glpi_tickets.id
left join glpi_tickets_users on glpi_tickets_users.tickets_id=glpi_tickets.id
WHERE
glpi_tickets.is_deleted = 0 and glpi_tickets.status = 6
and glpi_tickets_users.type = 1 and glpi_tickets_users.users_id = glpi_ticketfollowups.users_id
AND glpi_ticketfollowups.content <> 'NULL'
and glpi_ticketfollowups.date >= glpi_tickets.solvedate
".$datas2."
AND glpi_tickets.id NOT IN (SELECT glpi_tickets.id
FROM glpi_tickets
left join glpi_ticketsatisfactions on glpi_ticketsatisfactions.tickets_id=glpi_tickets.id
left join glpi_groups_tickets on glpi_groups_tickets.tickets_id=glpi_tickets.id
left join glpi_groups on glpi_groups.id=glpi_groups_tickets.groups_id
WHERE (glpi_tickets.is_deleted = 0 and glpi_tickets.status = 6
".$datas2."
AND glpi_ticketsatisfactions.satisfaction <> 'NULL')
ORDER BY glpi_tickets.id ASC)
";

$result_buscacomentarios = $DB->query($sqlbuscacomentarios);

$consulta = $DB->numrows($result_cham) + $DB->numrows($result_buscacomentarios);
*/

$consulta = $DB->numrows($result_cham);

if($consulta > 0) {

// nome da entidade
$sql_nm = "
SELECT name
FROM `glpi_entities`
WHERE id = ".$id_ent."";

$result_nm = $DB->query($sql_nm);
$ent_name = $DB->fetch_assoc($result_nm);


//listar chamados
echo "
<div class='well info_box row-fluid col-md-12 report-tic' style='margin-left: -1px;'>

<table class='row-fluid'  style='font-size: 18px; font-weight:bold;  margin-bottom:25px;  margin-top:20px; ' cellpadding = 1px>
	<td  style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> ".__('Entity', 'dashboard').": </span>".$ent_name['name']." </td>
	<td  style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> ".__('Tickets', 'dashboard').": </span>".$consulta." </td>
	<td colspan='3' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'>
	".__('Period', 'dashboard') .": </span> " . conv_data($data_ini2) ." a ". conv_data($data_fin2)." 
	</td>
	<td>&nbsp;</td>
</table>

<table id='ticket' class='display'  style='font-size: 11px; font-weight:bold;' cellpadding = 2px>
	<thead>
		<tr>
			<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'>".__('Ticket','dashboard')."</th>
			<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'> ".__('Title')." </th>
			<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'> ".__('Requester')." </th>
			<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'> ".__('Technician')." </th>			
			<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'> ".__('Opened','dashboard')."</th>
			<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'> ".__('Closed')." </th>
			<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'>Nota</th>
			<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'>Comentário da pesquisa</th>
			
		</tr>
	</thead>
<tbody>";



while($row = $DB->fetch_assoc($result_cham)){
	
	//requerente	
	$sql_user = "SELECT glpi_tickets.id AS id, glpi_tickets.name AS descr, glpi_users.firstname AS name, glpi_users.realname AS sname
	FROM `glpi_tickets_users` , glpi_tickets, glpi_users
	WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
	AND glpi_tickets.id = ". $row['id'] ."
	AND glpi_tickets_users.`users_id` = glpi_users.id
	AND glpi_tickets_users.type = 1
	";
	$result_user = $DB->query($sql_user);
			
	$row_user = $DB->fetch_assoc($result_user);
				
	//tecnico	
	$sql_tec = "SELECT glpi_tickets.id AS id, glpi_users.firstname AS name, glpi_users.realname AS sname
	FROM `glpi_tickets_users` , glpi_tickets, glpi_users
	WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
	AND glpi_tickets.id = ". $row['id'] ."
	AND glpi_tickets_users.`users_id` = glpi_users.id
	AND glpi_tickets_users.type = 2 ";
	
	$result_tec = $DB->query($sql_tec);	
	
	$row_tec = $DB->fetch_assoc($result_tec);
/*		
	//comentários nas aprovações de fechamentos dos chamados encontrados
	$sql_aprov = "
	SELECT glpi_ticketfollowups.content as comentarionaaprovacaodasolucao
	FROM glpi_tickets
	WHERE glpi_tickets_users.type = 1
	AND glpi_tickets.is_delted = 0 
	AND glpi_tickets.id = ". $row['id'] ."
	AND glpi_ticketfollowups.date >= glpi_tickets.solvedate";
	
	$result_aprov = $DB->query($sql_aprov);	
	
	$row_aprov = $DB->fetch_assoc($result_aprov);
*/
		
echo "	
	<tr>
		<td style='vertical-align:middle; text-align:center;'><a href=".$CFG_GLPI['url_base']."/front/ticket.form.php?id=". $row['id'] ." target=_blank >" . $row['id'] . "</a></td>
		<td style='vertical-align:middle;'> ". substr($row_user['descr'],0,55) ." </td>
		<td style='vertical-align:middle;'> ". $row_user['name'] ." ".$row_user['sname'] ." </td>
		<td style='vertical-align:middle;'> ". $row_tec['name'] ." ".$row_tec['sname'] ." </td>		
		<td style='vertical-align:middle;'> ". conv_data_hora($row['abertura']) ." </td>
		<td style='vertical-align:middle;'> ". conv_data_hora($row['fechamento']) ." </td>
		<td style='vertical-align:middle; text-align:center;'>
			<span class='label' style=\"background:url('../img/stars/star". $row['nota']."_22.png') no-repeat;  
			color:#000 !important; padding-left: 8px !important; padding-top: 4px; font-size:11px; \">".$row['nota']. "</span> 
		</td>
		<td style='vertical-align:middle; text-align:left; width:30%;'> ". $row['comentarios']." </td>
			
	</tr>";

}

/*
while($row = $DB->fetch_assoc($result_buscacomentarios)){
	
	//requerente	
	$sql_user = "SELECT glpi_tickets.id AS id, glpi_tickets.name AS descr, glpi_users.firstname AS name, glpi_users.realname AS sname
	FROM `glpi_tickets_users` , glpi_tickets, glpi_users
	WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
	AND glpi_tickets.id = ". $row['id'] ."
	AND glpi_tickets_users.`users_id` = glpi_users.id
	AND glpi_tickets_users.type = 1
	";
	$result_user = $DB->query($sql_user);
			
	$row_user = $DB->fetch_assoc($result_user);
				
	//tecnico	
	$sql_tec = "SELECT glpi_tickets.id AS id, glpi_users.firstname AS name, glpi_users.realname AS sname
	FROM `glpi_tickets_users` , glpi_tickets, glpi_users
	WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
	AND glpi_tickets.id = ". $row['id'] ."
	AND glpi_tickets_users.`users_id` = glpi_users.id
	AND glpi_tickets_users.type = 2 ";
	
	$result_tec = $DB->query($sql_tec);	
	
	$row_tec = $DB->fetch_assoc($result_tec);
		
	//comentários nas aprovações de fechamentos dos chamados encontrados
	$sql_aprov = "
	SELECT glpi_ticketfollowups.content as comentarionaaprovacaodasolucao
	FROM glpi_tickets
	WHERE glpi_tickets_users.type = 1
	AND glpi_tickets.is_delted = 0 
	AND glpi_tickets.id = ". $row['id'] ."
	AND glpi_ticketfollowups.date >= glpi_tickets.solvedate
	";
	
	//;
	//var_dump($sql_aprov);
	//die();
	$result_aprov = $DB->query($sql_aprov);	
	
	$row_aprov = $DB->fetch_assoc($result_aprov);

		
echo "	
	<tr>
		<td style='vertical-align:middle; text-align:center;'><a href=".$CFG_GLPI['url_base']."/front/ticket.form.php?id=". $row['id'] ." target=_blank >" . $row['id'] . "</a></td>
		<td style='vertical-align:middle;'> ". substr($row_user['descr'],0,55) ." </td>
		<td style='vertical-align:middle;'> ". $row_user['name'] ." ".$row_user['sname'] ." </td>
		<td style='vertical-align:middle;'> ". $row_tec['name'] ." </td>
		<td style='vertical-align:middle;'> ". $row['nomedogrupo'] ." </td>
		<td style='vertical-align:middle;'> ". conv_data_hora($row['abertura']) ." </td>
		<td style='vertical-align:middle;'> ". conv_data_hora($row['fechamento']) ." </td>
		<td style='vertical-align:middle; text-align:center;'> ". $row['nota']." </td>
		<td style='vertical-align:middle; text-align:center;'> ". $row['comentarios']." </td>
		<td style='vertical-align:middle; text-align:center;'> ". $row_aprov['comentarionaaprovacaodasolucao']." </td>		
	</tr>";

}
*/
echo "</tbody>
		</table>
		</div>";
		 
echo '</div><br>';
}

else {
	
	echo "
	<div id='nada_rel' class='well info_box row-fluid col-md-12'>
	<table class='table' style='font-size: 18px; font-weight:bold;' cellpadding = 1px>
	<tr><td style='vertical-align:middle; text-align:center;'> <span style='color: #000;'>" . __('No ticket found', 'dashboard') . "</td></tr>
	<tr></tr>
	</table></div>";	

}	
}
?>

<script type="text/javascript" charset="utf-8">

$('#ticket')
	.removeClass( 'display' )
	.addClass('table table-striped table-bordered table-hover');

$(document).ready(function() {
    oTable = $('#ticket').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "bFilter": false,
        "aaSorting": [[0,'desc']], 
        "iDisplayLength": 25,
    	  "aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]], 

        "sDom": 'T<"clear">lfrtip',   //"sDom": 'CT<"clear">lfrtip',
                
			"aoColumnDefs": [
         	{ "bVisible": false, "aTargets": [ <?php echo $targ; ?> ] }
            ],         
        
         "oTableTools": {
         "aButtons": [
             {
                 "sExtends": "copy",
                 "sButtonText": "<?php echo __('Copy'); ?>"
             },
             {
                 "sExtends": "print",
                 "sButtonText": "<?php echo __('Print','dashboard'); ?>",
                 "sMessage": "<div id='print' class='info_box row-fluid span12' style='margin-bottom:35px; margin-left: -1px;'><table id='print_tb' class='row-fluid'  style='width: 80%; margin-left: 10%; font-size: 18px; font-weight:bold;' cellpadding = '1px'><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo __('Entity'); ?> : </span><?php echo $ent_name['name']; ?> </td> <td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo  __('Tickets','dashboard'); ?> : </span><?php echo $consulta ; ?></td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'> <?php echo  __('Period','dashboard'); ?> : </span> <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2); ?> </td> </table></div>"
             },
             {
                 "sExtends":    "collection",
                 "sButtonText": "<?php echo _x('button', 'Export'); ?>",
                 "aButtons":    [ "csv", "xls",
                  {
                 "sExtends": "pdf",
                 "sPdfOrientation": "landscape",
                 "sPdfMessage": ""
                  } 
                  ]
             }
         	 ]
        },
                   "oLanguage": {
                     "sSearch": "<?php echo __('Search all columns:'); ?>"
                 		},
                   colVis: {
                   	"buttonText": "<?php echo __('Show/hide columns','dashboard'); ?>",
        				 	"restore": "<?php echo __('Restore'); ?>",
         				"showAll": "<?php echo __('Show all'); ?>",
         				"exclude": [0]     
     						},
                 "bSortCellsTop": true,
                 "sAlign": "right"
		  
    });    
} );		
</script> 	

</div>
</div>
</div>
</div>

</body> 
</html>

