<?php

     //variables
     $host = "localhost";
     $dbUsername = "root";
     $dbPassword = "";
     $dbName = "test";
     // Název db lze kdykoliv změnit.

    $conn = new mysqli($host,$dbUsername, $dbPassword, @$dbName);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
$sql = "select * from tbtest";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
    echo "id: " . $row["ID"]. " ; Name: " . $row["Name"]. "<br>";
}
?>