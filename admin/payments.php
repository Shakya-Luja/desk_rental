<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "admin") {
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";

$sql = "SELECT
            payments.*,
            users.name,
            users.email,
            desks.desk_number
        FROM payments

        INNER JOIN bookings
        ON payments.booking_id = bookings.id

        INNER JOIN users
        ON bookings.user_id = users.id

        INNER JOIN desks
        ON bookings.desk_id = desks.id

        ORDER BY payments.id DESC";

$result = mysqli_query($conn, $sql);

?>

<?php include "../includes/header.php"; ?>

<div class="container mt-5">

    <h2 class="text-center mb-4">
        Payment Management
    </h2>

    <div class="table-responsive">

        <table class="table table-bordered table-striped">

            <thead class="table-dark">

                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Desk</th>
                    <th>Booking ID</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>

            </thead>

            <tbody>

                <?php while ($payment = mysqli_fetch_assoc($result)): ?>

                    <tr>

                        <td>
                            <?php echo $payment["id"]; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($payment["name"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($payment["email"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($payment["desk_number"]); ?>
                        </td>

                        <td>
                            <?php echo $payment["booking_id"]; ?>
                        </td>

                        <td>
                            Rs. <?php echo $payment["amount"]; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($payment["payment_method"]); ?>
                        </td>

                        <td>

                            <span class="badge bg-success">
                                <?php echo htmlspecialchars($payment["payment_status"]); ?>
                            </span>

                        </td>

                        <td>
                            <?php echo $payment["payment_date"]; ?>
                        </td>

                    </tr>

                <?php endwhile; ?>

            </tbody>

        </table>

    </div>

</div>

<?php include "../includes/footer.php"; ?>