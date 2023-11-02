<?php
function getConnection () {
    $servername = "localhost"; // Replace with your MySQL server hostname
    $username = "cempus"; // Replace with your MySQL username
    $password = "cem1!pus"; // Replace with your MySQL password
    $database = "cempus"; // Replace with the name of your MySQL database

    // Create a connection to the database
    $connection = new mysqli($servername, $username, $password, $database);

    // Check the connection
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
    return $connection;
}
?>



