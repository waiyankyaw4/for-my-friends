<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<h2>User Data</h2>

<table>
    <thead>
        <tr>
            <th>Id</th>
            <th>Username</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        require_once('db.php');

        // Handle delete request
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_delete'])) {
            $id = $_POST['id'];
            $sql = "DELETE FROM users WHERE id = $id";
            if ($conn->query($sql) === TRUE) {
                echo "<p>Record deleted successfully.</p>";
            } else {
                echo "Error deleting record: " . $conn->error;
            }
        }

        // Handle update request
  // Handle update request
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
            $id = $_POST['id'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
  // Password validation


            $sql = "UPDATE users SET username='$username', email='$email', password='$password' WHERE id=$id";
            if ($conn->query($sql) === TRUE) {
                echo "<p>Record updated successfully.</p>";
            } else {
                echo "Error updating record: " . $conn->error;
            }
        }


        // Fetch and display data
        $sql = "SELECT * FROM users";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>";
                echo "<button onclick='showUpdateModal(" . $row['id'] . ", \"" . htmlspecialchars($row['username']) . "\", \"" . htmlspecialchars($row['email']) . "\", \"" . htmlspecialchars($row['password']) . "\")'>Update</button>";
                echo "<button onclick='showDeleteModal(" . $row['id'] . ")'>Delete</button>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No data found</td></tr>";
        }
        $conn->close();
        ?>
    </tbody>
</table>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="hideDeleteModal()">&times;</span>
        <h2>Are you sure you want to delete this record?</h2>
        <form action="" method="post">
            <input type="hidden" name="id" id="deleteId">
            <input type="submit" name="confirm_delete" value="Yes">
            <button type="button" onclick="hideDeleteModal()">No</button>
        </form>
    </div>
</div>

<!-- Update Modal -->
<div id="updateModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="hideUpdateModal()">&times;</span>
        <h2>Update User</h2>
        <form action="" method="post">
            <input type="hidden" name="id" id="updateId">
            <label for="updateUsername">Username:</label><br>
            <input type="text" id="updateUsername" name="username"><br>
            <label for="updateEmail">Email:</label><br>
            <input type="email" id="updateEmail" name="email"><br>
            <label for="updatePassword">Password:</label><br>
            <input type="text" id="updatePassword" name="password"><br>
            <input type="submit" name="update" value="Update">
        </form>
 

    </div>
</div>
<button type="button" class="btn btn-success"><a href="index.php"> Log out</a> </button>
 

<script>
// Modal handling
var deleteModal = document.getElementById("deleteModal");
var updateModal = document.getElementById("updateModal");

function showDeleteModal(id) {
    document.getElementById("deleteId").value = id;
    deleteModal.style.display = "block";
}

function hideDeleteModal() {
    deleteModal.style.display = "none";
}

function showUpdateModal(id, username, email, password) {
    document.getElementById("updateId").value = id;
    document.getElementById("updateUsername").value = username;
    document.getElementById("updateEmail").value = email;
    document.getElementById("updatePassword").value = password;
    updateModal.style.display = "block";
}

function hideUpdateModal() {
    updateModal.style.display = "none";
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target == deleteModal) {
        hideDeleteModal();
    }
    if (event.target == updateModal) {
        hideUpdateModal();
    }
}
</script>

</body>
</html>
