<?php
$mysqli = new mysqli("localhost", "root", "@Kalyan14", "system1");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
