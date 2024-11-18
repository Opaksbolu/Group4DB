<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SQL Injection Experiment</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-lg mx-auto bg-white p-8 rounded shadow">
        <h2 class="text-2xl font-bold mb-5">SQL Injection Experiment</h2>
        <form action="sql_injection_experiment.php" method="post" class="mb-5">
            <h3 class="font-semibold mb-2">Select Query</h3>
            <label for="selectInput" class="block mb-2">Enter Username:</label>
            <input type="text" name="selectInput" id="selectInput" class="w-full p-2 border rounded mb-3" required>
            <input type="submit" name="selectSubmit" value="Run Select Query" class="w-full bg-blue-500 text-white p-2 rounded mb-5">
        </form>
        
        <form action="sql_injection_experiment.php" method="post" class="mb-5">
            <h3 class="font-semibold mb-2">Update Query</h3>
            <label for="updateId" class="block mb-2">Enter User ID:</label>
            <input type="text" name="updateId" id="updateId" class="w-full p-2 border rounded mb-3" required>
            <label for="updateData" class="block mb-2">Enter New Username:</label>
            <input type="text" name="updateData" id="updateData" class="w-full p-2 border rounded mb-3" required>
            <input type="submit" name="updateSubmit" value="Run Update Query" class="w-full bg-green-500 text-white p-2 rounded">
        </form>

        <?php
        include 'db_connect.php';
        include 'header.php';


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['selectSubmit'])) {
                $input = $_POST['selectInput'];
                $sql = "SELECT * FROM Users WHERE username = '$input'";

                echo "<p>Generated Select Query: $sql</p>";

                $result = $conn->query($sql);
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "ID: " . $row['id'] . " - Username: " . $row['username'] . "<br>";
                    }
                } else {
                    echo "No results found.";
                }
            }

            if (isset($_POST['updateSubmit'])) {
                $id = $_POST['updateId'];
                $newUsername = $_POST['updateData'];
                $sql = "UPDATE Users SET username = '$newUsername' WHERE id = '$id'";

                echo "<p>Generated Update Query: $sql</p>";

                if ($conn->query($sql) === TRUE) {
                    echo "Record updated successfully.";
                } else {
                    echo "Error updating record: " . $conn->error;
                }
            }
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
