<?php


    error_reporting(E_ALL);
    ini_set('display_errors', 'On');

phpinfo();

/*
 * Criamos a instância de nosso cliente de webservice.
 * Especificamos a localização e o namespace do servidor de
 * webservice. 
 * Passando null no primeiro parâmetro do construtor indicamos
 * que o webservice irá trabalhar no modo não WSDL.
 */
$options = array(
	'location' => 'http://10.1.10.195/soap/server.php',
	'uri' => 'http://10.1.10.195/soap/'
);

$client = new SoapClient(null, $options);

if($client) echo "Ué?";
/*
 * No Objeto $client podemos usar os métodos da classe 
 * SoapServerExemplo disponível em nosso webservice.
 */
echo $client->mensagem('Thiago') . "<br />";
echo $client->soma(3, 5) . "<br />"

?>