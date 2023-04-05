<?php

//CONEXAO COM BANCO
$mysql = new PDO("mysql:dbname=db_login;host=localhost",'root','');

//SELECT
function Select($mysql){
    $select = $mysql->prepare("SELECT * FROM tb_usuario ORDER BY id_usuario");

    $select->execute();
    $data = $select->fetchAll(PDO::FETCH_ASSOC);
    return $data;
    
}

function Insert($mysql,$campo1,$campo2){
    $insert = $mysql->prepare("INSERT INTO tb_usuario(nm_login, vl_senha) VALUES(?,?)");

    $insert->bindParam(1,$campo1,PDO::FETCH_ASSOC);
    $insert->bindParam(2,$campo2,PDO::FETCH_ASSOC);
    $insert->execute();
}


//GERENCIADOR
$acessoid = array();
$acessonome = array();
$acessosenha = array();

//PUXA OS VALORES DO BD
foreach (Select($mysql) as $row) {
    foreach ($row as $key => $value) {
        $key === "id_usuario" ? $acessoid[] = $value : null;
        $key === "nm_login" ? $acessonome[] = $value : null;
        $key === "vl_senha" ? $acessosenha[] = $value : null;
    }
}

//VERIFICA SE O USUÁRIO E SENHA EXISTEM
$chavenome = array_search($_POST["name"],$acessonome);
$chavesenha = array_search($_POST["password"],$acessosenha);


//CRIA USUARIO CASO NAO EXISTA
if($chavenome==false && $chavenome!==0){
    
 Insert($mysql,$_POST["name"],$_POST["password"]);
 $chavenome = count($acessonome)+1;
 echo '<script language="javascript">
 alert("Usuario e senha inexistente, gerando acesso com base nas credenciais informadas.");
 window.location.replace("notas.php?value='.$chavenome.'")</script>'; 
 
}

//RECONHECE USUARIO
else if($chavesenha==true){
    $chavenome++;
    echo'<script language="javascript">
    const data = "<?php echo $test; ?>";
    alert("Acessando seu usuário");
    window.location.replace("notas.php?value='.$chavenome.'")</script>';
}
//RECONHECE SENHA ERRADA
else{
    echo'<script language="javascript">
    alert("Usuario reconhecido, senha errada");
    window.location.replace("front.php");
    </script>';
}




?>