<?php require_once "db.php" ?>
<!DOCTYPE php>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<title>Discount Juice :: Shop</title>
</head>
<body>
<?php 
    $searchTerm = "%" . $_REQUEST['s'] . "%"; 
?>
<h1>Discount Juice - Shop</h1>
<form id="searchForm">
    <label for="searchInput">Search Term</label>
    <input type="text" name="s" id="searchInput">
    <input type="submit" value="Submit">
</form>
<p style="border: 1px solid black">
Welcome to our shop, where we sell the juice. Riveting concept, with only the finest technology involved.
<br>Why use lots of CSS when you can drink juice?
<ul id="shopItems">
    <?php 
        if (!($stmnt = $mysqli->prepare("SELECT * FROM products WHERE name LIKE (?) ORDER BY name"))){
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        if (!$stmnt->bind_param("s",$searchTerm)) {
            echo "Binding parameters failed: (" . $stmnt->errno . ") " . $stmnt->error;
        }
        if (!$stmnt->execute()) {
            echo "Execute failed: (" . $stmnt->errno . ") " . $stmnt->error;
        }
        if (!$result = $stmnt->get_result()) {
            echo "Execute failed: (" . $stmnt->errno . ") " . $stmnt->error;
        }
        while($row = $stmnt->get_result()->fetch_array()){
            echo $stmnt->get_result();
            echo "<li><div style=\"border: 1px solid black\">";
                echo "<br>";
                echo "<p>";
                    echo "<h2>{$row['name']}</h2><br>";
                    echo "Price: \${$row['price']} <br>";
                    echo "Main Ingredient: {$row['ingredient']} <br>";
                    echo "Vendor ID: {$row['vendorid']}";
                echo "</p>";
            echo "</div></li>";
            //echo "{$row['name']} {$row['price']} {$row['ingredient']} {$row['vendorid']}";
        }
    ?>
<!-- <li><div></div></li> -->
</ul>
</p>
</body>
</html>
