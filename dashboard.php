<?php

session_start();

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");
    exit();

}

?>

<?php include "includes/header.php"; ?>

<div class="container mt-5">

    <div class="text-center">

        <h1>
            Welcome, <?php echo htmlspecialchars($_SESSION["user_name"]); ?>!
        </h1>

        <p class="lead">
            Welcome to your Desk Rental Dashboard.
        </p>

        <div class="mt-4">

            <a href="desks.php" class="btn btn-primary">
                View Desks
            </a>

            <a href="my_bookings.php" class="btn btn-success">
                My Bookings
            </a>

            <a href="logout.php" class="btn btn-danger">
                Logout
            </a>

        </div>

    </div>

</div>

<?php include "includes/footer.php"; ?>