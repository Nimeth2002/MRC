<?php
// Example database connection credentials
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "results.db";

// Create database connection
$conn = new SQLite3($dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . $conn->lastErrorMsg());
}

// Get the query parameter from the AJAX request
if(isset($_POST["query"])){
    $query = $_POST["query"];

    // Query the database for suggestions based on the user's input
    $sql = "SELECT fullName, indexNo FROM results WHERE fullName LIKE '%" . $query . "%' OR indexNo LIKE '%" . $query . "%' LIMIT 5";
    $result = $conn->query($sql);

    // Convert the result to JSON format and return it to the client
    $data = array();
    while($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $data[] = $row["fullName"] . " (" . $row["indexNo"] . ")";
    }
    echo json_encode($data);
}

// Get the selection parameter from the AJAX request
if(isset($_POST["selection"])){
    $selection = $_POST["selection"];
    $name = explode(" (", $selection)[0];
    $index = str_replace(")", "", explode(" (", $selection)[1]);

    // Query the database for the selected suggestion
    $stmt = $conn->prepare('SELECT * FROM results WHERE fullName = :fullName AND indexNo = :indexNo');
    $stmt->bindValue(':fullName', $name, SQLITE3_TEXT);
    $stmt->bindValue(':indexNo', $index, SQLITE3_TEXT);
    $result = $stmt->execute();

    // Convert the result to JSON format and return it to the client
    $data = array();
    while($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data);
}

// Close database connection
$conn->close();
?>
