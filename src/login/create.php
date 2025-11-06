<?php
require_once "../includes/db_cred.php";
require_once "../includes/jayclosetdb.php";

session_start();
$ID = $_POST["id"]; // Receive the username
$email = $_POST["email"]; // Get email
$firstname = $_POST["fname"];
$lastname = $_POST["lname"];


// Check if chosen username is used.
$qry = "SELECT * FROM users WHERE ID = :i";
$params = [":i"=> $ID];
$users = jayclosetdb::getDataFromSQL($qry,$params);

    // If username is found
    if (is_array($users) && count($users) > 0) { 
        echo "<script>
                alert('Username already exists!');
                window.history.back();
            </script>";
    } else {
        $rawpassword = $_POST['password'];
        $hashed_password = MD5($salt1 . $rawpassword . $salt2);

        // Password requirement variables
        $minlength = 8;
        $maxlength = 20;

        // Check for complex password (minimum length, maximum length, capital letter, lowercase letter, number, special character)
        if (strlen($rawpassword) >= $minlength && strlen($rawpassword) <= $maxlength && preg_match('/[A-Z]/', $rawpassword) && preg_match('/[a-z]/', $rawpassword) && preg_match('/[0-9]/', $rawpassword) && preg_match('/[^A-Za-z0-9]/', $rawpassword)){
            $sql="INSERT INTO users (ID, fname, lname, email, passwrd, isadmin) VALUES (:i, :f, :l, :e, :p, 0);";
            $params=[":i"=> $ID, ":f"=>$firstname, ":l"=>$lastname, ":e"=>$email, ":p"=>$hashed_password]; // No SQLi allowed
            jayclosetdb::executeSQL($sql,$params); // Add user info into database
            header("Location: ../index.php?page=login"); // Redirect to login page
        } else {
            $password_error_message = "Password needs to contain between 8 and 20 characters, a capital letter, a lowercase letter, a number, and a special character."; // Error message
            echo "<script>
                alert(" . json_encode($password_error_message) . ");
                window.history.back();
            </script>"; // Alert including error message
        }

    }
exit();
?>