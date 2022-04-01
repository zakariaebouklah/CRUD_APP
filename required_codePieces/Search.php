<?php 

$itemToFind = $_GET['search'] ?? '';

if ($itemToFind) {
    $statement = $pdo->prepare('SELECT * FROM crud_products WHERE title LIKE :title ORDER BY create_date DESC');
    $statement->bindValue(':title',"%$itemToFind%");
}else {
    $statement = $pdo->prepare('SELECT * FROM crud_products ORDER BY create_date DESC');
}

?>