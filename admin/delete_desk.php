<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "admin") {
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";

if (!isset($_GET["id"])) {
    header("Location: desks.php");
    exit();
}

$id = $_GET["id"];

$sql = "DELETE FROM desks WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {

    header("Location: desks.php");
    exit();

} else {

    echo "Failed to delete desk.";

}

?>