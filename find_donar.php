

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Donor</title>
    <style>
        .container {
            width: 80%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ff0000; /* Red background */
            border-radius: 10px;
        }

        #result {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px;
        }

        table, th, td {
            border: 1px solid black;
            padding: 8px;
            margin: 20px;
            text-align: left;
        }

        th {
            background-color: #ff0000; /* Red header */
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 style="color: white;">Find Donor</h1>
    <form method="POST" action="">
    <form id="donorForm">
        <label for="blood_group" style="color: white;">Blood Group:</label>
        <select name="blood_group" id="blood_group">
            <option value="A+">A+</option>
            <option value="A+">A</option>

            <option value="A-">A-</option>
            <option value="B+">B+</option>
            <option value="B-">B-</option>
            <option value="AB+">AB+</option>
            <option value="AB-">AB-</option>
            <option value="O+">O+</option>
            <option value="O-">O-</option>
        </select>
        <br><br>
        <label for="location" style="color: white;">City:</label>
        <input type="text" name="location" id="location" required>
        <br><br>
        <button type="submit">Find Donors</button>
    </form>

    <div id="result"></div>
</div>

<script>
    document.getElementById("donorForm").addEventListener("submit", function(event) {
        event.preventDefault();
        var form = event.target;
        var formData = new FormData(form);

        fetch("find_donor.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            document.getElementById("result").innerHTML = data;
        })
        .catch(error => {
            console.error("Error:", error);
        });
    });
</script>

</body>
</html>

<?php
// Database connection
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "redcross"; // Your MySQL database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Form submission handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $blood_group = $_POST["blood_group"];
    $location = $_POST["location"];

    // Query to find donors matching the blood group and city
    $sql = "SELECT donors.name, donors.mobile, donation.blood_group, donation.location 
    FROM donors
    INNER JOIN donation ON donors.id = donation.donor_id 
    WHERE donation.blood_group = '$blood_group' AND donation.location = '$location'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data in table format
        echo "<table>";
        echo "<tr><th>Name</th><th>Contact</th><th>Blood Group</th><th>Location</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td>" . $row["mobile"] . "</td>";
            echo "<td>" . $row["blood_group"] . "</td>";
            echo "<td>" . $row["location"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No donors found matching the criteria.";
    }
}

$conn->close();
?>
