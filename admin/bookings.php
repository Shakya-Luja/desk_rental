<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "admin") {
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";

if (isset($_GET["action"]) && isset($_GET["id"])) {

    $id = $_GET["id"];
    $action = $_GET["action"];

    if ($action == "approve") {

        $status = "Approved";

    } elseif ($action == "reject") {

        $status = "Rejected";

    } else {

        header("Location: bookings.php");
        exit();

    }

    $sql = "UPDATE bookings SET status = ? WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "si", $status, $id);

    mysqli_stmt_execute($stmt);

    header("Location: bookings.php");
    exit();
}


$sql = "SELECT
            bookings.*,
            users.name,
            users.email,
            desks.desk_number,
            desks.desk_type
        FROM bookings

        INNER JOIN users
        ON bookings.user_id = users.id

        INNER JOIN desks
        ON bookings.desk_id = desks.id

        ORDER BY bookings.id DESC";

$result = mysqli_query($conn, $sql);

?>

<?php include "../includes/header.php"; ?>

<div class="container mt-5">

    <h2 class="text-center mb-4">
        Manage Bookings
    </h2>

    <div class="table-responsive">

        <table class="table table-bordered table-striped">

            <thead class="table-dark">

                <tr>

                    <th>ID</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Desk</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Action</th>

                </tr>

            </thead>

            <tbody>

                <?php while ($booking = mysqli_fetch_assoc($result)): ?>

                    <tr>

                        <td>
                            <?php echo $booking["id"]; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($booking["name"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($booking["email"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($booking["desk_number"]); ?>
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

                            <?php if ($booking["status"] == "Approved"): ?>

                                <span class="badge bg-success">
                                    Approved
                                </span>

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

                        <td>

                            <?php if ($booking["status"] == "Pending"): ?>

                                <a
                                    href="bookings.php?action=approve&id=<?php echo $booking['id']; ?>"
                                    class="btn btn-success btn-sm">

                                    Approve

                                </a>

                                <a
                                    href="bookings.php?action=reject&id=<?php echo $booking['id']; ?>"
                                    class="btn btn-danger btn-sm">

                                    Reject

                                </a>

                            <?php else: ?>

                                <span class="text-muted">
                                    No action
                                </span>

                            <?php endif; ?>

                        </td>

                    </tr>

                <?php endwhile; ?>

            </tbody>

        </table>

    </div>

</div>

<?php include "../includes/footer.php"; ?>