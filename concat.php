<?php 
//PEGA (GETA) A INFORMAÇÃO DA VARIAVEL SETADA NA URL
$id = $_GET["value"];

//CONEXAO COM BD
$mysql = new PDO("mysql:dbname=db_login;host=localhost",'root','');

$insert=$mysql->prepare("INSERT INTO tb_nota(user,ds_titulo,ds_nota) VALUES(?,?,?)");

$insert->bindParam(1,$id,PDO::PARAM_INT);
$insert->bindParam(2,$_POST["title"],PDO::PARAM_STR);
$insert->bindParam(3,$_POST["content"],PDO::PARAM_STR);
$insert->execute();

echo '<script language="javascript">
 window.location.replace("notas.php?value='.$id.'")</script>'; 
?>