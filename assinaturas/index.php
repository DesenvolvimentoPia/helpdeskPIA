<?php

session_start();

ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

set_time_limit(200);

if(isset($_SESSION['login'])) { 

if(isset($_POST['nome'])) {

$image = imagecreatefromjpeg("../cron/foto.jpg");
$white = imageColorAllocate($image, 255, 255, 255);
$black = imageColorAllocate($image, 39, 36, 103);
$font = '../cron/Local Brewery Four.ttf';
imagettftext($image, 40, 0, 90, 120, $black, $font, $_POST['nome']);

if(isset($_POST['filial']) && !empty($_POST['filial']) && $_POST['filial'] != "" && $_POST['filial'] != " ") imagettftext($image, 25, 0, 90, 160, $black, $font, $_POST['setor']." / ".$_POST['filial']);
else imagettftext($image, 25, 0, 90, 160, $black, $font, $_POST['setor']);

if($_POST['ramal'] != "" && $_POST['ramal'] != " " && ! strstr($_POST['ramal'], ")")) $_POST['ramal'] = "ramal ".$_POST['ramal'];


if(isset($_POST['ramal']) && !empty($_POST['ramal']) && $_POST['ramal'] != "" && $_POST['ramal'] != " ") $escrito = $_POST['fone']." / ".$_POST['ramal'];
else $escrito = $_POST['fone'];

if(isset($_POST['celular']) && !empty($_POST['celular']) && $_POST['celular'] != "" && $_POST['celular'] != " ") $escrito .= " / ".$_POST['celular'];

imagettftext($image, 16, 0, 90, 245, $black, $font, $escrito." / WWW.PIA.COM.BR");
//header("Content-type:  image/jpeg");

$email = str_replace("@", ".", $_POST['email']);

imagejpeg($image, realpath("http://suporte.pia.com.br/ti/assinaturas/").$email.".jpg", 80);

// Chama o arquivo com a classe WideImage
require_once('../cron/wideImage/WideImage.php');
// Carrega a imagem a ser manipulada
$image = WideImage::load($email.".jpg");
// Redimensiona a imagem
$image = $image->resize(430, 223, 'outside');
// Salva a imagem em um arquivo (novo ou não)
$image->saveToFile($email.".jpg");

rename($email.".jpg", "../assinaturas/".$email.".jpg");

$sucesso = "sim";


}

?>

<title>Gerenciar Assinaturas</title>

<style>

	body {
	    margin: 0px;
	    padding-top: 52px;
	    background: #EEE;
	}

	form {
	    background: #FFF;
	    width: 20%;
	    border: 1px solid #CCC;
	    padding: 43px 5%;
	    margin-left: 35%;
	}

	input {
	    width: 100%;
	    border: 1px #CCC solid;
	    padding: 16px;
	    margin-bottom: 7px;
	}

	h1 {
		color: #333;
	    font-size: 25px;
	    text-align: center;
	    font-family: calibri;
	    font-weight: normal;
	}

	p#erro {
	    background: rgba(255,50,50,0.52);
	    border: 1px solid rgba(255,0,0,0.34);
	    padding: 12px;
	    text-align: center;
	    color: #FFF;
	    font-family: calibri;
	}

	div#sucesso, div#lista {
	    position: fixed;
	    top: 0;
	    left: 0;
	    background: rgba(255,255,255,.7);
	    width: 100%;
	    height: 100%;
	    display: none;
	    z-index: 99;
	}

	div#modal {
	    width: 30%;
	    margin-left: 35%;
	    text-align: center;
	    padding: 25px 0;
	    background: rgba(30, 154, 45, 0.79);
	    color: #FFF;
	    font-size: 22px;
	    font-family: calibri;
	    border: 1px solid rgba(30, 154, 45, 0.97);
	    margin-top: 160px;
	}

	div#modal2 {
	    width: 50%;
	    margin-left: 20%;
	    text-align: center;
	    padding: 25px 5%;
	    background: rgba(255, 255, 255, 0.79);
	    color: #FFF;
	    font-size: 16px;
	    font-family: calibri;
	    border: 1px solid rgba(0, 0, 0, 0.16);
	    margin-top: 16px;
	    display: none;
	    max-height: 520px;
	    position: fixed;
	    top: 0;
	    left: 0;
	    overflow-y: scroll; 
	    z-index: 999;
	}

	#modal2 a {
	    display: block;
	    padding: 7px;
	    color: #333;
	    text-align: left;
	    text-decoration: none;
	}

	#modal2 a:nth-child(even) {
	    background: #DDD;
	}

	#modal2 a:hover, a#mostrarImagens:hover {
	    background: #38C;
	    color: #FFF;
	}

	a#mostrarImagens {
	    position: fixed;
	    bottom: 16px;
	    left: 16px;
	    width: 200px;
	    text-align: center;
	    background: #333;
	    color: #FFF;
	    padding: 16px 0;
	    font-family: calibri;
	    cursor: pointer;
	    z-index: 0;
	}

	</style>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script>

	$( function() {
		$("#sucesso").click(function() {
			$("#sucesso").fadeOut();
		});

		$("#mostrarImagens").click(function() {
			$("#lista").fadeIn();
			$("#modal2").fadeIn();
		});

		$("#lista").click(function() {
			$("#lista").fadeOut();
			$("#modal2").fadeOut();
		});

	});

	</script>

	<?php if(isset($sucesso)) echo "<div id='sucesso' style='display: block'><div id='modal'>Inserido com Sucesso!</div></div>"; ?>

	<form method="post" action="./">

	<h1>Gerenciar Assinaturas</h1>

	<?php if(isset($erro)) echo "<p id='erro'>".$erro."</p>"; ?>

	<input type="text" required placeholder="Nome Completo" name="nome">

	<input type="email" required placeholder="E-mail" name="email">

	<input type="text" required placeholder="Setor" name="setor">

	<input type="text" placeholder="Filial" name="filial">

	<input type="phone" required placeholder="Telefone" title="Ex.: (99) 9999 9999" name="fone" pattern="[\(]\d{2}[\)][ ]\d{4}[ ]\d{4}">

	<input type="phone" placeholder="Ramal" name="ramal" title="Ex.: 9999" pattern="\d{4}">

	<input type="phone" placeholder="Celular" name="celular" title="Ex.: (99) 9999 9999" pattern="[\(]\d{2}[\)][ ]\d{4}[ ]\d{4}">

	<input type="submit" value="Adicionar">

	</form>

	<a id="mostrarImagens">Ver Assinaturas</a>


	<div id="lista"></div><div id="modal2"><h1>Lista de Assinaturas</h1>

	<?php
	   $path = "./";
	   $diretorio = dir($path);
	    
	    // echo "Lista de Arquivos do diretório '<strong>".$path."</strong>':<br />";    
	   while($arquivo = $diretorio -> read()){
	   	if(strlen($arquivo) > 3 && $arquivo != "index.php") $arrayImg[] = $arquivo;
	   }
	   
	   $diretorio -> close();

	   sort($arrayImg);
	   foreach($arrayImg as $arquivo) {
	      echo "<a target='_blank' href='".$path.$arquivo."'>".$arquivo."</a>";
	   }
	?>

	</div>

<?php }

else {

if(isset($_POST['usuario'])) {

	if($_POST['usuario'] == "admin" && $_POST['senha'] == "AdmSenhas142536") {
		$_SESSION['login'] = "Administrador";
		header("location: ./");
	}
	else $erro = "Usuário ou Senha Incorretos!";

}


?>

<title>Gerenciar Assinaturas</title>

	<style>

	body {
	    margin: 0px;
	    padding-top: 52px;
	    background: #EEE;
	}

	form {
	    background: #FFF;
	    width: 20%;
	    border: 1px solid #CCC;
	    padding: 43px 5%;
	    margin-left: 35%;
	}

	input {
	    width: 100%;
	    border: 1px #CCC solid;
	    padding: 16px;
	    margin-bottom: 7px;
	}

	h1 {
		color: #333;
	    font-size: 25px;
	    text-align: center;
	    font-family: calibri;
	    font-weight: normal;
	}

	p#erro {
	    background: rgba(255,50,50,0.52);
	    border: 1px solid rgba(255,0,0,0.34);
	    padding: 12px;
	    text-align: center;
	    color: #FFF;
	    font-family: calibri;
	}

	</style>

	<form method="post" action="./">

	<h1>Gerenciar Assinaturas</h1>

	<?php if(isset($erro)) echo "<p id='erro'>".$erro."</p>"; ?>

	<input type="text" required placeholder="Usuário" name="usuario">

	<input type="password" required placeholder="Senha" name="senha">

	<input type="submit">

	</form>

<?php } ?>
