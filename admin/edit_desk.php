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

$sql = "SELECT * FROM desks WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$desk = mysqli_fetch_assoc($result);

if (!$desk) {
    echo "Desk not found.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $desk_number = trim($_POST["desk_number"]);
    $desk_type = trim($_POST["desk_type"]);
    $location = trim($_POST["location"]);
    $description = trim($_POST["description"]);
    $price_per_day = $_POST["price_per_day"];
    $status = $_POST["status"];

    $sql = "UPDATE desks
            SET desk_number = ?,
                desk_type = ?,
                location = ?,
                description = ?,
                price_per_day = ?,
                status = ?
            WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssssdsi",
        $desk_number,
        $desk_type,
        $location,
        $description,
        $price_per_day,
        $status,
        $id
    );

    if (mysqli_stmt_execute($stmt)) {

        header("Location: desks.php");
        exit();

    } else {

        echo "Failed to update desk.";

    }
}

?>

<?php include "../includes/header.php"; ?>

<div class="container mt-5">

    <h2 class="text-center mb-4">
        Edit Desk
    </h2>

    <div class="card shadow">

        <div class="card-body">

            <form method="POST">

                <div class="mb-3">

                    <label class="form-label">
                        Desk Number
                    </label>

                    <input
                        type="text"
                        name="desk_number"
                        class="form-control"
                        value="<?php echo htmlspecialchars($desk["desk_number"]); ?>"
                        required
                    >

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Desk Type
                    </label>

                    <select name="desk_type" class="form-control" required>

                        <option value="Single Desk"
                            <?php if ($desk["desk_type"] == "Single Desk") echo "selected"; ?>>
                            Single Desk
                        </option>

                        <option value="Premium Desk"
                            <?php if ($desk["desk_type"] == "Premium Desk") echo "selected"; ?>>
                            Premium Desk
                        </option>

                        <option value="Private Desk"
                            <?php if ($desk["desk_type"] == "Private Desk") echo "selected"; ?>>
                            Private Desk
                        </option>

                    </select>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Location
                    </label>

                    <input
                        type="text"
                        name="location"
                        class="form-control"
                        value="<?php echo htmlspecialchars($desk["location"]); ?>"
                        required
                    >

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Price Per Day
                    </label>

                    <input
                        type="number"
                        name="price_per_day"
                        class="form-control"
                        value="<?php echo $desk["price_per_day"]; ?>"
                        required
                    >

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Description
                    </label>

                    <textarea
                        name="description"
                        class="form-control"
                        rows="3"><?php echo htmlspecialchars($desk["description"]); ?></textarea>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Status
                    </label>

                    <select name="status" class="form-control">

                        <option value="Available"
                            <?php if ($desk["status"] == "Available") echo "selected"; ?>>
                            Available
                        </option>

                        <option value="Occupied"
                            <?php if ($desk["status"] == "Occupied") echo "selected"; ?>>
                            Occupied
                        </option>

                        <option value="Maintenance"
                            <?php if ($desk["status"] == "Maintenance") echo "selected"; ?>>
                            Maintenance
                        </option>

                    </select>

                </div>

                <button type="submit" class="btn btn-success">
                    Update Desk
                </button>

                <a href="desks.php" class="btn btn-secondary">
                    Cancel
                </a>

            </form>

        </div>

    </div>

</div>

<?php include "../includes/footer.php"; ?>