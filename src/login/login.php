<?php

// This section will check and report any warnings, it will include user
// and password information, and param the queries to prevent any SQLi.

// The code below can be changed if needed.

if(is_array($result) && count ($result) >0) {
    $_SESSION["LoginStatus"]="YES";
    $_SESSION["UserID"]=$result[0]["userID"];
    $_SESSION["Name"]=$result[0]["firstName"]." ".result[0]["lastName"];
    $_SESSION["email"]=$result[0]["email"];
    $_SESSION["ADMIN"]=$result[0]["admin"];
}

// If we have time, we can include profile pictures
// and we will also param the queries to prevent any SQLi.

// Here, there will be a way to check if the user has already made an
// account and if their password is typed in correctly or incorrectly.