<?php

session_start();

?>

<title>Listar Assinaturas</title>
<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>

<style>

	body {
	    margin: 0px;
	    padding-top: 52px;
	    background: #EEE;
	}

	#lista {
	    background: #FFF;
	    width: 40%;
	    border: 1px solid #CCC;
	    padding: 43px 5%;
	    margin-left: 25%;
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
	    text-decoration: none;
	}

	#lista a {
	    display: block;
	    padding: 7px;
	    color: #333;
	    text-align: left;
	    text-decoration: none;
	}

	#lista a:nth-child(even) {
	    background: #DDD;
	}

	#lista a:hover, a#mostrarImagens:hover {
	    background: #38C;
	    color: #FFF;
	}

	a#mostrarImagens.direita1 {
	    left: auto;
	    right: 16px;
	    bottom: 124px;
	}

	a#mostrarImagens.direita2 {
	    left: auto;
	    right: 16px;
	    bottom: 70px;
	}

	a#mostrarImagens.direita3 {
	    left: auto;
	    right: 16px;
	}

	</style>

	<a href="./gerenciar.php" target="_blank" id="mostrarImagens">Gerenciar Assinaturas</a>


	<div id="lista" ng-app="myApp" ng-controller="namesCtrl"><h1>Lista de Assinaturas <input placeholder="Pesquisar" id="pesquisar" ng-model="name"></h1>

	<a href="{{x}}" target="_blank" ng-repeat="x in names | filter:name | orderBy: ''">{{x}}</a>

	<script>

	var listaNomes = [
	<?php
	   $path = "./";
	   $diretorio = dir($path);
	   $x = 0;
	    
	    // echo "Lista de Arquivos do diretório '<strong>".$path."</strong>':<br />";

	   while($arquivo = $diretorio -> read()){
	   	if(strlen($arquivo) > 3 && $arquivo != "index.php" && $arquivo != "gerenciar.php" && !strstr($arquivo, ".pdf")) {
	   		$x++;
	   		if($x == 1) echo "'".$arquivo."'";
	   		else echo ", '".$arquivo."'";
	   	}
	   }
	   
	   $diretorio -> close();

	?>];

	angular.module('myApp', []).controller('namesCtrl', function($scope) {
    $scope.names = listaNomes;
});

</script>

	</div>


	<a href="./TutorialAssinaturas.pdf" target="_blank" class="direita1" id="mostrarImagens">Manual Interface Nova</a>
	<a href="./TutorialAssinaturasOld.pdf" target="_blank" class="direita2" id="mostrarImagens">Manual Interface Antiga</a>
	<a href="./TutorialAssinaturasCliente.pdf" target="_blank" class="direita3" id="mostrarImagens">Manual Cliente de E-mail</a>