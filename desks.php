<?php

include "config/database.php";

$sql = "SELECT * FROM desks ORDER BY id DESC";

$result = mysqli_query($conn, $sql);

?>

<?php include "includes/header.php"; ?>

<div class="container mt-5">

    <h2 class="text-center mb-4">
        Available Desks
    </h2>

    <div class="row">

        <?php while ($desk = mysqli_fetch_assoc($result)): ?>

            <?php if ($desk["status"] == "Available"): ?>

                <div class="col-md-4 mb-4">

                    <div class="card shadow">

                        <div class="card-body">

                            <h5 class="card-title">

                                <?php
                                echo htmlspecialchars($desk["desk_number"]);
                                ?>

                            </h5>

                            <p>
                                <strong>Type:</strong>
                                <?php
                                echo htmlspecialchars($desk["desk_type"]);
                                ?>
                            </p>

                            <p>
                                <strong>Location:</strong>
                                <?php
                                echo htmlspecialchars($desk["location"]);
                                ?>
                            </p>

                            <p>
                                <strong>Price:</strong>
                                Rs.
                                <?php
                                echo htmlspecialchars($desk["price_per_day"]);
                                ?>/day
                            </p>

                            <p>
                                <?php
                                echo htmlspecialchars($desk["description"]);
                                ?>
                            </p>

                            <span class="badge bg-success">
                                Available
                            </span>

                            <br><br>

                            <a
                                <a
    href="book.php?id=<?php echo $desk['id']; ?>"
    class="btn btn-primary">

    Book Now

</a>

                            </a>

                        </div>

                    </div>

                </div>

            <?php endif; ?>

        <?php endwhile; ?>

    </div>

</div>

<?php include "includes/footer.php"; ?>