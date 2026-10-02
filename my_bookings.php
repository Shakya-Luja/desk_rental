<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include "config/database.php";

$user_id = $_SESSION["user_id"];

$sql = "SELECT
            bookings.*,
            desks.desk_number,
            desks.desk_type,
            desks.location
        FROM bookings
        INNER JOIN desks
        ON bookings.desk_id = desks.id
        WHERE bookings.user_id = ?
        ORDER BY bookings.id DESC";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $user_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

?>

<?php include "includes/header.php"; ?>

<div class="container mt-5">

    <h2 class="text-center mb-4">
        My Bookings
    </h2>

    <?php if (mysqli_num_rows($result) > 0): ?>

        <div class="table-responsive">

            <table class="table table-bordered table-striped">

                <thead class="table-dark">

                    <tr>
                        <th>Desk</th>
                        <th>Type</th>
                        <th>Location</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>

                </thead>

                <tbody>

                    <?php while ($booking = mysqli_fetch_assoc($result)): ?>

                        <tr>

                            <td>
                                <?php echo htmlspecialchars($booking["desk_number"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($booking["desk_type"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($booking["location"]); ?>
                            </td>

                            <td>
                                <?php echo $booking["start_date"]; ?>
                            </td>

                            <td>
                                <?php echo $booking["end_date"]; ?>
                            </td>

                            <td>
                                Rs. <?php echo $booking["total_amount"]; ?>
                            </td>

                            <td>
                               <td>

    <?php if ($booking["status"] == "Approved"): ?>

        <span class="badge bg-success">
            Approved
        </span>

        <br><br>

        <a
            href="payment.php?booking_id=<?php echo $booking['id']; ?>"
            class="btn btn-primary btn-sm">

            Pay Now

        </a>

    <?php elseif ($booking["status"] == "Rejected"): ?>

        <span class="badge bg-danger">
            Rejected
        </span>

    <?php else: ?>

        <span class="badge bg-warning text-dark">
            Pending
        </span>

    <?php endif; ?>

</td>
                            </td>

                        </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    <?php else: ?>

        <div class="alert alert-info text-center">
            You have no bookings yet.
        </div>

    <?php endif; ?>

</div>

<?php include "includes/footer.php"; ?>