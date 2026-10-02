<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "admin") {

    header("Location: ../login.php");
    exit();

}
include "../config/database.php";
$total_users = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM users")
)["total"];

$total_desks = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM desks")
)["total"];

$total_bookings = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM bookings")
)["total"];

$total_payments = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COALESCE(SUM(amount), 0) AS total FROM payments WHERE payment_status = 'Paid'")
)["total"];
?>

<?php include "../includes/header.php"; ?>

<div class="container mt-5">

    <h1 class="text-center">
        Admin Dashboard
    </h1>

    <p class="text-center lead">
        Welcome, <?php echo htmlspecialchars($_SESSION["user_name"]); ?>!
    </p>

    <div class="row mt-5">

    <div class="col-md-3 mb-3">

        <div class="card shadow text-center">

            <div class="card-body">

                <h5>Total Users</h5>

                <h2>
                    <?php echo $total_users; ?>
                </h2>

            </div>

        </div>

    </div>


    <div class="col-md-3 mb-3">

        <div class="card shadow text-center">

            <div class="card-body">

                <h5>Total Desks</h5>

                <h2>
                    <?php echo $total_desks; ?>
                </h2>

            </div>

        </div>

    </div>


    <div class="col-md-3 mb-3">

        <div class="card shadow text-center">

            <div class="card-body">

                <h5>Total Bookings</h5>

                <h2>
                    <?php echo $total_bookings; ?>
                </h2>

            </div>

        </div>

    </div>


    <div class="col-md-3 mb-3">

        <div class="card shadow text-center">

            <div class="card-body">

                <h5>Total Revenue</h5>

                <h2>
                    Rs. <?php echo $total_payments; ?>
                </h2>

            </div>

        </div>

    </div>

</div>
    <div class="row mt-5">

        <div class="col-md-3 mb-3">

            <div class="card text-center shadow">

                <div class="card-body">

                    <h5>Manage Desks</h5>

                    <a href="desks.php"
                       class="btn btn-primary">
                        Manage
                    </a>

                </div>

            </div>

        </div>


        <div class="col-md-3 mb-3">

            <div class="card text-center shadow">

                <div class="card-body">

                    <h5>Users</h5>

                    <a href="users.php"
                       class="btn btn-primary">
                        View Users
                    </a>

                </div>

            </div>

        </div>


        <div class="col-md-3 mb-3">

            <div class="card text-center shadow">

                <div class="card-body">

                    <h5>Bookings</h5>

                    <a href="bookings.php"
                       class="btn btn-primary">
                        View Bookings
                    </a>

                </div>

            </div>

        </div>


        <div class="col-md-3 mb-3">

            <div class="card text-center shadow">

                <div class="card-body">

                    <h5>Payments</h5>

                    <a href="payments.php"
                       class="btn btn-primary">
                        View Payments
                    </a>

                </div>

            </div>

        </div>

    </div>

    <div class="text-center mt-4">

        <a href="../logout.php"
           class="btn btn-danger">
            Logout
        </a>

    </div>

</div>

<?php include "../includes/footer.php"; ?>