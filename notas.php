<?php

//PEGA (GETA) A INFORMAÇÃO DA VARIAVEL SETADA NA URL
$id = $_GET["value"];

//CONECTAR COM BANCO MYSQL
$mysql = new PDO("mysql:dbname=db_login;host=localhost",'root','');

//ENCONTRA NOME USUARIO
function Find($mysql,$id){
	$select=$mysql->prepare("SELECT nm_login FROM tb_usuario WHERE id_usuario=?");
	$select->bindParam(1,$id,PDO::PARAM_INT);
	$select->execute();
	$data = $select->fetchAll(PDO::FETCH_ASSOC);
	$name = $data[0]["nm_login"];
	echo "de ".$name;
}

//SELECIONA NO BD
function Select($mysql,$id){
	$select = $mysql->prepare("SELECT * FROM tb_nota WHERE user=$id");
	$select->execute();
	$data = $select->fetchAll(PDO::FETCH_ASSOC);
	return $data;
}

//DELETA NOTA NO BD
function Delete($idnota,$mysql,$id){
	$delete=$mysql->prepare("DELETE FROM tb_nota WHERE id_nota=?");
	$delete->bindParam(1,$idnota,PDO::PARAM_INT);
	$delete->execute();
	header("location:notas.php?value=".$id);
	
	
}

//ESCREVE AS NOTAS
function Write($mysql,$id){
	$title = array();
	$text = array();

	//PUXANDO OS DADOS DAS NOTAS
	foreach (Select($mysql,$id) as $row) {
		foreach ($row as $key => $value) {
		//echo $key." ".$value."<br>";
			if($key==="id_nota"){
				$idnota[]=$value;
			}
			if($key==="ds_titulo"){
				$title[] = $value;
			}
			if($key==="ds_nota"){
				$text[] = $value;
			}	
		}
	}
	//ALTERAÇÕES NA NOTA	
	echo "<script>
	let a = 0;

	function Double(idnota){
		console.log(idnota);	
		window.location.replace('notas.php?value=".$id."&note='+idnota);
		".((isset($_GET['note']))?Delete($_GET['note'],$mysql,$id):null)."
	}
	function Click(props){
			console.log(document.getElementById(props).style.textDecoration);
			if(a===1){
			document.getElementById(props).style.textDecoration='none';
			}

			if(a===0){
			document.getElementById(props).style.textDecoration='line-through';
			}
			
			a===1?a=0:a=1;
		}</script>";

	//GERAÇÃO DAS NOTAS
	foreach ($title as $key => $value) {
			//echo $key." ".$value."<br>";
		echo '<div class="note" id="'.$key.'" onclick="Click('.$key.')" ondblclick="Double('.$idnota[$key].')">
		<h1 style="text-transform: uppercase">'.$value.'</h1>
		<p id="1'.$key.'" onclick="Click(1'.$key.')" class=>'.$text[$key].'</p>
		</div>';
		
	}
	
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Notas</title>
	<link rel="stylesheet" type="text/css" href="styles2.css">
</head>
<body>

	<header>
		<h1 style="text-transform: capitalize;">Notas 
			<?php Find($mysql,$id)?>
		</h1>
	</header>

	<div>
		
		<form method="post" action='<?php echo "concat.php?value=".$id ?>'>
			<input
			name="title"
			onChange=""
			style="text-transform: uppercase"
			value=""
			placeholder="Título"

			/>
			<textarea
			name="content"
			onChange=""
			value=""
			placeholder="Escreva uma nota..."
			rows="3"
			style="font-size: 12px"
			></textarea>
			<button type="submit">Add</button>
		</form>
	</div>
	<hr>
	<i style="font-size: 8px">*Um clique risca a nota;</i><br>
	<i style="font-size: 8px">*Dois cliques exclui a nota.</i><br>
	<?php Write($mysql,$id)?>

<!-- <iframe text-align= "center" src="" width="240px"> -->
  
</iframe>

</body>
</html>