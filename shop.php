<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/db.php" ?>
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

        //Give the paragraph the ID of the item's name
        //using a string builder
        $paraTag = '<p id="';
        $paraTag .= "{$row['name']}";
        $paraTag .= '">';

        echo $paraTag;
        echo "<h2>{$row[1]}</h2><br>";
        echo "Price: \${$row[2]} <br>";
        echo "Main Ingredient: {$row[3]} <br>";
        echo "Vendor ID: {$row[4]} <br>";
        echo "<button id='{}'>Add to Cart</button>";
        echo "</p>";
        echo "</div></li>";
        //echo "{$row['name']} {$row['price']} {$row['ingredient']} {$row['vendorid']}";
    }
    ?>
    <!-- <li><div></div></li> -->
</ul>
</p>
<script>
    function errOut() {
        alert("FATAL ERROR: Chek console for details.");
    }

    function addItem(itemID) {
        if (!Number.isInteger(itemID)) console.error("ERROR: ItemID of " + itemID + " is not a valid integer.";
        errOut();
    else
        if (itemID < 0) console.error("ERROR: ItemID is negative (" + itemID + ")");
        errOut();
    else
        {

        }
    }
</script>
</body>
</html>
