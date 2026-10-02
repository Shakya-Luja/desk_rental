<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include "config/database.php";

if (!isset($_GET["id"])) {
    header("Location: desks.php");
    exit();
}

$desk_id = $_GET["id"];

$sql = "SELECT * FROM desks WHERE id = ? AND status = 'Available'";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $desk_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$desk = mysqli_fetch_assoc($result);

if (!$desk) {
    echo "Desk not found or unavailable.";
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $start_date = $_POST["start_date"];
    $end_date = $_POST["end_date"];

    if (empty($start_date) || empty($end_date)) {

        $message = "Please select both dates.";

    } elseif ($end_date < $start_date) {

        $message = "End date cannot be before start date.";

    } else {

        $start = new DateTime($start_date);
        $end = new DateTime($end_date);

        $days = $start->diff($end)->days + 1;

        $total_amount = $days * $desk["price_per_day"];

        $user_id = $_SESSION["user_id"];

        $sql = "INSERT INTO bookings
                (user_id, desk_id, start_date, end_date, total_amount)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "iissd",
            $user_id,
            $desk_id,
            $start_date,
            $end_date,
            $total_amount
        );

        if (mysqli_stmt_execute($stmt)) {

            header("Location: my_bookings.php");
            exit();

        } else {

            $message = "Booking failed.";

        }
    }
}

?>

<?php include "includes/header.php"; ?>

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-7">

            <div class="card shadow">

                <div class="card-body">

                    <h2 class="text-center mb-4">
                        Book Desk
                    </h2>

                    <div class="alert alert-light border">

                        <h5>
                            <?php echo htmlspecialchars($desk["desk_number"]); ?>
                        </h5>

                        <p class="mb-1">
                            <strong>Type:</strong>
                            <?php echo htmlspecialchars($desk["desk_type"]); ?>
                        </p>

                        <p class="mb-0">
                            <strong>Price:</strong>
                            Rs. <?php echo $desk["price_per_day"]; ?> / day
                        </p>

                    </div>

                    <?php if (!empty($message)): ?>

                        <div class="alert alert-danger">
                            <?php echo $message; ?>
                        </div>

                    <?php endif; ?>

                    <form method="POST">

                        <div class="mb-3">

                            <label class="form-label">
                                Start Date
                            </label>

                            <input
                                type="date"
                                name="start_date"
                                class="form-control"
                                required
                            >

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                End Date
                            </label>

                            <input
                                type="date"
                                name="end_date"
                                class="form-control"
                                required
                            >

                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary w-100">

                            Confirm Booking

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include "includes/footer.php"; ?>