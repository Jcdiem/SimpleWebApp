<?php
// Start the user's session
session_start();

// Required our database connection
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/db.php";

// Check that there are contents in the cart, otherwise redirect back to show the empty cart message
if (empty($_SESSION['cart'])) {
    header("Location: /cart/");
    exit();
}


// Form variables
$myname = $_REQUEST['name'];
$mystreet = $_REQUEST['street'];
$mycity = $_REQUEST['city'];
$mystate = $_REQUEST['state'];
$myzip = $_REQUEST['zip'];
$mycreditcard = $_REQUEST['creditcard'];
$myexpiration = $_REQUEST['expiration'];
$mysecuritycode = $_REQUEST['securitycode'];

?>
<!DOCTYPE HTML>
<html lang=en>

<head>
    <title>Disco Juice - Checkout</title>
    <style>
        .error {
            border: 1px solid red;
            color: red;
            padding: .5rem;
            width: 50rem;
        }

        th {
            text-align: right;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
</head>

<body>
<h1>Checkout</h1>

<?php

//BEGIN: If-else field check
// If ALL of the fields have been submitted, enter the order
if ($_REQUEST['csrf_token'] === $_SESSION['csrf_token'] && !empty($myname)
    && !empty($mystreet) && !empty($mycity)
    && !empty($myzip) && !empty($mycreditcard) && !empty($myexpiration)
    && !empty($mysecuritycode) && !empty($mystate)) {

    if (!is_string($myname) || !ctype_alpha($myname)) echo "ERROR: Improper name.";
    else if (!is_string($mystreet)) echo "ERROR: Improper street";
    else if (!is_string($mycity)) echo "ERROR: Improper city";
    else if (!strlen($myzip)==5 || !ctype_digit($myzip)) echo "ERROR: Improper zip";
    else if (!strlen($mycreditcard)==16 || !ctype_digit($mycreditcard)) echo "ERROR: Improper CC#";
    else if (!is_string($myexpiration)) echo "ERROR: Improper expiration";
    else if (!strlen($mysecuritycode)==3 || !ctype_digit($mysecuritycode)) echo "ERROR: Improper CVV";
    else if (!strlen($mystate)==2 || !ctype_alpha($mystate)) echo "ERROR: Improper state code (WI, MN, etc...)";
    else {
        // Insert the order into the database
        if (!($orderStmnt = $mysqli->prepare("INSERT INTO orders (name, street, city, state, zip, creditcard, expiration, securitycode) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        if (!$orderStmnt->bind_param("ssssssss", $myname, $mystreet, $mycity, $mystate, $myzip, $mycreditcard, $myexpiration, $mysecuritycode)) {
            echo "Binding parameters failed: (" . $orderStmnt->errno . ") " . $orderStmnt->error;
        }
        if (!$orderStmnt->execute()) {
            echo "Execute failed: (" . $orderStmnt->errno . ") " . $orderStmnt->error;
        }
        $order_id = mysqli_insert_id($mysqli);

        // Loop through the items in the shopping cart
        foreach ($_SESSION['cart'] as $item_product_id => $item) {
            foreach ($item as $item_price => $item_quantity) {
                $shopping_cart_total += $item_quantity * $item_price;

                // Foreach product ordered, add the product id, quantity, and price
                if (!($stmnt = $mysqli->prepare("INSERT INTO line_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)"))) {
                    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                }
                if (!$stmnt->bind_param("iiid", $order_id, $item_product_id, $item_quantity, $item_price)) {
                    echo "Binding parameters failed: (" . $stmnt->errno . ") " . $stmnt->error;
                }
                if (!$stmnt->execute()) {
                    echo "Execute failed: (" . $stmnt->errno . ") " . $stmnt->error;
                }
            }
        }

        // Now that everything is entered into the database, empty the cart & CSRF
        unset($_SESSION['csrf_token']);
        unset($_SESSION['cart']);
    }
    ?>

    <p>Thank you for your order! Your order confirmation number is <strong><?= $order_id ?></strong>, and you have been
        charged <strong>$<?= number_format($shopping_cart_total, 2) ?></strong>. Please allow 5-30 business days to
        receive it in the post.</p>
    <p><em>Just when you've forgotten about it, or decide you want a refund, it'll show up for sure! (Or just wait
            another day or two...)</em></p>
    <?php

// Else not ALL of the fields have been submitted, so show the form
} else {

    // If one or more of the fields have been submitted, display an error message
    if (isset($myname) || isset($mystreet) || isset($mycity) || isset($myzip) || isset($mycreditcard) || isset($myexpiration) || isset($mysecuritycode)) {
        echo "<p class='error'>ERROR: Please complete all fields.</p>";

    }
    ?>

    <p>Please enter your billing details.</p>
    <form id="daForm">
<!--    Pass along the token from how we got it (Session CSRF should not be touched until the end)-->
        <input type="hidden" name="csrf_token" value="<?php echo $_REQUEST['csrf_token']; ?>">
        <table>
            <tr>
                <th><label for="name">Name</label></th>
                <td><input id="name" type="text" name="name" maxlength="64" value="<?= $myname ?>" required/></td>
            </tr>
            <tr>
                <th><label for="street">Street</label></th>
                <td><input id="street" type="text" name="street" maxlength="64" value="<?= $mystreet ?>" required/></td>
            </tr>
            <tr>
                <th><label for="city">City</label></th>
                <td><input id="city" type="text" name="city" maxlength="64" value="<?= $mycity ?>" required/></td>
            </tr>
            <tr>
                <th><label for="state">State</label></th>
                <td><input id="state" type="text" name="state" size="2" maxlength="2" minlength="2" value="<?= $mystate ?>" required></td>
            </tr>
            <tr>
                <th><label for="zip">Zip</label></th>
                <td><input id="zip" type="text" maxlength="5" name="zip" value="<?= $myzip ?>" required/></td>
            </tr>
            <tr>
                <th><label for="creditcard">Credit Card</label></th>
                <td><input id="creditcard" type="text" name="creditcard" minlength="16" maxlength="16" value="<?= $mycreditcard ?>" required/></td>
            </tr>
            <tr>
                <th><label for="expiration">Expiration</label></th>
                <td><input id="expiration" type="month" name="expiration" value="<?= $myexpiration ?>" required/></td>
            </tr>
            <tr>
                <th><label for="securitycode">Security Code</label></th>
                <td><input id="securitycode" type="password" name="securitycode" maxlength="3" minlength="3"
                           value="<?= $mysecuritycode ?>" required/></td>

            </tr>
            <tr>
                <td></td>
                <td><input type="submit" value="Complete Purchase"/></td>
            </tr>
        </table>
    </form>

    <?php
// END: If-else field check
}
?>

<script>
$("#daForm").validate();
</script>

</body>
</html>
