<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "admin") {
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";

$sql = "SELECT id, name, email, phone, role, created_at
        FROM users
        ORDER BY id DESC";

$result = mysqli_query($conn, $sql);

?>

<?php include "../includes/header.php"; ?>

<div class="container mt-5">

    <h2 class="text-center mb-4">
        User Management
    </h2>

    <div class="table-responsive">

        <table class="table table-bordered table-striped">

            <thead class="table-dark">

                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Registered</th>
                </tr>

            </thead>

            <tbody>

                <?php while ($user = mysqli_fetch_assoc($result)): ?>

                    <tr>

                        <td>
                            <?php echo $user["id"]; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($user["name"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($user["email"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($user["phone"]); ?>
                        </td>

                        <td>

                            <?php if ($user["role"] == "admin"): ?>

                                <span class="badge bg-danger">
                                    Admin
                                </span>

                            <?php else: ?>

                                <span class="badge bg-primary">
                                    User
                                </span>

                            <?php endif; ?>

                        </td>

                        <td>
                            <?php echo $user["created_at"]; ?>
                        </td>

                    </tr>

                <?php endwhile; ?>

            </tbody>

        </table>

    </div>

</div>

<?php include "../includes/footer.php"; ?>