<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include "config/database.php";

if (!isset($_GET["booking_id"])) {
    header("Location: my_bookings.php");
    exit();
}

$booking_id = $_GET["booking_id"];

$user_id = $_SESSION["user_id"];

$sql = "SELECT
            bookings.*,
            desks.desk_number
        FROM bookings

        INNER JOIN desks
        ON bookings.desk_id = desks.id

        WHERE bookings.id = ?
        AND bookings.user_id = ?
        AND bookings.status = 'Approved'";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "ii",
    $booking_id,
    $user_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$booking = mysqli_fetch_assoc($result);

if (!$booking) {

    echo "Booking not found or not approved.";
    exit();

}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $payment_method = $_POST["payment_method"];

    $amount = $booking["total_amount"];

    $sql = "INSERT INTO payments
            (booking_id, amount, payment_method, payment_status)
            VALUES (?, ?, ?, 'Paid')";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ids",
        $booking_id,
        $amount,
        $payment_method
    );

    if (mysqli_stmt_execute($stmt)) {

        $message = "Payment successful!";

    } else {

        $message = "Payment failed.";

    }

}

?>

<?php include "includes/header.php"; ?>

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-6">

            <div class="card shadow">

                <div class="card-body">

                    <h2 class="text-center mb-4">
                        Make Payment
                    </h2>

                    <?php if (!empty($message)): ?>

                        <div class="alert alert-success">
                            <?php echo $message; ?>
                        </div>

                        <a
                            href="my_bookings.php"
                            class="btn btn-primary w-100">

                            Back to My Bookings

                        </a>

                    <?php else: ?>

                        <p>
                            <strong>Desk:</strong>
                            <?php echo htmlspecialchars($booking["desk_number"]); ?>
                        </p>

                        <p>
                            <strong>Start Date:</strong>
                            <?php echo $booking["start_date"]; ?>
                        </p>

                        <p>
                            <strong>End Date:</strong>
                            <?php echo $booking["end_date"]; ?>
                        </p>

                        <h4 class="mb-4">
                            Total: Rs. <?php echo $booking["total_amount"]; ?>
                        </h4>

                        <form method="POST">

                            <div class="mb-3">

                                <label class="form-label">
                                    Payment Method
                                </label>

                                <select
                                    name="payment_method"
                                    class="form-control"
                                    required>

                                    <option value="">
                                        Select Payment Method
                                    </option>

                                    <option value="Cash">
                                        Cash
                                    </option>

                                    <option value="eSewa">
                                        eSewa
                                    </option>

                                    <option value="Khalti">
                                        Khalti
                                    </option>

                                </select>

                            </div>

                            <button
                                type="submit"
                                class="btn btn-success w-100">

                                Pay Rs. <?php echo $booking["total_amount"]; ?>

                            </button>

                        </form>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include "includes/footer.php"; ?>