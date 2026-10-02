<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "admin") {

    header("Location: ../login.php");
    exit();

}

include "../config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $desk_number = trim($_POST["desk_number"]);
    $desk_type = trim($_POST["desk_type"]);
    $location = trim($_POST["location"]);
    $description = trim($_POST["description"]);
    $price_per_day = $_POST["price_per_day"];

    if (
        empty($desk_number) ||
        empty($desk_type) ||
        empty($location) ||
        empty($price_per_day)
    ) {

        $message = "Please fill in all required fields.";

    } else {

        $sql = "INSERT INTO desks
                (desk_number, desk_type, location, description, price_per_day)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "ssssd",
            $desk_number,
            $desk_type,
            $location,
            $description,
            $price_per_day
        );

        if (mysqli_stmt_execute($stmt)) {

            $message = "Desk added successfully!";

        } else {

            $message = "Failed to add desk.";

        }

        mysqli_stmt_close($stmt);
    }
}

?>

<?php include "../includes/header.php"; ?>

<div class="container mt-5">

    <h2 class="text-center mb-4">
        Manage Desks
    </h2>

    <?php if (!empty($message)): ?>

        <div class="alert alert-info">
            <?php echo $message; ?>
        </div>

    <?php endif; ?>


    <div class="card shadow">

        <div class="card-body">

            <h4 class="mb-3">
                Add New Desk
            </h4>

            <form method="POST">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Desk Number
                        </label>

                        <input
                            type="text"
                            name="desk_number"
                            class="form-control"
                            placeholder="Example: D-001"
                            required
                        >

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Desk Type
                        </label>

                        <select
                            name="desk_type"
                            class="form-control"
                            required>

                            <option value="">
                                Select Type
                            </option>

                            <option value="Single Desk">
                                Single Desk
                            </option>

                            <option value="Premium Desk">
                                Premium Desk
                            </option>

                            <option value="Private Desk">
                                Private Desk
                            </option>

                        </select>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Location
                        </label>

                        <input
                            type="text"
                            name="location"
                            class="form-control"
                            placeholder="Example: Room A"
                            required
                        >

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Price Per Day
                        </label>

                        <input
                            type="number"
                            name="price_per_day"
                            class="form-control"
                            placeholder="Example: 500"
                            min="0"
                            required
                        >

                    </div>


                    <div class="col-12 mb-3">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea
                            name="description"
                            class="form-control"
                            rows="3"
                            placeholder="Describe the desk">
                        </textarea>

                    </div>

                </div>

                <button
                    type="submit"
                    class="btn btn-primary">

                    Add Desk

                </button>

            </form>

        </div>

    </div>

</div>



<hr class="my-5">

<h4 class="mb-3">All Desks</h4>

<table class="table table-bordered table-striped">

    <thead class="table-dark">

        <tr>
            <th>ID</th>
            <th>Desk Number</th>
            <th>Type</th>
            <th>Location</th>
            <th>Price/Day</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

    </thead>

    <tbody>

        <?php

        $sql = "SELECT * FROM desks ORDER BY id DESC";

        $result = mysqli_query($conn, $sql);

        while ($desk = mysqli_fetch_assoc($result)):

        ?>

        <tr>

            <td>
                <?php echo $desk["id"]; ?>
            </td>

            <td>
                <?php echo htmlspecialchars($desk["desk_number"]); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($desk["desk_type"]); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($desk["location"]); ?>
            </td>

            <td>
                Rs. <?php echo $desk["price_per_day"]; ?>
            </td>

            <td>
                <?php echo htmlspecialchars($desk["status"]); ?>
            </td>

            <td>

                <a
                    href="edit_desk.php?id=<?php echo $desk['id']; ?>"
                    class="btn btn-warning btn-sm">
                    Edit
                </a>

                <a
                    href="delete_desk.php?id=<?php echo $desk['id']; ?>"
                    class="btn btn-danger btn-sm"
                    onclick="return confirm('Are you sure you want to delete this desk?');">
                    Delete
                </a>

            </td>

        </tr>

        <?php endwhile; ?>

    </tbody>

</table>


<?php include "../includes/footer.php"; ?>