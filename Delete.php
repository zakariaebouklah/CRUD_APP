<?php 

//setting
require_once("required_codePieces/db_conn.php");

$id = $_POST['id'] ?? null ;

//if an id is empty we'll just head out to home
if (!$id) {
    header("Location: Home.php");
}

$statement = $pdo->prepare('DELETE FROM crud_products WHERE id = :id');
$statement->bindValue(':id',$id);
$statement->execute();

header('Location: Home.php');

?>