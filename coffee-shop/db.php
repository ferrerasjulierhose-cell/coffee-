<?php
// db.php
$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = ""; // change if needed
$DB_NAME = "coffee_shop";

$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if (!$conn) {
    die("DB Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8mb4");
?>
