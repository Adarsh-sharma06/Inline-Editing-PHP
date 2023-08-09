<?php
// Database connection
$db = mysqli_connect('localhost', 'root', '', 'demo');
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to escape user input to prevent SQL injection
function escape($db, $value) {
    return mysqli_real_escape_string($db, $value);
}

// Handle delete operation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = escape($db, $_POST['delete_id']);
    $sql = "DELETE FROM `program` WHERE id = $delete_id";
    mysqli_query($db, $sql);
}

// Handle update operation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['program_name'], $_POST['total_students'], $_POST['start_seat'], $_POST['end_seat'])) {
    $id = escape($db, $_POST['id']);
    $name = escape($db, $_POST['program_name']);
    $Total_students = escape($db, $_POST['total_students']);
    $Start_seat = escape($db, $_POST['start_seat']);
    $End_seat = escape($db, $_POST['end_seat']);

    // Corrected SQL query for update
    $sql = "UPDATE `program` SET `program_name`='$name',`total_students`='$Total_students',`start_seat`='$Start_seat',`end_seat`='$End_seat' WHERE id = $id";

    mysqli_query($db, $sql);
}

// Handle add row operation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    // Insert a new row with empty values
    $sql = "INSERT INTO `program` (`program_name`, `total_students`, `start_seat`, `end_seat`) VALUES ('', '', '', '')";
    mysqli_query($db, $sql);
}
?>

<!-- ... Rest of the HTML code remains the same ... -->

<!DOCTYPE html>
<html>
<head>
    <title>Inline Editing Example</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <th>Program_name</th>
                <th>Total_students</th>
                <th>Start_seat</th>
                <th>End_seat</th>
                <th>Action</th>
                <th>Delete</th>
            </tr>
            <?php
            $sql = "SELECT * FROM program";
            $result = mysqli_query($db, $sql);
            while ($res = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                    <td><?php echo $res['id']; ?></td>
                    <td contenteditable="true" class="edit"><?php echo $res['program_name']; ?></td>
                    <td contenteditable="true" class="edit"><?php echo $res['total_students']; ?></td>
                    <td contenteditable="true" class="edit"><?php echo $res['start_seat']; ?></td>
                    <td contenteditable="true" class="edit"><?php echo $res['end_seat']; ?></td>
                    <td>
                        <form method="post" action="demo.php">
                            <input type="hidden" name="id" value="<?php echo $res['id']; ?>">
                            <input type="hidden" name="program_name" value="<?php echo $res['program_name']; ?>">
                            <input type="hidden" name="total_students" value="<?php echo $res['total_students']; ?>">
                            <input type="hidden" name="start_seat" value="<?php echo $res['start_seat']; ?>">
                            <input type="hidden" name="end_seat" value="<?php echo $res['end_seat']; ?>">
                            <button type="submit" class="btn btn-success">Update</button>
                        </form>
                    </td>
                    <td>
                        <form method="post" action="demo.php" onsubmit="return confirm('Are you sure you want to delete this record?');">
                            <input type="hidden" name="delete_id" value="<?php echo $res['id']; ?>">
                            <button type="submit" name="delete" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
            <?php
            }
            mysqli_free_result($result);
            ?>
            <!-- Add Row Button -->
            <tr>
                <td colspan="6">
                    <form method="post" action="demo.php">
                        <button type="submit" name="add" class="btn btn-primary">Add Row</button>
                    </form>
                </td>
            </tr>
        </table>
    </div>
    <!-- Add Bootstrap JS and jQuery scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".edit").on("input", function() {
                var row = $(this).closest("tr");
                row.find("input[name='program_name']").val(row.find("td:eq(1)").text());
                row.find("input[name='total_students']").val(row.find("td:eq(2)").text());
                row.find("input[name='start_seat']").val(row.find("td:eq(3)").text());
                row.find("input[name='end_seat']").val(row.find("td:eq(4)").text());
            });
        });
    </script>
</body>
</html>
