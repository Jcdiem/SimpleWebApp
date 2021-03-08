<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/db.php";
session_start();
//Create CSRF
try {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(128));
} catch (Exception $e) {
    echo "Huh, that's probably really bad...";
}

?>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Discount Juice :: Shop</title>

</head>
<body>
<?php
$searchTerm = "%" . $_REQUEST['searchTrm'] . "%";
?>
<h1>Discount Juice - Shop</h1>
<form id="searchForm">
    <label for="searchInput">Search Term</label>
    <input type="text" name="searchTrm" id="searchTrm">
    <input type="submit" value="Submit">
</form>
<p style="border: 1px solid black">
    Welcome to our shop, where we sell the juice. Riveting concept, with only the finest technology involved.
    <br>Why use lots of CSS when you can drink juice?
</p>
<ul id="shopItems">
    <?php
    if (!($stmnt = $mysqli->prepare("SELECT * FROM products WHERE name LIKE (?) ORDER BY name"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    if (!$stmnt->bind_param("s", $searchTerm)) {
        echo "Binding parameters failed: (" . $stmnt->errno . ") " . $stmnt->error;
    }
    if (!$stmnt->execute()) {
        echo "Execute failed: (" . $stmnt->errno . ") " . $stmnt->error;
    }
    if (!$result = $stmnt->get_result()) {
        echo "Gathering result failed: (" . $stmnt->errno . ") " . $stmnt->error;
    }
    while ($row = $result->fetch_array(MYSQLI_BOTH)) {
        //            echo $row;
        echo "<li><div style=\"border: 1px solid black\">";
        echo "<br>";

        echo "<p>";
        echo "<h2>{$row['name']}</h2><br>";
        echo "Price: \${$row['price']} <br>";
        echo "Main Ingredient: {$row['ingredient']} <br>";
        echo "Vendor ID: {$row['vendorid']} <br>"; ?>

        <!--    SWAPPING TO PURE HTML UNLESS STARTING WITH PHP TAG FROM HERE ON-->
        <form method='post' action='/cart/index.php' style='border: 1px dot-dash #ff0000'>
            <label for="quantity">Quantity:</label>
            <!--        Label tag has no *modern* 'for' attribute due to dynamic loading     -->
            <input type='number' name='quantity' placeholder='Enter desired amount'>
            <?php echo "<input type='hidden' name='product_id' readonly value='{$row['id']}'>"; ?>
            <input type="submit" value="Add to Cart">
            <!--    Close out all of our lovely tags-->
        </form>
        </p>
        </div></li>
        <?php
        //Close out the loop
    }
    ?>
</ul>
</body>
</html>
