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

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script>

	$( function() {
		$("#pesquisar").keyup(function() {
			var elementos = document.getElementsByClassName('linkEmail');
				//alert(elementos.length);
			for(var x = 0; x < elementos.length; x++) {
				var interno = elementos[x].innerHTML;
				//alert(interno);
				if(document.getElementById('pesquisar').value == "") {
					elementos[x].style.display = "block";	
				}

				else {
				 if(interno.search(document.getElementById('pesquisar').value) != -1)	elementos[x].style.display = "block";
				 else elementos[x].style.display = "none";			
				}
			}
		});
	});

	</script>


	<a href="./gerenciar.php" target="_blank" id="mostrarImagens">Gerenciar Assinaturas</a>


	<div id="lista"><h1>Lista de Assinaturas <input placeholder="Pesquisar" id="pesquisar"></h1>

	<?php
	   $path = "./";
	   $diretorio = dir($path);
	    
	    // echo "Lista de Arquivos do diretório '<strong>".$path."</strong>':<br />";    
	   while($arquivo = $diretorio -> read()){
	   	if(strlen($arquivo) > 3 && $arquivo != "index.php" && !strstr($arquivo, ".pdf")) $arrayImg[] = $arquivo;
	   }
	   
	   $diretorio -> close();

	   sort($arrayImg);
	   $x = 0;
	   foreach($arrayImg as $arquivo) {
	   	$x++;
	      echo "<a target='_blank' id='linkEmail".$x."' class='linkEmail' href='".$path.$arquivo."'>".$arquivo."</a>";
	   }
	?>

	</div>


	<a href="./TutorialAssinaturas.pdf" target="_blank" class="direita1" id="mostrarImagens">Manual Interface Nova</a>
	<a href="./TutorialAssinaturasOld.pdf" target="_blank" class="direita2" id="mostrarImagens">Manual Interface Antiga</a>
	<a href="./TutorialAssinaturasCliente.pdf" target="_blank" class="direita3" id="mostrarImagens">Manual Cliente de E-mail</a>