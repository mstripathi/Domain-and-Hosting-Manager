<?php
session_start();
include("connection.php");

// Fetch the current user session
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$message = ''; // Initialize a message variable

// Add Domain
if (isset($_POST['add_domain'])) {
    $domain = mysqli_real_escape_string($conn, $_POST['domain']);
    $expiry = mysqli_real_escape_string($conn, $_POST['expiry']);
    $renewal_price = mysqli_real_escape_string($conn, $_POST['renewal_price']);
    $registrar = mysqli_real_escape_string($conn, $_POST['registrar']);
    $username = $_SESSION['username'];

    $query = "INSERT INTO domains (username, domain, expiry, renewal_price, registrar) VALUES ('$username', '$domain', '$expiry', '$renewal_price', '$registrar')";
    if (mysqli_query($conn, $query)) {
        $message = "Domain added successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}

// Delete Domain
if (isset($_POST['delete_domain'])) {
    $domain_id = (int) $_POST['domain_id'];
    $query = "DELETE FROM domains WHERE id = $domain_id AND username = '{$_SESSION['username']}'";
    
    if (mysqli_query($conn, $query)) {
        $message = "Domain deleted successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}

// Update Domain Expiry
if (isset($_POST['update_domain'])) {
    $domain_id = (int) $_POST['domain_id'];
    $domain = mysqli_real_escape_string($conn, $_POST['domain']);
    $expiry = mysqli_real_escape_string($conn, $_POST['expiry']);
    $renewal_price = mysqli_real_escape_string($conn, $_POST['renewal_price']);
    $registrar = mysqli_real_escape_string($conn, $_POST['registrar']);

    $query = "UPDATE domains SET domain = '$domain', expiry = '$expiry', renewal_price = '$renewal_price', registrar = '$registrar' WHERE id = $domain_id AND username = '{$_SESSION['username']}'";
    if (mysqli_query($conn, $query)) {
        $message = "Domain updated successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}

// Fetch user's domains
$query = "SELECT * FROM domains WHERE username = '{$_SESSION['username']}' ORDER BY expiry ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Domain Manager</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .top-buttons {
            display: flex;
            justify-content: space-between;
        }
        /* Conditional styling based on expiry */
        .expiring-soon {
            background-color: rgba(255, 0, 0, 0.3) !important; /* Faded red */
            color: black;
        }
        .expiring-in-three-months {
            background-color: rgba(255, 255, 0, 0.5) !important; /* Faded yellow */
        }
        .normal {
            background-color: white;
        }
        table {
            width: 70%; /* Reduce the width of the table by 30% */
            margin: auto; /* Center the table */
        }
        /* Set row height */
        tr {
            height: 60px; /* Adjust the row height as needed */
        }
    </style>
</head>
<body>
    <div class="container">

        <h1 class="mt-4">Welcome, <?php echo $_SESSION['username']; ?></h1>
        <br>

        <div class="top-buttons mt-4">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addDomainModal">Add Domain</button>
             <a href="self.php"><button type="button" class="btn btn-warning">Hosting Manager</button></a>
            <a href="logout.php" class="btn btn-secondary">Logout</a>
        </div>

        <br><br>

        <table class="table table-striped">
    <thead>
        <tr>
            <th>Domain</th>
            <th>Expiry</th>
            <th>Renewal Price</th>
            <th>Registrar</th>
            <th>Days Since Expiry</th> <!-- New column header -->
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <?php
            // Calculate expiry status
            $expiry_date = new DateTime($row['expiry']);
            $current_date = new DateTime();
            $interval = $current_date->diff($expiry_date);
            $days_until_expiry = $interval->days;

            // Determine days since expiry
            $days_since_expiry = $current_date > $expiry_date ? $interval->days : 0;

            // Determine the row class based on the expiry status
            if ($days_until_expiry <= 30) {
                $row_class = 'expiring-soon';
            } elseif ($days_until_expiry <= 90) {
                $row_class = 'expiring-in-three-months';
            } else {
                $row_class = 'normal';
            }
            ?>
            <tr class="<?php echo $row_class; ?>">
                <td><?php echo $row['domain']; ?></td>
                <td><?php echo $row['expiry']; ?></td>
                <td><?php echo $row['renewal_price']; ?></td>
                <td><?php echo $row['registrar']; ?></td>
                <td><?php echo $days_since_expiry; ?> days</td> <!-- New column for days since expiry -->
                <td>
                    <!-- Edit Button -->
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editDomainModal<?php echo $row['id']; ?>">Edit</button>

                    <!-- Delete Button -->
                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteDomainModal<?php echo $row['id']; ?>">Delete</button>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editDomainModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="editDomainModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Domain</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="post">
                                        <input type="hidden" name="domain_id" value="<?php echo $row['id']; ?>">
                                        <div class="form-group">
                                            <label for="domain">Domain</label>
                                            <input type="text" name="domain" class="form-control" value="<?php echo $row['domain']; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="expiry">Expiry Date (YYYY-MM-DD)</label>
                                            <input type="text" name="expiry" class="form-control" value="<?php echo $row['expiry']; ?>" placeholder="YYYY-MM-DD" required>
                                            <small class="form-text text-muted">Or select from calendar:</small>
                                            <input type="date" name="expiry_calendar" class="form-control" onchange="this.form.expiry.value = this.value;">
                                        </div>
                                        <div class="form-group">
                                            <label for="renewal_price">Renewal Price</label>
                                            <input type="text" name="renewal_price" class="form-control" value="<?php echo $row['renewal_price']; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="registrar">Registrar</label>
                                            <input type="text" name="registrar" class="form-control" value="<?php echo $row['registrar']; ?>" required>
                                        </div>
                                        <button type="submit" name="update_domain" class="btn btn-primary">Update</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteDomainModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="deleteDomainModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Delete Domain</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete the domain: <strong><?php echo $row['domain']; ?></strong>?
                                    <form method="post">
                                        <input type="hidden" name="domain_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="delete_domain" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>


        <?php if ($message): ?>
            <div class="alert alert-info" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Add Domain Modal -->
        <div class="modal fade" id="addDomainModal" tabindex="-1" aria-labelledby="addDomainModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Domain</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post">
                            <div class="form-group">
                                <label for="domain">Domain</label>
                                <input type="text" name="domain" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="expiry">Expiry Date (YYYY-MM-DD)</label>
                                <input type="text" name="expiry" class="form-control" placeholder="YYYY-MM-DD" required>
                                <small class="form-text text-muted">Or select from calendar:</small>
                                <input type="date" name="expiry_calendar" class="form-control" onchange="this.form.expiry.value = this.value;">
                            </div>
                            <div class="form-group">
                                <label for="renewal_price">Renewal Price</label>
                                <input type="text" name="renewal_price" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="registrar">Registrar</label>
                                <input type="text" name="registrar" class="form-control" required>
                            </div>
                            <button type="submit" name="add_domain" class="btn btn-success">Add Domain</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
