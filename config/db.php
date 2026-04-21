<?php
/**
 * Database Connection Configuration
 * Referral Management System (RMS)
 */

$host     = "localhost";
$dbname   = "rms_db";
$username = "root";
$password = "";

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    error_log("DB Error: " . mysqli_connect_error());
    die("Database connection failed.");
}

mysqli_set_charset($conn, "utf8mb4");

define('DB_HOST', $host);
define('DB_NAME', $dbname);
define('APP_NAME', 'Referral Management System (RMS)');
define('SESSION_TIMEOUT', 1800);

function sanitize($data) {
    global $conn;
    return mysqli_real_escape_string($conn, stripslashes(trim($data)));
}

function executeQuery($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    if (!$result) {
        error_log("Query Error: " . mysqli_error($conn));
        return false;
    }
    return $result;
}

function getRow($query) {
    $result = executeQuery($query);
    return $result ? mysqli_fetch_assoc($result) : null;
}

function getRows($query) {
    $result = executeQuery($query);
    $data = array();
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }
    return $data;
}
?>
