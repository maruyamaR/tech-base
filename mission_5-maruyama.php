<!DOCTYPE html>
<html lang="ja" dir="ltr">
	<head>
		<meta charset="UTF-8" >
		<title>mission5</title>
	</head>

<body>

<?php

$dsn = 'mysql:dbname=***********;host=localhost';
	$user = '*********';
	$password = '**************';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

$sql = "CREATE TABLE IF NOT EXISTS dbform"
	." ("					
	. "id INT AUTO_INCREMENT PRIMARY KEY,"	
	. "name char(32),"
	. "comment TEXT,"
	. "bdate TEXT,"
	. "password TEXT"
	.");";
	$stmt = $pdo->query($sql);



//$sql ='SHOW TABLES';	//テーブル一覧を表示；
	//$result = $pdo -> query($sql);
	//foreach ($result as $row){
	//	echo $row[0];
	//	echo '<br>';
	//}
	//echo "<hr>";


//$sql ='SHOW CREATE TABLE dbform';	//テーブルの中身を確認；
	//$result = $pdo -> query($sql);
	//foreach ($result as $row){
	//	echo $row[1];
	//}
	//echo "<hr>";

////////////////投稿フォーム////////////////////////////////////////////////////////////////////////////////


if(!empty($_POST["name"]) && !empty($_POST["comment"]) &&  !empty($_POST["password"]) && !empty($_POST["toukou"]) && empty($_POST["editNo"]))
{
	$sql = $pdo -> prepare("INSERT INTO dbform (name, comment,  bdate, password) VALUES (:name, :comment, :bdate, :password )");
	$sql -> bindParam(':name', $name, PDO::PARAM_STR);	
	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	$sql -> bindParam(':bdate', $jdate, PDO::PARAM_STR);
	$sql -> bindParam(':password', $password, PDO::PARAM_STR);
	$name = $_POST["name"];
	$comment = $_POST["comment"]; 
	$date = new DateTime("now") ;
	$bdate = $date -> format('Y/m/d H:i:s');
	$password = $_POST["password"];
	$sql -> execute();
}



////////////////////削除フォーム///////////////////////////////////

if(!empty($_POST["deleteNo"]) && !empty($_POST["delPassword"]) && !empty($_POST["delSubmit"]))
{
	$id = $_POST["deleteNo"];
	$delpass = $_POST["delPassword"];
	$sql = 'SELECT * FROM dbform';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){

		if($id  == $row['id']){
			if($delpass == $row['password']){
				$sql = 'delete from dbform where id=:id';
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(':id', $id, PDO::PARAM_INT);
				$stmt->execute();
			}
			else{
				echo "パスワードが違います";
			}
		}
	}
}


//////////////////編集フォーム///////////////////////////////////////////////////

if(!empty($_POST["edit"]) && !empty($_POST["editPassword"]) && !empty($_POST["editSubmit"])){
	$editi = $_POST["edit"];	
	$editPassword = $_POST["editPassword"];
	$sql = 'SELECT * FROM dbform';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();

	foreach ($results as $row){

		if($editiId  == $row['id']){
			if($editpas == $row['password']){
				$editNo = $row['id'];
				$editName = $row['name'];
				$editComment = $row['comment'];
			}
			else{
				echo "パスワードが違います";
			}
		}
	}
}

///////////編集//////////////////////////////////////////////////

if(!empty($_POST["name"]) && !empty($_POST["comment"]) &&  !empty($_POST["password"]) && !empty($_POST["editNo"]))
{
	$id = $_POST["editNo"];
	$name = $_POST["name"];
	$comment =  $_POST["comment"];
	$date = new DateTime("now");
	$bdate = $date -> format('Y/m/d H:i:s');
	$password = $_POST["password"];
	$sql = 'update dbform set name=:name,comment=:comment, bdate=:bdate, password=:password where id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	$stmt->bindParam(':bdate', $bdate, PDO::PARAM_STR);
	$stmt -> bindParam(':password', $password, PDO::PARAM_STR);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>




<form method="POST" action="mission_5-maruyama.php">
	名前:<input type = "text"  name = "name" value = <?php
						if(!empty($editName))
						{	 
							echo $editName;
						}
						?>><br/>

	コメント:<input type = "text" name = "comment"  value = <?php 
						if(!empty($editComment))
						{
							echo $editComment;
						}
						?>><br/>
			<input type = "hidden" name = "editNo" value = <?php 
							if(!empty($editNo))	
							{
								echo $editNo;
							}
							?>>
	パスワード:<input type = "password" name = "password"><br/>
			<input type = "submit" name = "toukou" value = "送信"><br/>

	削除対象番号:<input type = "text" name = "deleteNo" ><br/>
	パスワード:<input type = "password" name = "delPassword"><br/>
			<input type = "submit" name = "deleteSubmit" value = "削除"><br/>

	編集対象番号:<input type = "text" name = "edit"><br/>
	パスワード:<input type = "password" name = "editPassword"><br/>
				<input type = "submit" name = "editSubmit" value = "編集"><br/>
</form>

<?php

$sql = 'SELECT * FROM dbform';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){

		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['bdate'].',';
		echo $row['password'].'<br>';
	echo "<hr>";
	}
?>	


</body>

</html>