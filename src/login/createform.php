<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h2>Create an Account</h2>
        <form action="login/create.php" method="POST">
            <label for="id">Student ID:</label>
            <input type="text" id="id" name="id" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="fname">First name:</label>
            <input type="fname" id="fname" name="fname" required>

            <label for="lname">Last name:</label>
            <input type="lname" id="lname" name="lname" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="submit">Create Account</button>
        </form>
        <p>Already have an account? <a href="index.php?page=login">Login here</a></p>
    </div>
</body>
</html>