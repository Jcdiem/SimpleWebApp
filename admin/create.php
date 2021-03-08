<?php require_once "force_login.php"?>
<?php require_once "db.php" ?>
<?php require_once "validation.php" ?>
<html>
<body>

<?php


$failure = false;

if ($_REQUEST['name']) {

    $myname = $mysqli->real_escape_string($_REQUEST['name']);
    $myprice = (double)$_REQUEST['price'];
    $mainIngred = $mysqli->real_escape_string($_REQUEST['ingred']);
    $vendorID = $_REQUEST['vendor'];

    if (validateString($myname) == false) {
        failValidation("Error in string {$myname} Encoding");
    }
    else if (checkDecimalRange($myprice, 0, 999.99) == false){
        failValidation("Error in price of item");
    }
    else if (checkIntegerRange($vendorID, 1, 999) == false) {
        failValidation("Improper vendor");
    }
    else if (validateString($mainIngred) == false) {
        failValidation("Error in the ingredient");
    }
    else{
        $sql = "INSERT INTO products (name, price, ingredient, vendorid) VALUES ('$myname', $myprice, '$mainIngred', $vendorID)";

        // This is the object-oriented style to query the database
        if ($mysqli->query($sql) === TRUE) {
            echo "Product $myname created successfully!<br />";
        } else {
            echo "Error: $sql <br />" . $mysqli->error;
        }
    }


}

?>

<form>
    <label>Name:</label>
    <input type="text" name="name"/>

    <label>Price:</label>
    <input type="text" name="price"/>

    <label>Main Ingredient:</label>
    <input type="text" name="ingred"/>

    <label>Vendor ID:</label>
    <input type="number" name="vendor"/>

    <input type="submit" value="Create Product"/>
</form>

</body>
</html>
