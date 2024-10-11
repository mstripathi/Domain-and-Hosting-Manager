<?php
session_start();
include("connection.php");

// Fetch the current user session
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$message = ''; // Initialize a message variable

// Add Self-Hosting
if (isset($_POST['add_self_hosting'])) {
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $provider = mysqli_real_escape_string($conn, $_POST['provider']);
    $plan = mysqli_real_escape_string($conn, $_POST['plan']);
    $renewal_date = mysqli_real_escape_string($conn, $_POST['renewal_date']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $username = $_SESSION['username'];

    $query = "INSERT INTO self_hosting (username, type, provider, plan, renewal_date, price) VALUES ('$username', '$type', '$provider', '$plan', '$renewal_date', '$price')";
    if (mysqli_query($conn, $query)) {
        $message = "Record added successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}

// Delete Self-Hosting
if (isset($_POST['delete_self_hosting'])) {
    $self_hosting_id = (int) $_POST['self_hosting_id'];
    $query = "DELETE FROM self_hosting WHERE id = $self_hosting_id AND username = '{$_SESSION['username']}'";
    
    if (mysqli_query($conn, $query)) {
        $message = "Record deleted successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}

// Update Self-Hosting
if (isset($_POST['update_self_hosting'])) {
    $self_hosting_id = (int) $_POST['self_hosting_id'];
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $provider = mysqli_real_escape_string($conn, $_POST['provider']);
    $plan = mysqli_real_escape_string($conn, $_POST['plan']);
    $renewal_date = mysqli_real_escape_string($conn, $_POST['renewal_date']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);

    $query = "UPDATE self_hosting SET type = '$type', provider = '$provider', plan = '$plan', renewal_date = '$renewal_date', price = '$price' WHERE id = $self_hosting_id AND username = '{$_SESSION['username']}'";
    if (mysqli_query($conn, $query)) {
        $message = "Self-hosting record updated successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}

// Fetch user's self-hosting records
$query = "SELECT * FROM self_hosting WHERE username = '{$_SESSION['username']}' ORDER BY renewal_date ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hosting Manager</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .top-buttons {
            display: flex;
            justify-content: space-between;
        }
        table {
            width: 70%; /* Reduce the width of the table */
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
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addSelfHostingModal">Add Hosting</button>
             <a href="index.php"><button type="button" class="btn btn-warning">Domain Manager</button></a>
              
            <a href="logout.php" class="btn btn-secondary">Logout</a>
        </div>

        <br><br>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Provider</th>
                    <th>Plan</th>
                    <th>Renewal Date</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['type']; ?></td>
                        <td><?php echo $row['provider']; ?></td>
                        <td><?php echo $row['plan']; ?></td>
                        <td><?php echo $row['renewal_date']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                        <td>
                            <!-- Edit Button -->
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editSelfHostingModal<?php echo $row['id']; ?>">Edit</button>

                            <!-- Delete Button -->
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteSelfHostingModal<?php echo $row['id']; ?>">Delete</button>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editSelfHostingModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="editSelfHostingModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Hosting</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="post">
                                                <input type="hidden" name="self_hosting_id" value="<?php echo $row['id']; ?>">
                                                <div class="form-group">
                                                    <label for="type">Type</label>
                                                    <input type="text" name="type" class="form-control" value="<?php echo $row['type']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="provider">Provider</label>
                                                    <input type="text" name="provider" class="form-control" value="<?php echo $row['provider']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="plan">Plan</label>
                                                    <input type="text" name="plan" class="form-control" value="<?php echo $row['plan']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="renewal_date">Renewal Date (YYYY-MM-DD)</label>
                                                    <input type="text" name="renewal_date" class="form-control" value="<?php echo $row['renewal_date']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="price">Price</label>
                                                    <input type="text" name="price" class="form-control" value="<?php echo $row['price']; ?>" required>
                                                </div>
                                                <button type="submit" name="update_self_hosting" class="btn btn-primary">Update</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteSelfHostingModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="deleteSelfHostingModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Delete</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete the record for <strong><?php echo $row['type']; ?></strong>?
                                            <form method="post">
                                                <input type="hidden" name="self_hosting_id" value="<?php echo $row['id']; ?>">
                                                <button type="submit" name="delete_self_hosting" class="btn btn-danger">Delete</button>
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

        <!-- Add Self-Hosting Modal -->
        <div class="modal fade" id="addSelfHostingModal" tabindex="-1" aria-labelledby="addSelfHostingModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post">
                            <div class="form-group">
                                <label for="type">Type</label>
                                <input type="text" name="type" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="provider">Provider</label>
                                <input type="text" name="provider" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="plan">Plan</label>
                                <input type="text" name="plan" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="renewal_date">Renewal Date (YYYY-MM-DD)</label>
                                <input type="text" name="renewal_date" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="price">Price</label>
                                <input type="text" name="price" class="form-control" required>
                            </div>
                            <button type="submit" name="add_self_hosting" class="btn btn-success">Add</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
