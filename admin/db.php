
<?php
$mysqli = new mysqli("localhost", "webapadmin", "GD4s*4\$D@l*ANDP30@uG1%!t4TMe*x4^", "webapp");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
//echo $mysqli->host_info . "\n";
