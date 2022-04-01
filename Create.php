<?php

require_once("required_codePieces/db_conn.php");

// echo $_SERVER['REQUEST_METHOD'].'<br>';

// echo '<pre>';
// var_dump($_FILES);
// echo '</pre>';
// die;

$errors = [];

/*
    we're creating these variables so that we avoid losing the data
    we typed in the input statement or text-areas in the case of invalidation
    for our form. 
    value="<?php echo $var; ?>"
*/
$TheTitle = '';
$TheDesc = '';
$ThePrice = '';
$TheImage = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $TheTitle = $_POST['Title'];
    $TheDesc = $_POST['Description'];
    $ThePrice = $_POST['Price'];
    $TheDate = date('Y-m-d H:i:s');

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
        $picPath = '';

        if ($TheImage) { // <--- problem of those who don't have pictures how can we not generate the path(folder)...?

            $picPath = 'Pictures/'.randomString(5).'/'.$TheImage['name'];
            mkdir(dirname('./'.$picPath));

            move_uploaded_file($TheImage['tmp_name'], $picPath);
        }
        
        $statement = $pdo->prepare("INSERT INTO crud_products (title, image, description, price, create_date) VALUES (:TheTitle, :picPath, :TheDesc, :ThePrice, :TheDate);"); // :var --> called named parameter.

        $statement->bindValue(':TheTitle',$TheTitle);
        $statement->bindValue(':picPath',$picPath);
        $statement->bindValue(':TheDesc',$TheDesc);
        $statement->bindValue(':ThePrice',$ThePrice);
        $statement->bindValue(':TheDate',$TheDate);

        $statement->execute();
        //echo $statement->rowCount();
        header('Location: Home.php');
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
    <h1>CREATE NEW Product</h1>

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

    <form action="Create.php" method="post" enctype="multipart/form-data"> <!-- get is good example for a search form!!! -->
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