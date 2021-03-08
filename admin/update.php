<?php require_once "db.php" ?>
<?php require_once "force_login.php"?>
<?php require_once "validation.php" ?>
<html>
<body>

<?php

$myid = $_REQUEST['id'];
$myname = $_REQUEST['name'];
$myprice = $_REQUEST['price'];
$mainIngred = $_REQUEST['ingred'];
$vendorID = $_REQUEST['vendor'];
$textName = base64_decode($myname);
$maxInt = ($mysqli->query("SELECT COUNT(*) FROM products")) - 1;

//Perform validations
if (checkIntegerRange($myid, 0, $maxInt)) failValidation("Improper ID");
else if (validateString($myname)) failValidation("Error in String Encoding");
else if (checkDecimalRange($myprice, 0, 999.99)) failValidation("Error in price of item");
else if(!checkIntegerRange($vendorID,1,999)) failValidation("Improper vendor");
else {
    if ($_REQUEST['name']) {


        $sql = "UPDATE products SET name='$myname', price=$myprice WHERE id=$myid";

        if ($mysqli->query($sql) === TRUE) {
            echo "$textName updated successfully <br />";
        } else {
            echo "Error: $sql <br />" . $mysqli->error;
        }
    }

    $sql = "SELECT * FROM products WHERE id=$myid";

    // This is the procedural style to query the database
    $result = mysqli_query($mysqli, $sql);

    $row = mysqli_fetch_array($result);
}
?>
<form action="update.php" method="post">
    <input type="hidden" id="id" name="id" value=""/>

    <label>Name:</label>
    <input type="text" id="name" name="name" value=""/>

    <label>Price:</label>
    <input type="text" id="price" name="price" value=""/>

    <label>Main Ingredient:</label>
    <input type="text" id="ingredient" name="ingredient" value=""/>

    <label>Vendor ID:</label>
    <input type="text" id="vendor" name="vendor" value=""/>

    <input type="submit" value="update"/>
</form>
</body>
</html>
