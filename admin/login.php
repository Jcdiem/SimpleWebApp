<?php
session_start();

//Create CSRF
try {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(128));
} catch (Exception $e) {
    echo "Huh, that's probably really bad...";
}

require_once $_SERVER['DOCUMENT_ROOT']."/admin/db.php";

$userName = $_REQUEST['username'];
$userPass = $_REQUEST['password'];

if (!($stmnt = $mysqli->prepare("SELECT * FROM users WHERE username=? AND password=SHA2(?,256)"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!$stmnt->bind_param("ss", $userName, $userPass)) {
    echo "Binding parameters failed: (" . $stmnt->errno . ") " . $stmnt->error;
}
if (!$stmnt->execute()) {
    echo "Execute failed: (" . $stmnt->errno . ") " . $stmnt->error;
}
if (!$result = $stmnt->get_result()) {
    echo "Gathering result failed: (" . $stmnt->errno . ") " . $stmnt->error;
}

$row = mysqli_fetch_assoc($result);

// This is what happens when a user successfully authenticates
if(!empty($row)) {
    unset($_SESSION['csrf_token']);
	session_destroy();
	session_start();

	$_SESSION['username'] = $row['username'];

	
// This is what happens when the username and/or password doesn't match
} else {
	echo "<p>Incorrect username OR password</p>";
}

if($_SESSION['username'] && $_SESSION['csrf_token'] == $_REQUEST['csrf_token']) {
	echo "<p>Welcome {$_SESSION[username]}</p>";

	header("Location: {$_REQUEST['redirect']}");
	exit();

} else {
?>
<html>
<body>

<form>
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
	<input type="hidden" name="redirect" value="<?= $_REQUEST['redirect'] ?>" />

	<label>Username:</label>
	<input type="text" name="username" />

	<label>Password:</label>
	<input type="password" name="password" />

	<input type="submit" value="Log In" />
</form>

<?php
}
?>

</body>
</html>
