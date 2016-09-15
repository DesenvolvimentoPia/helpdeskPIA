<?php

ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

set_time_limit(200);

$file = file_get_contents("../listas/dadosAssinaturas.csv");

$file1 = str_replace(")", ") ", $file);
$file2 = str_replace("@", ".", $file1);
$file = str_replace(")  ", ") ", $file2);

$explode = explode("
", $file);

for($i = 0; $i < count($explode)-1; $i++) {
	
$item = explode(";", $explode[$i]);

//echo "<div>";


//		echo "<p><b>E-mail: </b>".$item[0]."<br>";
//		echo "<b>Nome: </b>".$item[1]."<br>";
//		echo "<b>Setor: </b>".$item[2]."<br>";
//		echo "<b>Centro: </b>".$item[3]."<br>";
//		echo "<b>Fone: </b>".$item[4]."</p>";


//echo "</div>";

//phpinfo();
$email = $item[0];

$image = imagecreatefromjpeg("foto.jpg");
$white = imageColorAllocate($image, 255, 255, 255);
$black = imageColorAllocate($image, 39, 36, 103);
$font = 'Local Brewery Four.ttf';
imagettftext($image, 40, 0, 30, 80, $black, $font, $item[1]);

if(isset($item[3]) && !empty($item[3]) && $item[3] != "" && $item[3] != " ") imagettftext($image, 25, 0, 30, 120, $black, $font, $item[2]." / ".$item[3]);
else imagettftext($image, 25, 0, 30, 120, $black, $font, $item[2]);

if($item[5] < 10000 && $item[5] != "" && $item[5] != " " && ! strstr($item[5], ")")) $item[5] = "ramal ".$item[5];
if($item[6] < 10000 && $item[6] != "" && $item[6] != " " && ! strstr($item[6], ")")) $item[6] = "ramal ".$item[6];


if(isset($item[4]) && !empty($item[4]) && $item[4] != "" && $item[4] != " ");
else $item[4] = "(54) 3281 8800";

if(isset($item[5]) && !empty($item[5]) && $item[5] != "" && $item[5] != " ") $escrito = $item[4]." / ".$item[5];
else $escrito = $item[4];

if(isset($item[6]) && !empty($item[6]) && $item[6] != "" && $item[6] != " ") $escrito .= " / ".$item[6];

imagettftext($image, 16, 0, 30, 219, $black, $font, $escrito." / WWW.PIA.COM.BR");
//header("Content-type:  image/jpeg");
imagejpeg($image, realpath("http://suporte.pia.com.br/ti/assinaturas/").$email.".jpg", 80);

// Chama o arquivo com a classe WideImage
require_once('wideImage/WideImage.php');
// Carrega a imagem a ser manipulada
$image = WideImage::load($email.".jpg");
// Redimensiona a imagem
$image = $image->resize(430, 223, 'outside');
// Salva a imagem em um arquivo (novo ou não)
$image->saveToFile($email.".jpg");

rename($email.".jpg", "../../rh/assinaturas/".$email.".jpg");


}

