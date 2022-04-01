<?php

require_once("required_codePieces/db_conn.php");

require_once("required_codePieces/Search.php");

$statement->execute();
$products = $statement->fetchAll(PDO::FETCH_ASSOC);
// echo '<pre>';
// var_dump($_GET);
// echo '</pre>';
// die;

?>
 
<?php require_once("required_codePieces/head_html.php") ?>
<body>
    <h1>Products List</h1>
    <p>
        <a href="Create.php" class="btn btn-success" target="_blank">CREATE</a>
    </p>

    <form>
        <div class="input-group mb-3">
            <input type="text" class="form-control" value="<?php echo $itemToFind; ?>" placeholder="Search for a product..." name="search">
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit" id="button-addon2">SEARCH</button>
            </div>
        </div>
    </form>

    <table class="table table-success table-striped">
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">Image</th>
            <th scope="col">Title</th>
            <th scope="col">Description</th>
            <th scope="col">Price</th>
            <th scope="col">Create Date</th>
            <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $i => $product): ?>
                <tr>
                    <th scope="row"><?php echo $i+1  ?></th>
                        <td>
                            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['title']; ?>" class="thumb-image">
                        </td>
                        <td><?php echo $product['title'] ?></td>
                        <td><?php echo $product['description'] ?></td>
                        <td><?php echo $product['price'] ?></td>
                        <td><?php echo $product['create_date'] ?></td>
                        <td>

                            <a href="Update.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">EDIT</a>
                            <form  style="display: inline-block;" action='Delete.php' method="post">
                                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                <button type="submit" class="btn btn-danger">DELETE</button>
                            </form>

                        </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</body>
</html>