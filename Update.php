<?php

require_once("required_codePieces/db_conn.php"); 

$id = $_GET['id'] ?? null ;

if (!$id) {
    header("Location: Home.php");
    file_put_contents("except.txt","yes");
}

$statement = $pdo->prepare('SELECT * FROM crud_products WHERE id = :id');
$statement->bindParam(':id',$id);

$statement->execute();
$p = $statement->fetch(PDO::FETCH_ASSOC);

$errors = [];

$TheTitle = $p['title'];
$TheDesc = $p['description'];
$ThePrice = $p['price'];
// $TheImage = $p['image'];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $TheTitle = $_POST['Title'];
    $TheDesc = $_POST['Description'];
    $ThePrice = $_POST['Price'];

    //condition to validate the create form.
    if (!$TheTitle) {
        $errors[] = 'You must specify the title for the product !!';
    }
    if (!$ThePrice) {
        $errors[] = 'You must specify the price for the product !!';
    }

    if (!is_dir('Pictures')) {
        mkdir('Pictures'); 
    }

    if(empty($errors)){ //only if the errors array is empty then we're inserting in DB.
    //inserting out data in the CRUD_DataBase...

        $TheImage = $_FILES['Image'] ?? null;
        $picPath = $p['image'];

        if ($TheImage) {

            if ($p['image']) {
                unlink($p['image']);
            }

            $picPath = 'Pictures/'.randomString(5).'/'.$TheImage['name'];
            mkdir(dirname('./'.$picPath));

            move_uploaded_file($TheImage['tmp_name'], $picPath);
        }
        
        $statement = $pdo->prepare('UPDATE crud_products SET title = :TheTitle,image = :picPath,description = :TheDesc,price = :ThePrice WHERE id = :id'); // :var --> called named parameter.

        $statement->bindParam(':TheTitle',$TheTitle);
        $statement->bindParam(':picPath',$picPath);
        $statement->bindParam(':TheDesc',$TheDesc);
        $statement->bindParam(':ThePrice',$ThePrice);



        $statement->bindParam(':id',$id);

        $statement->execute();


        if ($statement->rowCount() === 1) {
            header('Location: Home.php');
        }else{
            print_r($statement->errorInfo());
        }
        
    }
}

function randomString($n){
    $allChars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomStr = '';
    for ($i=0; $i < $n; $i++) { 
        $position = rand(0,strlen($allChars)-1);
        $randomStr .= $allChars[$position]; 
    }

    return $randomStr;
}

?>

<?php require_once("required_codePieces/head_html.php") ?>
<body>

    <p>
        <a href="Home.php" class="btn btn-secondary">Back To Products Overview</a>
    </p>

    <h1>UPDATE Product</h1>

    <!-- if the errors is not empty (contains error messages), then 
         we're displaying those error messages to the user so he 
         can try again and submit a valide creation form.
    -->
    <?php if(!empty($errors)): ?>

    <br>
    <div class="alert alert-danger" role="alert">
        <?php foreach ($errors as $error): ?>
            <div><?php echo $error.'<br>' ?></div>
        <?php endforeach; ?>    
    </div>
    <br>
    
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data"> <!-- get is good example for a search form!!! -->

        <?php if($p['image']): ?>  
            <img src="<?php echo $p['image']; ?>" class="thumb-image">  
        <?php endif; ?>    
            
        <div class="form-group">
            <label>Product's Image</label>
            <br>
            <input type="file" name="Image">
        </div>
        <br>
        <div class="form-group">
            <label>Product's Title</label>
            <input type="text" class="form-control" name="Title" value="<?php echo $TheTitle ?>">
        </div>
        <br>
        <div class="form-group">
            <label>Product Description</label>
            <textarea class="form-control" name="Description"><?php echo $TheDesc ?></textarea>
        </div>
        <br>
        <div class="form-group">
            <label>Product's Price</label>
            <input type="number" class="form-control" step="0.001" name="Price" value="<?php echo $ThePrice ?>">
        </div>
        <br>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

</body>
</html>